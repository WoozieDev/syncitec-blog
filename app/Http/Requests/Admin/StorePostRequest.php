<?php

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El Gate se queda en el controller (como vienes trabajando)
        return true;
    }

    public function rules(): array
    {
        $statusValues = array_map(fn ($s) => $s->value, PostStatus::cases());

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'content' => ['nullable', 'string'],

            'status' => ['required', Rule::in($statusValues)],
            'published_at' => ['nullable', 'date'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:2048'],

            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $status = $this->input('status');
            $publishedAt = $this->input('published_at');

            // draft => published_at debe ser null (no error, solo lo normalizamos luego)
            if ($status === PostStatus::Draft->value) {
                return;
            }

            // published o scheduled => published_at requerido (para consistencia)
            if (in_array($status, [PostStatus::Published->value, PostStatus::Scheduled->value], true)) {
                if (blank($publishedAt)) {
                    $validator->errors()->add('published_at', 'Publish date is required for published or scheduled posts.');
                    return;
                }
            }

            // scheduled => debe ser futura
            if ($status === PostStatus::Scheduled->value && filled($publishedAt)) {
                $dt = Carbon::parse($publishedAt);
                if ($dt->lte(now())) {
                    $validator->errors()->add('published_at', 'Scheduled publish date must be in the future.');
                }
            }
        });
    }

    /**
     * Normaliza reglas de negocio.
     */
    public function normalized(): array
    {
        $data = $this->validated();
        $status = $data['status'];

        // draft => null
        if ($status === PostStatus::Draft->value) {
            $data['published_at'] = null;
        }

        // published => si no llega (o llega vacío), set now (pero aquí lo exigimos igual)
        if ($status === PostStatus::Published->value && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }
}

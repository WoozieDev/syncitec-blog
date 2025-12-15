<script setup lang="ts">
import type { SelectOption, TagOption, PostStatus, PostFormData } from '@modules/posts/types/post'

// form viene de useForm(), lo tratamos como objeto reactivo
const props = defineProps<{
    form: PostFormData & {
        errors: Record<string, string>
        processing: boolean
        clearErrors: (field?: string | string[]) => void
    }
    categories: SelectOption[]
    tags: TagOption[]
    statusOptions: PostStatus[]
}>()

const onStatusChange = () => {
    // UX: si cambia a draft, limpiamos published_at
    if (props.form.status === 'draft') {
        props.form.published_at = null
    }
}
</script>

<template>
    <div class="space-y-6">
        <!-- Basic -->
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-sm font-medium">Title</label>
                <input v-model="form.title" type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    placeholder="How to build a clean Laravel app" @input="form.clearErrors('title')" />
                <p v-if="form.errors.title" class="text-xs text-destructive">{{ form.errors.title }}</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Slug (optional)</label>
                <input v-model="form.slug" type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    placeholder="how-to-build-a-clean-laravel-app" @input="form.clearErrors('slug')" />
                <p v-if="form.errors.slug" class="text-xs text-destructive">{{ form.errors.slug }}</p>
                <p class="text-xs text-muted-foreground">If empty, it will be generated from the title.</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Category</label>
                <select v-model="form.category_id"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    @change="form.clearErrors('category_id')">
                    <option :value="null">Select category</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">
                        {{ c.name }}
                    </option>
                </select>
                <p v-if="form.errors.category_id" class="text-xs text-destructive">
                    {{ form.errors.category_id }}
                </p>
            </div>
        </div>

        <!-- Status -->
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1">
                <label class="text-sm font-medium">Status</label>
                <select v-model="form.status"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    @change="() => { form.clearErrors('status'); onStatusChange() }">
                    <option v-for="s in statusOptions" :key="s" :value="s">
                        {{ s }}
                    </option>
                </select>
                <p v-if="form.errors.status" class="text-xs text-destructive">{{ form.errors.status }}</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">Publish date</label>
                <input v-model="form.published_at" type="datetime-local"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :disabled="form.status === 'draft'" @input="form.clearErrors('published_at')" />
                <p v-if="form.errors.published_at" class="text-xs text-destructive">
                    {{ form.errors.published_at }}
                </p>
                <p class="text-xs text-muted-foreground">
                    For <b>scheduled</b>, choose a future datetime. For <b>published</b>, you can set now or leave it
                    and we’ll auto-handle later.
                </p>
            </div>
        </div>

        <!-- Excerpt -->
        <div class="space-y-1">
            <label class="text-sm font-medium">Excerpt (optional)</label>
            <textarea v-model="form.excerpt" rows="3"
                class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                placeholder="Short summary shown in listings." @input="form.clearErrors('excerpt')" />
            <p v-if="form.errors.excerpt" class="text-xs text-destructive">{{ form.errors.excerpt }}</p>
        </div>

        <!-- Content -->
        <div class="space-y-1">
            <label class="text-sm font-medium">Content (optional)</label>
            <textarea v-model="form.content" rows="10"
                class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                placeholder="Write the post content..." @input="form.clearErrors('content')" />
            <p v-if="form.errors.content" class="text-xs text-destructive">{{ form.errors.content }}</p>
        </div>

        <!-- Tags -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label class="text-sm font-medium">Tags (optional)</label>
                <button type="button" class="text-xs text-muted-foreground hover:underline" @click="form.tag_ids = []">
                    Clear
                </button>
            </div>

            <div class="grid gap-2 md:grid-cols-3">
                <label v-for="t in tags" :key="t.id"
                    class="flex items-center gap-2 rounded-md border bg-card px-3 py-2 text-sm">
                    <input v-model="form.tag_ids" type="checkbox" :value="t.id" class="h-4 w-4" />
                    <span>{{ t.name }}</span>
                </label>
            </div>

            <p v-if="form.errors.tag_ids" class="text-xs text-destructive">{{ form.errors.tag_ids }}</p>
        </div>

        <!-- SEO -->
        <div class="rounded-lg border bg-muted/20 p-4 space-y-4">
            <div>
                <h3 class="text-sm font-semibold">SEO</h3>
                <p class="text-xs text-muted-foreground">Optional metadata for search and social previews.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-1">
                    <label class="text-sm font-medium">Meta title</label>
                    <input v-model="form.meta_title" type="text"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        @input="form.clearErrors('meta_title')" />
                    <p v-if="form.errors.meta_title" class="text-xs text-destructive">{{ form.errors.meta_title }}</p>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-medium">Meta description (160)</label>
                    <input v-model="form.meta_description" type="text" maxlength="160"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        @input="form.clearErrors('meta_description')" />
                    <p v-if="form.errors.meta_description" class="text-xs text-destructive">
                        {{ form.errors.meta_description }}
                    </p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-1">
                    <label class="text-sm font-medium">OG title</label>
                    <input v-model="form.og_title" type="text"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        @input="form.clearErrors('og_title')" />
                    <p v-if="form.errors.og_title" class="text-xs text-destructive">{{ form.errors.og_title }}</p>
                </div>

                <div class="space-y-1 md:col-span-2">
                    <label class="text-sm font-medium">OG description</label>
                    <input v-model="form.og_description" type="text"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        @input="form.clearErrors('og_description')" />
                    <p v-if="form.errors.og_description" class="text-xs text-destructive">{{ form.errors.og_description
                        }}</p>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium">OG image (URL/path)</label>
                <input v-model="form.og_image" type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    @input="form.clearErrors('og_image')" />
                <p v-if="form.errors.og_image" class="text-xs text-destructive">{{ form.errors.og_image }}</p>
            </div>
        </div>
    </div>
</template>

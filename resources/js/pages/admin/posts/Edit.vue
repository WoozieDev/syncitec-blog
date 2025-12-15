<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import PostFormFields from '@modules/posts/components/PostFormFields.vue'
import type { SelectOption, TagOption, PostStatus, PostFormData } from '@modules/posts/types/post'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    post: {
        id: number
        category_id: number
        title: string
        slug: string
        excerpt: string | null
        content: string | null
        status: PostStatus
        published_at: string | null // ISO string
        meta_title: string | null
        meta_description: string | null
        og_title: string | null
        og_description: string | null
        og_image: string | null
        tag_ids: number[]
        deleted_at: string | null
    }
    categories: SelectOption[]
    tags: TagOption[]
    statusOptions: PostStatus[]
}>()

const toDatetimeLocal = (iso: string | null): string | null => {
    if (!iso) return null
    const d = new Date(iso)
    const pad = (n: number) => String(n).padStart(2, '0')
    const yyyy = d.getFullYear()
    const mm = pad(d.getMonth() + 1)
    const dd = pad(d.getDate())
    const hh = pad(d.getHours())
    const mi = pad(d.getMinutes())
    return `${yyyy}-${mm}-${dd}T${hh}:${mi}`
}

const form = useForm<PostFormData>({
    category_id: props.post.category_id,
    title: props.post.title,
    slug: props.post.slug,
    excerpt: props.post.excerpt ?? '',
    content: props.post.content ?? '',
    status: props.post.status,
    published_at: toDatetimeLocal(props.post.published_at),
    meta_title: props.post.meta_title ?? '',
    meta_description: props.post.meta_description ?? '',
    og_title: props.post.og_title ?? '',
    og_description: props.post.og_description ?? '',
    og_image: props.post.og_image ?? '',
    tag_ids: props.post.tag_ids ?? [],
})

const submit = () => {
    form.put(`/admin/posts/${props.post.id}`)
}
</script>

<template>
    <div class="space-y-6">
        <header>
            <h2 class="text-lg font-semibold tracking-tight">Edit post</h2>
            <p class="mt-1 text-sm text-muted-foreground">Update content, status and SEO fields.</p>
        </header>

        <div class="rounded-xl border bg-card p-6">
            <form class="space-y-6" @submit.prevent="submit">
                <PostFormFields :form="form" :categories="props.categories" :tags="props.tags"
                    :status-options="props.statusOptions" />

                <div class="flex items-center justify-end gap-2">
                    <Link href="/admin/posts"
                        class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted">
                        Cancel
                    </Link>

                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                        :disabled="form.processing">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

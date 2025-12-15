<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import PostFormFields from '@modules/posts/components/PostFormFields.vue'
import type { SelectOption, TagOption, PostStatus, PostFormData } from '@modules/posts/types/post'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    categories: SelectOption[]
    tags: TagOption[]
    statusOptions: PostStatus[]
}>()

const form = useForm<PostFormData>({
    category_id: null,
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    status: 'draft',
    published_at: null, // datetime-local string
    meta_title: '',
    meta_description: '',
    og_title: '',
    og_description: '',
    og_image: '',
    tag_ids: [],
})

const submit = () => {
    form.post('/admin/posts')
}
</script>

<template>
    <div class="space-y-6">
        <header>
            <h2 class="text-lg font-semibold tracking-tight">Create post</h2>
            <p class="mt-1 text-sm text-muted-foreground">Write and publish content for the blog.</p>
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
                        Create post
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

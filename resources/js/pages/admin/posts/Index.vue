<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

import PostsFilters from '@modules/posts/components/PostsFilters.vue'
import PostsTable from '@modules/posts/components/PostsTable.vue'
import PaginationLinks from '@modules/posts/components/PaginationLinks.vue'

import usePosts from '@modules/posts/composables/usePosts'
import type { PostFilters, PostListItem, Paginated, SelectOption, PostStatus } from '@modules/posts/types/post'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
  title?: string
  posts: Paginated<PostListItem>
  filters: PostFilters
  categories: SelectOption[]
  statusOptions: PostStatus[]
}>()

const page = usePage()
const canManagePosts = computed(() => !!(page.props as any)?.auth?.can?.manage_posts)

const { processing, list } = usePosts()

const onChange = (filters: PostFilters) => list(filters)
const onReset = () => list({ search: null, status: null, category_id: null, trashed: null })
</script>

<template>
  <div class="space-y-6">
    <header class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">Posts</h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Create and manage blog posts (draft, published, scheduled).
        </p>
      </div>

      <Link v-if="canManagePosts" href="/admin/posts/create"
        class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
        New post
      </Link>
    </header>

    <PostsFilters :filters="props.filters" :categories="props.categories" :status-options="props.statusOptions"
      :processing="processing" @change="onChange" @reset="onReset" />

    <PostsTable :posts="props.posts.data" />

    <PaginationLinks :links="props.posts.links" />
  </div>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import TagsFilters from '@modules/tags/components/TagsFilters.vue';
import TagsTable from '@modules/tags/components/TagsTable.vue';
import PaginationLinks from '@modules/tags/components/PaginationLinks.vue';

import useTags from '@modules/tags/composables/useTags';
import type { TagFilters, TagListItem, Paginated } from '@modules/tags/types/tag';

defineOptions({ layout: AdminLayout })

const props = defineProps<{
  title?: string
  tags: Paginated<TagListItem>
  filters: TagFilters
}>()

const page = usePage()
const canManageTags = computed(() => !!(page.props as any)?.auth?.can?.manage_tags)

const { processing, list } = useTags()

const onChange = (filters: TagFilters) => list(filters)
const onReset = () => list({ search: null, trashed: null })
</script>

<template>
  <div class="space-y-6">
    <header class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">Tags</h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Manage blog tags (name and slug).
        </p>
      </div>

      <Link v-if="canManageTags" href="/admin/tags/create"
        class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
        New tag
      </Link>
    </header>

    <TagsFilters :filters="props.filters" :processing="processing" @change="onChange" @reset="onReset" />

    <TagsTable :tags="props.tags.data" />

    <PaginationLinks :links="props.tags.links" />
  </div>
</template>

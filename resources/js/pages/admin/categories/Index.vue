<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import CategoriesFilters from '@modules/categories/components/CategoriesFilters.vue';
import CategoriesTable from '@modules/categories/components/CategoriesTable.vue';
import PaginationLinks from '@modules/categories/components/PaginationLinks.vue';

import useCategories from '@modules/categories/composables/useCategories';
import type { CategoryFilters, CategoryListItem, Paginated } from '@modules/categories/types/category';

defineOptions({ layout: AdminLayout })

const props = defineProps<{
  title?: string
  categories: Paginated<CategoryListItem>
  filters: CategoryFilters
}>()

const page = usePage()
const canCreateCategories = computed(() => !!(page.props as any)?.auth?.can?.categories_create);

const { processing, list } = useCategories();

const onChange = (filters: CategoryFilters) => list(filters);
const onReset = () => list({ search: null, trashed: null });
</script>

<template>
  <div class="space-y-6">
    <header class="flex items-start justify-between gap-3">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">Categories</h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Manage blog categories (name, slug, description).
        </p>
      </div>

      <Link v-if="canCreateCategories" href="/admin/categories/create"
        class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
        New category
      </Link>
    </header>

    <CategoriesFilters :filters="props.filters" :processing="processing" @change="onChange" @reset="onReset" />

    <CategoriesTable :categories="props.categories.data" />

    <PaginationLinks :links="props.categories.links" />
  </div>
</template>

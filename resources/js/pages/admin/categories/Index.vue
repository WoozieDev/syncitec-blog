<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

defineOptions({ layout: AdminLayout })

type CategoryRow = {
  id: number
  name: string
  slug: string
  description: string | null
  deleted_at: string | null
}

type Paginated<T> = {
  data: T[]
  links: { url: string | null; label: string; active: boolean }[]
  meta?: any
}

const props = defineProps<{
  title?: string
  categories: Paginated<CategoryRow>
  filters: {
    search?: string | null
    trashed?: string | null // null | with | only
  }
}>()

const page = usePage()
const canManageCategories = computed(() => !!(page.props as any)?.auth?.can?.manage_categories)

const search = ref(props.filters.search ?? '')
const trashed = ref(props.filters.trashed ?? '')

watch(
  () => props.filters,
  (v) => {
    search.value = v.search ?? ''
    trashed.value = v.trashed ?? ''
  },
)

const applyFilters = () => {
  router.get(
    '/admin/categories',
    {
      search: search.value || null,
      trashed: trashed.value || null,
    },
    { preserveState: true, preserveScroll: true, replace: true },
  )
}

const resetFilters = () => {
  search.value = ''
  trashed.value = ''
  applyFilters()
}

const destroyCategory = (id: number) => {
  if (!confirm('Are you sure you want to delete this category?')) return
  router.delete(`/admin/categories/${id}`, { preserveScroll: true })
}

const restoreCategory = (id: number) => {
  router.patch(`/admin/categories/${id}/restore`, {}, { preserveScroll: true })
}
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

      <Link v-if="canManageCategories" href="/admin/categories/create"
        class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
        New category
      </Link>
    </header>

    <!-- Filters -->
    <div class="rounded-xl border bg-card p-4">
      <div class="grid gap-3 md:grid-cols-3">
        <div class="space-y-1">
          <label class="text-xs font-medium text-muted-foreground">Search</label>
          <input v-model="search" type="text"
            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
            placeholder="Search by name or slug" @keydown.enter.prevent="applyFilters" />
        </div>

        <div class="space-y-1">
          <label class="text-xs font-medium text-muted-foreground">Trashed</label>
          <select v-model="trashed"
            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <option value="">Active</option>
            <option value="with">With trashed</option>
            <option value="only">Only trashed</option>
          </select>
        </div>

        <div class="flex items-end gap-2">
          <button type="button"
            class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            @click="applyFilters">
            Apply
          </button>
          <button type="button"
            class="inline-flex items-center rounded-md border px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-muted"
            @click="resetFilters">
            Reset
          </button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl border bg-card">
      <table class="w-full text-sm">
        <thead class="border-b bg-muted/50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Name</th>
            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Slug</th>
            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Description</th>
            <th class="px-4 py-2 text-right text-xs font-medium uppercase text-muted-foreground">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="cat in props.categories.data" :key="cat.id" class="border-b last:border-b-0">
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <span class="font-medium">{{ cat.name }}</span>
                <span v-if="cat.deleted_at"
                  class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                  Deleted
                </span>
              </div>
            </td>

            <td class="px-4 py-3 text-muted-foreground">
              {{ cat.slug }}
            </td>

            <td class="px-4 py-3 text-muted-foreground">
              {{ cat.description ?? '—' }}
            </td>

            <td class="px-4 py-3 text-right">
              <div class="inline-flex items-center gap-3">
                <Link v-if="canManageCategories && !cat.deleted_at" :href="`/admin/categories/${cat.id}/edit`"
                  class="text-xs text-primary hover:underline">
                  Edit
                </Link>

                <button v-if="canManageCategories && cat.deleted_at" type="button"
                  class="text-xs text-primary hover:underline" @click="restoreCategory(cat.id)">
                  Restore
                </button>

                <button v-if="canManageCategories && !cat.deleted_at" type="button"
                  class="text-xs text-destructive hover:underline" @click="destroyCategory(cat.id)">
                  Delete
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="props.categories.data.length === 0">
            <td colspan="4" class="px-4 py-10 text-center text-sm text-muted-foreground">
              No categories found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination (simple) -->
    <div v-if="props.categories.links?.length" class="flex flex-wrap gap-1">
      <Link v-for="link in props.categories.links" :key="link.label" :href="link.url ?? ''" :class="[
        'rounded-md border px-3 py-1.5 text-sm',
        link.active ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-muted',
        !link.url && 'pointer-events-none opacity-50',
      ]" v-html="link.label" />
    </div>
  </div>
</template>

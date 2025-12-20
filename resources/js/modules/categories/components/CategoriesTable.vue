<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { CategoryListItem } from '@modules/categories/types/category';
import useCategories from '@modules/categories/composables/useCategories';

const props = defineProps<{
    categories: CategoryListItem[]
}>()

const page = usePage()

const canUpdateCategories = computed(() => !!(page.props as any)?.auth?.can?.categories_update);
const canDeleteCategories = computed(() => !!(page.props as any)?.auth?.can?.categories_delete);
const canRestoreCategories = computed(() => !!(page.props as any)?.auth?.can?.categories_restore);

const { processing, destroyCategory, restoreCategory } = useCategories()
</script>

<template>
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
                <tr v-for="category in props.categories" :key="category.id" class="border-b last:border-b-0">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ category.name }}</span>
                            <span v-if="category.deleted_at"
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                                Deleted
                            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-muted-foreground">{{ category.slug }}</td>
                    <td class="px-4 py-3 text-muted-foreground">{{ category.description ?? '—' }}</td>

                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <Link v-if="canUpdateCategories && !category.deleted_at"
                                :href="`/admin/categories/${category.id}/edit`" class="text-xs text-primary hover:underline">
                                Edit
                            </Link>

                            <button v-if="canRestoreCategories && category.deleted_at" type="button"
                                class="text-xs text-primary hover:underline disabled:opacity-50" :disabled="processing"
                                @click="restoreCategory(category.id)">
                                Restore
                            </button>

                            <button v-if="canDeleteCategories && !category.deleted_at" type="button"
                                class="text-xs text-destructive hover:underline disabled:opacity-50"
                                :disabled="processing" @click="destroyCategory(category.id)">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="props.categories.length === 0">
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-muted-foreground">
                        No categories found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { TagListItem } from '@modules/tags/types/tag';
import useTags from '@modules/tags/composables/useTags';

const props = defineProps<{
    tags: TagListItem[]
}>()

const page = usePage()
const canManageTags = computed(() => !!(page.props as any)?.auth?.can?.manage_tags)

const { processing, destroyTag, restoreTag } = useTags()
</script>

<template>
    <div class="rounded-xl border bg-card">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Slug</th>
                    <th class="px-4 py-2 text-right text-xs font-medium uppercase text-muted-foreground">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="tag in props.tags" :key="tag.id" class="border-b last:border-b-0">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ tag.name }}</span>
                            <span v-if="tag.deleted_at"
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                                Deleted
                            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-muted-foreground">{{ tag.slug }}</td>

                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <Link v-if="canManageTags && !tag.deleted_at" :href="`/admin/tags/${tag.id}/edit`"
                                class="text-xs text-primary hover:underline">
                                Edit
                            </Link>

                            <button v-if="canManageTags && tag.deleted_at" type="button"
                                class="text-xs text-primary hover:underline disabled:opacity-50" :disabled="processing"
                                @click="restoreTag(tag.id)">
                                Restore
                            </button>

                            <button v-if="canManageTags && !tag.deleted_at" type="button"
                                class="text-xs text-destructive hover:underline disabled:opacity-50"
                                :disabled="processing" @click="destroyTag(tag.id)">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="props.tags.length === 0">
                    <td colspan="3" class="px-4 py-10 text-center text-sm text-muted-foreground">
                        No tags found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

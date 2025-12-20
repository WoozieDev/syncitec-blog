<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { PostListItem } from '@modules/posts/types/post'
import usePosts from '@modules/posts/composables/usePosts'

const props = defineProps<{ posts: PostListItem[] }>()

const page = usePage()
const canUpdatePosts = computed(() => !!(page.props as any)?.auth?.can?.posts_update);
const canDeletePosts = computed(() => !!(page.props as any)?.auth?.can?.posts_delete);
const canRestorePosts = computed(() => !!(page.props as any)?.auth?.can?.posts_restore);

const { processing, destroyPost, restorePost } = usePosts()

const statusBadgeClass = (status: string) => {
    if (status === 'published') return 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'
    if (status === 'scheduled') return 'bg-amber-500/10 text-amber-600 border-amber-500/20'
    return 'bg-muted text-muted-foreground'
}
</script>

<template>
    <div class="rounded-xl border bg-card">
        <table class="w-full text-sm">
            <thead class="border-b bg-muted/50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Title</th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Category</th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">Author</th>
                    <th class="px-4 py-2 text-right text-xs font-medium uppercase text-muted-foreground">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="post in props.posts" :key="post.id" class="border-b last:border-b-0">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium truncate">{{ post.title }}</span>
                                    <span v-if="post.deleted_at"
                                        class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                                        Deleted
                                    </span>
                                </div>
                                <div class="text-xs text-muted-foreground truncate">{{ post.slug }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-muted-foreground">{{ post.category?.name }}</td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs"
                            :class="statusBadgeClass(post.status)">
                            {{ post.status }}
                        </span>
                        <div v-if="post.published_at" class="mt-1 text-xs text-muted-foreground">
                            {{ post.published_at }}
                        </div>
                    </td>

                    <td class="px-4 py-3 text-muted-foreground">{{ post.author?.name }}</td>

                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <Link v-if="canUpdatePosts && !post.deleted_at" :href="`/admin/posts/${post.id}/edit`"
                                class="text-xs text-primary hover:underline">
                                Edit
                            </Link>

                            <button v-if="canRestorePosts && post.deleted_at" type="button"
                                class="text-xs text-primary hover:underline disabled:opacity-50" :disabled="processing"
                                @click="restorePost(post.id)">
                                Restore
                            </button>

                            <button v-if="canDeletePosts && !post.deleted_at" type="button"
                                class="text-xs text-destructive hover:underline disabled:opacity-50"
                                :disabled="processing" @click="destroyPost(post.id)">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="props.posts.length === 0">
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-muted-foreground">
                        No posts found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

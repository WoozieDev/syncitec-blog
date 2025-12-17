<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import FlashMessage from '@modules/core/components/FlashMessage.vue';
import { useCommentFilters } from '@modules/comments/composables/useCommentFilters';
import type { CommentRow } from '@modules/comments/types/comment';
import { router, Link } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout })

type PaginationLink = { url: string | null; label: string; active: boolean }
type Paginated<T> = { data: T[]; links: PaginationLink[]; total: number }

const props = defineProps<{
  title?: string
  comments: Paginated<CommentRow>
  statusOptions: { value: string; label: string }[]
}>()

const { status, q, reset } = useCommentFilters()

const approve = (id: number) => {
  router.patch(`/admin/comments/${id}/approve`, {}, { preserveScroll: true })
}

const reject = (id: number) => {
  router.patch(`/admin/comments/${id}/reject`, {}, { preserveScroll: true })
}

const badgeClass = (s: string) => {
  if (s === 'approved') return 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20'
  if (s === 'rejected') return 'bg-rose-500/10 text-rose-500 border-rose-500/20'
  return 'bg-amber-500/10 text-amber-500 border-amber-500/20'
}
</script>

<template>
  <div class="space-y-6">

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-lg font-semibold tracking-tight">Comments</h1>
        <p class="text-sm text-muted-foreground">Moderate reader comments.</p>
      </div>

      <button type="button" class="rounded-md border px-3 py-2 text-sm text-muted-foreground hover:bg-muted"
        @click="reset">
        Reset filters
      </button>
    </div>

    <!-- Filters -->
    <div class="grid gap-3 rounded-xl border bg-card p-4 md:grid-cols-3">
      <div class="space-y-1">
        <label class="text-xs font-medium text-muted-foreground">Status</label>
        <select v-model="status"
          class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring">
          <option v-for="opt in props.statusOptions" :key="opt.value" :value="opt.value">
            {{ opt.label }}
          </option>
        </select>
      </div>

      <div class="space-y-1 md:col-span-2">
        <label class="text-xs font-medium text-muted-foreground">Search</label>
        <input v-model="q" type="text" placeholder="Search by comment, post title, user name/email..."
          class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" />
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border bg-card">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="border-b bg-muted/30 text-xs text-muted-foreground">
            <tr>
              <th class="px-4 py-3 text-left font-medium">Status</th>
              <th class="px-4 py-3 text-left font-medium">Comment</th>
              <th class="px-4 py-3 text-left font-medium">User</th>
              <th class="px-4 py-3 text-left font-medium">Post</th>
              <th class="px-4 py-3 text-right font-medium">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="c in props.comments.data" :key="c.id" class="border-b last:border-0">
              <td class="px-4 py-3">
                <span
                  :class="['inline-flex items-center rounded-full border px-2 py-0.5 text-xs', badgeClass(c.status)]">
                  {{ c.status }}
                </span>
              </td>

              <td class="px-4 py-3">
                <div class="line-clamp-3 text-foreground/90 whitespace-pre-wrap">
                  {{ c.body }}
                </div>
                <div class="mt-1 text-xs text-muted-foreground">
                  {{ c.created_at }}
                </div>
              </td>

              <td class="px-4 py-3">
                <div class="font-medium">{{ c.user.name }}</div>
                <div class="text-xs text-muted-foreground">{{ c.user.email }}</div>
              </td>

              <td class="px-4 py-3">
                <Link :href="`/posts/${c.post.slug}`" class="text-primary hover:underline">
                  {{ c.post.title }}
                </Link>
              </td>

              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <button type="button" class="rounded-md border px-3 py-1.5 text-xs hover:bg-muted disabled:opacity-50"
                    :disabled="c.status === 'approved'" @click="approve(c.id)">
                    Approve
                  </button>
                  <button type="button" class="rounded-md border px-3 py-1.5 text-xs hover:bg-muted disabled:opacity-50"
                    :disabled="c.status === 'rejected'" @click="reject(c.id)">
                    Reject
                  </button>
                </div>
              </td>
            </tr>

            <tr v-if="props.comments.data.length === 0">
              <td colspan="5" class="px-4 py-10 text-center text-sm text-muted-foreground">
                No comments found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="props.comments.links?.length" class="flex flex-wrap gap-1 border-t p-3">
        <Link v-for="link in props.comments.links" :key="link.label" :href="link.url ?? ''" :class="[
          'rounded-md border px-3 py-1.5 text-sm',
          link.active ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-muted',
          !link.url && 'pointer-events-none opacity-50',
        ]" v-html="link.label" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import type { PostFilters, PostStatus, SelectOption } from '@modules/posts/types/post'

const props = defineProps<{
    filters: PostFilters
    categories: SelectOption[]
    statusOptions: PostStatus[]
    processing?: boolean
}>()

const emit = defineEmits<{
    (e: 'change', filters: PostFilters): void
    (e: 'reset'): void
}>()

const search = ref(props.filters.search ?? '')
const status = ref<string>(props.filters.status ?? '')
const categoryId = ref<string>(props.filters.category_id ? String(props.filters.category_id) : '')
const trashed = ref<string>(props.filters.trashed ?? '')

watch(
    () => props.filters,
    (v) => {
        search.value = v.search ?? ''
        status.value = (v.status ?? '') as any
        categoryId.value = v.category_id ? String(v.category_id) : ''
        trashed.value = (v.trashed ?? '') as any
    },
    { deep: true },
)

let t: number | null = null
const emitDebounced = () => {
    if (t) window.clearTimeout(t)
    t = window.setTimeout(() => {
        emit('change', {
            search: search.value || null,
            status: (status.value || null) as any,
            category_id: categoryId.value ? Number(categoryId.value) : null,
            trashed: (trashed.value || null) as any,
        })
    }, 400)
}

watch(search, () => emitDebounced())
watch([status, categoryId, trashed], () => {
    emit('change', {
        search: search.value || null,
        status: (status.value || null) as any,
        category_id: categoryId.value ? Number(categoryId.value) : null,
        trashed: (trashed.value || null) as any,
    })
})

const reset = () => {
    search.value = ''
    status.value = ''
    categoryId.value = ''
    trashed.value = ''
    emit('reset')
}
</script>

<template>
    <div class="rounded-xl border bg-card p-4">
        <div class="grid gap-3 md:grid-cols-4">
            <div class="space-y-1 md:col-span-2">
                <label class="text-xs font-medium text-muted-foreground">Search</label>
                <input v-model="search" type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    placeholder="Search by title or slug" :disabled="processing" />
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-muted-foreground">Status</label>
                <select v-model="status"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :disabled="processing">
                    <option value="">All</option>
                    <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-muted-foreground">Category</label>
                <select v-model="categoryId"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :disabled="processing">
                    <option value="">All</option>
                    <option v-for="c in categories" :key="c.id" :value="String(c.id)">{{ c.name }}</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-muted-foreground">Trashed</label>
                <select v-model="trashed"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :disabled="processing">
                    <option value="">Active</option>
                    <option value="with">With trashed</option>
                    <option value="only">Only trashed</option>
                </select>
            </div>

            <div class="flex items-end justify-end md:col-span-4">
                <button type="button"
                    class="inline-flex items-center rounded-md border px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-muted disabled:opacity-50"
                    :disabled="processing" @click="reset">
                    Clear
                </button>
            </div>
        </div>
    </div>
</template>

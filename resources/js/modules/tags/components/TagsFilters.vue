<script setup lang="ts">
import { ref, watch } from 'vue';
import type { TagFilters } from '@modules/tags/types/tag';

const props = defineProps<{
    filters: TagFilters
    processing?: boolean
}>()

const emit = defineEmits<{
    (e: 'change', filters: TagFilters): void
    (e: 'reset'): void
}>()

const search = ref(props.filters.search ?? '')
const trashed = ref(props.filters.trashed ?? '')

watch(
    () => props.filters,
    (v) => {
        search.value = v.search ?? ''
        trashed.value = v.trashed ?? ''
    },
    { deep: true },
)

let t: number | null = null
const emitChangeDebounced = () => {
    if (t) window.clearTimeout(t)
    t = window.setTimeout(() => {
        emit('change', {
            search: search.value || null,
            trashed: (trashed.value || null) as any,
        })
    }, 400)
}

watch(search, () => emitChangeDebounced())

watch(trashed, () => {
    emit('change', {
        search: search.value || null,
        trashed: (trashed.value || null) as any,
    })
})

const reset = () => {
    search.value = ''
    trashed.value = ''
    emit('reset')
}
</script>

<template>
    <div class="rounded-xl border bg-card p-4">
        <div class="grid gap-3 md:grid-cols-3">
            <div class="space-y-1">
                <label class="text-xs font-medium text-muted-foreground">Search</label>
                <input v-model="search" type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    placeholder="Search by name or slug" :disabled="processing" />
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

            <div class="flex items-end justify-end">
                <button type="button"
                    class="inline-flex items-center rounded-md border px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-muted disabled:opacity-50"
                    :disabled="processing" @click="reset">
                    Clear
                </button>
            </div>
        </div>
    </div>
</template>

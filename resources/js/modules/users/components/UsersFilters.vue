<script setup lang="ts">
import { ref, watch } from 'vue';
import useUsers from '@modules/users/composables/useUsers';

const props = defineProps<{
    filters: {
        search?: string | null
        role?: string | null
        trashed?: string | null
    }
    roleOptions: string[]
}>()

const { index, processing } = useUsers()

const search = ref(props.filters.search ?? '')
const role = ref<string | null>(props.filters.role ?? null)
const trashed = ref<string | null>(props.filters.trashed ?? null)

const applyFilters = () => {
    index({
        search: search.value || null,
        role: role.value || null,
        trashed: trashed.value || null,
    })
}

// para debounce de búsqueda básico
let searchTimeout: number | undefined

watch(search, () => {
    window.clearTimeout(searchTimeout)
    searchTimeout = window.setTimeout(() => {
        applyFilters()
    }, 400)
})
</script>

<template>
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div class="flex flex-1 flex-col gap-2 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-muted-foreground">
                    Search
                </label>
                <input v-model="search" type="text" placeholder="Name or email…"
                    class="mt-1 w-full rounded-md border bg-background px-3 py-1.5 text-sm outline-none ring-offset-background focus-visible:ring-2 focus-visible:ring-primary" />
            </div>

            <div class="w-full md:w-48">
                <label class="block text-xs font-medium text-muted-foreground">
                    Role
                </label>
                <select v-model="role" class="mt-1 w-full rounded-md border bg-background px-3 py-1.5 text-sm"
                    @change="applyFilters">
                    <option :value="null">All</option>
                    <option v-for="name in roleOptions" :key="name" :value="name">
                        {{ name }}
                    </option>
                </select>
            </div>

            <div class="w-full md:w-40">
                <label class="block text-xs font-medium text-muted-foreground">
                    Deleted
                </label>
                <select v-model="trashed" class="mt-1 w-full rounded-md border bg-background px-3 py-1.5 text-sm"
                    @change="applyFilters">
                    <option :value="null">Without deleted</option>
                    <option value="with">With deleted</option>
                    <option value="only">Only deleted</option>
                </select>
            </div>
        </div>

        <button type="button"
            class="inline-flex items-center justify-center rounded-md bg-muted px-3 py-1.5 text-xs font-medium text-muted-foreground hover:bg-muted/80 md:self-auto"
            :disabled="processing"
            @click="() => { search = ''; role = null; trashed = null; applyFilters() }">
            Reset filters
        </button>
    </div>
</template>

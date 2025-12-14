<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import UsersFilters from '@modules/users/components/UsersFilters.vue'
import UsersTable from '@modules/users/components/UsersTable.vue'
import type { Paginated } from '@modules/core/types'
import type { UserListItem } from '@modules/users/types/user'

defineOptions({
    layout: AdminLayout,
})

const props = defineProps<{
    title?: string
    users: Paginated<UserListItem>
    filters: {
        search?: string | null
        role?: string | null
        trashed?: string | null
    }
    roleOptions: string[]
}>()
</script>

<template>
    <div class="space-y-4">
        <header class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold tracking-tight">
                    Users
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Gestión de usuarios del sistema (admin, editor, reader).
                </p>
            </div>
        </header>

        <UsersFilters :filters="props.filters" :role-options="props.roleOptions" />

        <UsersTable :users="props.users" />
    </div>
</template>

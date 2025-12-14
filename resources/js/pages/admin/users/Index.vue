<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import UsersFilters from '@modules/users/components/UsersFilters.vue';
import UsersTable from '@modules/users/components/UsersTable.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { Paginated } from '@modules/core/types';
import type { UserListItem } from '@modules/users/types/user';

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

const page = usePage()
const canManageUsers = computed(() => !!(page.props as any)?.auth?.can?.manage_users)

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

            <Link
                v-if="canManageUsers"
                href="/admin/users/create"
                class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
            >
                New user
            </Link>
        </header>

        <UsersFilters :filters="props.filters" :role-options="props.roleOptions" />

        <UsersTable :users="props.users" />
    </div>
</template>

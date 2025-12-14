<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { Paginated } from '@modules/core/types';
import type { UserListItem } from '@modules/users/types/user';
import useUsers from '@modules/users/composables/useUsers';


const props = defineProps<{
    users: Paginated<UserListItem>
}>()

const { destroyUser, processing } = useUsers()
const page = usePage()

const canManageUsers = computed<boolean>(
    () => !!(page.props as any).auth?.can?.manage_users,
)
</script>

<template>
    <div class="overflow-hidden rounded-xl border bg-card">
        <table class="min-w-full divide-y divide-border text-sm">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">
                        Name
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">
                        Email
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">
                        Roles
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-medium uppercase text-muted-foreground">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <tr v-for="user in users.data" :key="user.id">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <span v-if="user.deleted_at" class="inline-flex h-2 w-2 rounded-full bg-destructive"
                                title="Deleted" />
                            <span>{{ user.name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-muted-foreground">
                        {{ user.email }}
                    </td>
                    <td class="px-4 py-2">
                        <span v-for="role in user.roles" :key="role.id"
                            class="mr-1 inline-flex items-center rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground">
                            {{ role.name }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link v-if="canManageUsers" :href="`/admin/users/${user.id}/edit`"
                                class="text-xs text-primary hover:underline">
                                Edit
                            </Link>
                            <button v-if="canManageUsers && !user.deleted_at" type="button"
                                class="text-xs text-destructive hover:underline disabled:opacity-50"
                                :disabled="processing" @click="destroyUser(user.id)">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TODO: después añadimos un componente de paginación core -->
    </div>
</template>

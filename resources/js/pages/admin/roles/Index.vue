<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
  title?: string
  roles: {
    id: number
    name: string
    permissions_count: number
  }[]
}>()

const page = usePage()
const canManageRoles = computed(() => !!(page.props as any)?.auth?.can?.roles_view)
</script>

<template>
  <div class="space-y-6">
    <header>
      <h2 class="text-lg font-semibold tracking-tight">Roles</h2>
      <p class="mt-1 text-sm text-muted-foreground">
        Manage permissions assigned to system roles.
      </p>
    </header>

    <div class="rounded-xl border bg-card">
      <table class="w-full text-sm">
        <thead class="border-b bg-muted/50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">
              Role
            </th>
            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-muted-foreground">
              Permissions
            </th>
            <th class="px-4 py-2 text-right text-xs font-medium uppercase text-muted-foreground">
              Actions
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="role in props.roles" :key="role.id" class="border-b last:border-b-0">
            <td class="px-4 py-3 font-medium capitalize">
              {{ role.name }}
            </td>

            <td class="px-4 py-3 text-muted-foreground">
              {{ role.permissions_count }}
            </td>

            <td class="px-4 py-3 text-right">
              <Link v-if="canManageRoles" :href="`/admin/roles/${role.id}/edit`"
                class="text-xs text-primary hover:underline">
                Edit permissions
              </Link>

              <span v-else class="text-xs text-muted-foreground">
                Not allowed
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

type Permission = {
  id: number
  name: string
  display_name: string | null
  description: string | null
}

const props = defineProps<{
  permissions: Permission[]
}>()

/**
 * Agrupar permisos por módulo
 * posts.create → posts
 */
const groupedPermissions = computed(() => {
  const map: Record<string, Permission[]> = {}

  props.permissions.forEach((permission) => {
    const [module] = permission.name.split('.')
    map[module] ??= []
    map[module].push(permission)
  })

  return Object.entries(map).map(([module, items]) => ({
    module,
    title: module.charAt(0).toUpperCase() + module.slice(1),
    items: items.sort((a, b) => a.name.localeCompare(b.name)),
  }))
})
</script>

<template>
  <AdminLayout>

    <Head title="Permissions" />

    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-xl font-semibold">Permissions</h1>
        <p class="text-sm text-muted-foreground">
          Manage system permissions grouped by module.
        </p>
      </div>

      <!-- Permission groups -->
      <div class="space-y-6">
        <section v-for="group in groupedPermissions" :key="group.module" class="rounded-xl border bg-card">
          <!-- Module header -->
          <div class="border-b px-4 py-3">
            <h2 class="text-sm font-semibold tracking-wide uppercase">
              {{ group.title }}
            </h2>
          </div>

          <!-- Permissions list -->
          <ul class="divide-y">
            <li v-for="permission in group.items" :key="permission.id"
              class="flex items-start justify-between gap-4 px-4 py-3 hover:bg-muted/50">
              <div class="min-w-0">
                <p class="text-sm font-medium">
                  {{ permission.display_name ?? permission.name }}
                </p>

                <p v-if="permission.description" class="mt-0.5 text-xs text-muted-foreground">
                  {{ permission.description }}
                </p>
              </div>

              <!-- Slug -->
              <span class="shrink-0 rounded-md bg-muted px-2 py-0.5 text-xs text-muted-foreground">
                {{ permission.name }}
              </span>
            </li>
          </ul>
        </section>
      </div>
    </div>
  </AdminLayout>
</template>

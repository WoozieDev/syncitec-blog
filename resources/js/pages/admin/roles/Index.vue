<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
  layout: AdminLayout,
})

defineProps<{
  title?: string
  roles: any
}>()
</script>

<template>
  <div class="space-y-4">
    <header class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">
          Roles
        </h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Define los roles del sistema y su relación con los permisos.
        </p>
      </div>
    </header>

    <div class="rounded-xl border bg-card p-4 text-sm text-muted-foreground">
      Roles module coming soon…
    </div>
  </div>
</template>

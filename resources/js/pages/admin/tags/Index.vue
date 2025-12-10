<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
  layout: AdminLayout,
})

defineProps<{
  title?: string
  tags: any
}>()
</script>

<template>
  <div class="space-y-4">
    <header class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">
          Tags
        </h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Etiquetas flexibles para clasificar posts.
        </p>
      </div>
    </header>

    <div class="rounded-xl border bg-card p-4 text-sm text-muted-foreground">
      Tags module coming soon…
    </div>
  </div>
</template>

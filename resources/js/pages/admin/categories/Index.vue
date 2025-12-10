<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
  layout: AdminLayout,
})

defineProps<{
  title?: string
  categories: any
}>()
</script>

<template>
  <div class="space-y-4">
    <header class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">
          Categories
        </h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Organiza los posts del blog en categorías.
        </p>
      </div>
    </header>

    <div class="rounded-xl border bg-card p-4 text-sm text-muted-foreground">
      Categories module coming soon…
    </div>
  </div>
</template>

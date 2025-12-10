<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
  layout: AdminLayout,
})

defineProps<{
  title?: string
  comments: any
}>()
</script>

<template>
  <div class="space-y-4">
    <header class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold tracking-tight">
          Comments
        </h2>
        <p class="mt-1 text-sm text-muted-foreground">
          Modera los comentarios del blog.
        </p>
      </div>
    </header>

    <div class="rounded-xl border bg-card p-4 text-sm text-muted-foreground">
      Comments module coming soon…
    </div>
  </div>
</template>

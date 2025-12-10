<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout,
})

defineProps<{
    title?: string
}>()
</script>

<template>
    <div class="space-y-6">
        <section>
            <h2 class="text-lg font-semibold tracking-tight">
                Welcome to Syncitec Blog admin
            </h2>
            <p class="mt-2 text-sm text-muted-foreground">
                Aquí luego mostraremos métricas.
            </p>
        </section>
    </div>
</template>

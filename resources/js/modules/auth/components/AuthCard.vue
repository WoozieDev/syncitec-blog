<script setup lang="ts">
defineProps<{
    title: string
    subtitle?: string
}>()
</script>

<template>
    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border bg-card p-6 md:p-8">
            <div class="space-y-1 text-center">
                <h1 class="text-xl font-semibold tracking-tight">{{ title }}</h1>
                <p v-if="subtitle" class="text-sm text-muted-foreground">{{ subtitle }}</p>
            </div>

            <div class="mt-6">
                <slot />
            </div>
        </div>
    </div>
</template>

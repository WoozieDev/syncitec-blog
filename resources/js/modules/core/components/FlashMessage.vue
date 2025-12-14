<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const success = computed(() => (page.props as any)?.flash?.success as string | null)
const error = computed(() => (page.props as any)?.flash?.error as string | null)
</script>

<template>
    <div class="space-y-3">
        <div v-if="success" class="rounded-lg border bg-card px-4 py-3 text-sm">
            <p class="font-medium text-primary">Success</p>
            <p class="mt-1 text-muted-foreground">{{ success }}</p>
        </div>

        <div v-if="error" class="rounded-lg border bg-card px-4 py-3 text-sm">
            <p class="font-medium text-destructive">Error</p>
            <p class="mt-1 text-muted-foreground">{{ error }}</p>
        </div>
    </div>
</template>

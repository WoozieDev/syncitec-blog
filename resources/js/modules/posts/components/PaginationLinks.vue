<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { PaginationLink } from '@modules/posts/types/post'

defineProps<{ links: PaginationLink[] }>()
</script>

<template>
    <div v-if="links?.length" class="flex flex-wrap gap-1">
        <Link v-for="link in links" :key="link.label" :href="link.url ?? ''" :class="[
            'rounded-md border px-3 py-1.5 text-sm',
            link.active ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-muted',
            !link.url && 'pointer-events-none opacity-50',
        ]" v-html="link.label" />
    </div>
</template>

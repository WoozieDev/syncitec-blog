<script setup lang="ts">
import BlogLayout from '@modules/core/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: BlogLayout })

const props = defineProps<{
    title?: string
    post: {
        id: number
        title: string
        slug: string
        excerpt: string | null
        content: string | null
        published_at: string | null
        category: { id: number; name: string; slug?: string }
        author: { id: number; name: string }
        tags: { id: number; name: string; slug?: string }[]
        meta_title: string | null
        meta_description: string | null
        og_title: string | null
        og_description: string | null
        og_image: string | null
    }
}>()
</script>

<template>
    <article class="space-y-6">
        <header class="space-y-2">
            <div class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                <Link href="/" class="hover:underline">Home</Link>
                <span>•</span>
                <span class="font-medium text-foreground">{{ props.post.category?.name }}</span>
                <span>•</span>
                <span>{{ props.post.author?.name }}</span>
                <span v-if="props.post.published_at">• {{ props.post.published_at }}</span>
            </div>

            <h1 class="text-2xl font-semibold tracking-tight md:text-3xl">
                {{ props.post.title }}
            </h1>

            <p v-if="props.post.excerpt" class="text-sm text-muted-foreground">
                {{ props.post.excerpt }}
            </p>

            <div v-if="props.post.tags?.length" class="flex flex-wrap gap-2 pt-2">
                <span v-for="t in props.post.tags" :key="t.id"
                    class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                    #{{ t.name }}
                </span>
            </div>
        </header>

        <div class="prose prose-neutral max-w-none dark:prose-invert">
            <!-- MVP: contenido como texto plano -->
            <pre class="whitespace-pre-wrap font-sans text-sm">{{ props.post.content ?? '' }}</pre>
        </div>
    </article>
</template>

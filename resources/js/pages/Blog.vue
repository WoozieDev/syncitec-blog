<script setup lang="ts">
import BlogLayout from '@modules/core/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'

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
    <article class="mx-auto max-w-3xl space-y-8">
        <!-- Breadcrumb -->
        <nav class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
            <Link href="/" class="hover:underline">Home</Link>
            <span>•</span>

            <Link v-if="props.post.category?.slug" :href="`/categories/${props.post.category.slug}`"
                class="hover:underline">
                {{ props.post.category.name }}
            </Link>

            <span>•</span>
            <span class="text-foreground font-medium">{{ props.post.title }}</span>
        </nav>

        <!-- Header -->
        <header class="space-y-3">
            <h1 class="text-2xl font-semibold tracking-tight md:text-3xl">
                {{ props.post.title }}
            </h1>

            <p v-if="props.post.excerpt" class="text-sm text-muted-foreground md:text-base">
                {{ props.post.excerpt }}
            </p>

            <div class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                <span class="font-medium text-foreground">
                    {{ props.post.author?.name }}
                </span>

                <span>•</span>

                <span v-if="props.post.published_at">
                    {{ props.post.published_at }}
                </span>
            </div>

            <!-- Tags -->
            <div v-if="props.post.tags?.length" class="flex flex-wrap gap-2 pt-2">
                <Link v-for="t in props.post.tags" :key="t.id" :href="`/tags/${t.slug}`"
                    class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground hover:bg-muted hover:text-foreground transition-colors">
                    #{{ t.name }}
                </Link>
            </div>
        </header>

        <!-- Content -->
        <section
            class="prose prose-neutral max-w-none dark:prose-invert prose-pre:bg-muted prose-pre:border prose-pre:rounded-lg">
            <!-- MVP: texto plano -->
            <pre class="whitespace-pre-wrap font-sans text-sm leading-relaxed">
{{ props.post.content ?? '' }}
      </pre>
        </section>

        <!-- Footer navigation -->
        <footer class="border-t pt-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <Link href="/" class="text-sm text-muted-foreground hover:text-foreground hover:underline">
                    ← Back to home
                </Link>

                <div class="flex flex-wrap gap-2">
                    <Link v-if="props.post.category?.slug" :href="`/categories/${props.post.category.slug}`"
                        class="rounded-md border px-3 py-1.5 text-xs font-medium text-muted-foreground hover:bg-muted">
                        More in {{ props.post.category.name }}
                    </Link>

                    <Link href="/"
                        class="rounded-md border px-3 py-1.5 text-xs font-medium text-muted-foreground hover:bg-muted">
                        Latest posts
                    </Link>
                </div>
            </div>
        </footer>
    </article>
</template>

<script setup lang="ts">
import BlogLayout from '@modules/core/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: BlogLayout })

type PostCard = {
    id: number
    title: string
    slug: string
    excerpt: string | null
    published_at: string | null
    category: { id: number; name: string; slug?: string }
    author: { id: number; name: string }
    tags: { id: number; name: string; slug?: string }[]
}

type PaginationLink = { url: string | null; label: string; active: boolean }
type Paginated<T> = { data: T[]; links: PaginationLink[] }

const props = defineProps<{
    title?: string
    posts: Paginated<PostCard>
}>()
</script>

<template>
    <div class="space-y-8">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight md:text-3xl">Tech & Development</h1>
            <p class="text-sm text-muted-foreground">
                Articles about Laravel, Vue, architecture, and shipping software.
            </p>
        </header>

        <section class="grid gap-4">
            <article v-for="post in props.posts.data" :key="post.id"
                class="rounded-xl border bg-card p-5 hover:bg-muted/20 transition-colors">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-xs text-muted-foreground">
                        <span class="font-medium text-foreground">{{ post.category?.name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ post.author?.name }}</span>
                    </div>

                    <div v-if="post.published_at" class="text-xs text-muted-foreground">
                        {{ post.published_at }}
                    </div>
                </div>

                <h2 class="mt-2 text-lg font-semibold tracking-tight">
                    <Link :href="`/posts/${post.slug}`" class="hover:underline">
                        {{ post.title }}
                    </Link>
                </h2>

                <p v-if="post.excerpt" class="mt-2 text-sm text-muted-foreground">
                    {{ post.excerpt }}
                </p>

                <div v-if="post.tags?.length" class="mt-4 flex flex-wrap gap-2">
                    <span v-for="t in post.tags" :key="t.id"
                        class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground">
                        #{{ t.name }}
                    </span>
                </div>
            </article>

            <div v-if="props.posts.data.length === 0"
                class="rounded-xl border bg-card p-10 text-center text-sm text-muted-foreground">
                No posts yet.
            </div>
        </section>

        <div v-if="props.posts.links?.length" class="flex flex-wrap gap-1">
            <Link v-for="link in props.posts.links" :key="link.label" :href="link.url ?? ''" :class="[
                'rounded-md border px-3 py-1.5 text-sm',
                link.active ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-muted',
                !link.url && 'pointer-events-none opacity-50',
            ]" v-html="link.label" />
        </div>
    </div>
</template>

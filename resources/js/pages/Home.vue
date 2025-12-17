<script setup lang="ts">
import BlogLayout from '@modules/core/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: BlogLayout })

type Category = { id: number; name: string; slug: string }
type Tag = { id: number; name: string; slug: string }

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
    featured: null | {
        title: string
        slug: string
        excerpt: string | null
        published_at: string | null
        category: { id: number; name: string; slug?: string }
        author: { id: number; name: string }
        tags: { id: number; name: string; slug?: string }[]
    }
    posts: Paginated<PostCard>
    categories: Category[]
    tags: Tag[]
}>()
</script>

<template>
    <div class="space-y-8">
        <!-- HERO -->
        <section class="relative overflow-hidden rounded-2xl border bg-card p-6 md:p-10">
            <div class="pointer-events-none absolute -right-24 -top-24 h-64 w-64 rounded-full bg-primary/15 blur-3xl" />
            <div
                class="pointer-events-none absolute -left-24 -bottom-24 h-64 w-64 rounded-full bg-primary/10 blur-3xl" />

            <div class="grid gap-6 md:grid-cols-2 md:items-center">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border bg-muted/30 px-3 py-1 text-xs text-muted-foreground">
                        <span class="h-1.5 w-1.5 rounded-full bg-primary" />
                        Shipping software, one post at a time
                    </div>

                    <h1 class="text-2xl font-semibold tracking-tight md:text-4xl">
                        Tech & Development Notes
                    </h1>

                    <p class="text-sm text-muted-foreground md:text-base">
                        Laravel 12, Vue 3, clean architecture, performance and product thinking. Short, practical, and
                        battle-tested.
                    </p>

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <Link href="/"
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                            Explore latest
                        </Link>

                        <a href="#latest"
                            class="rounded-md border px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted">
                            Jump to posts
                        </a>
                    </div>
                </div>

                <!-- Featured -->
                <div class="rounded-xl border bg-muted/20 p-5">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Featured</div>
                        <div v-if="props.featured?.published_at" class="text-xs text-muted-foreground">
                            {{ props.featured.published_at }}
                        </div>
                    </div>

                    <div v-if="props.featured" class="mt-3 space-y-2">
                        <div class="text-xs text-muted-foreground">
                            <span class="font-medium text-foreground">{{ props.featured.category?.name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ props.featured.author?.name }}</span>
                        </div>

                        <Link :href="`/posts/${props.featured.slug}`"
                            class="block text-lg font-semibold tracking-tight hover:underline">
                            {{ props.featured.title }}
                        </Link>

                        <p v-if="props.featured.excerpt" class="text-sm text-muted-foreground">
                            {{ props.featured.excerpt }}
                        </p>

                        <div v-if="props.featured.tags?.length" class="flex flex-wrap gap-2 pt-1">
                            <Link v-for="t in props.featured.tags" :key="t.id" :href="`/tags/${t.slug}`"
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground hover:bg-muted">
                                #{{ t.name }}
                            </Link>
                        </div>
                    </div>

                    <div v-else class="mt-3 text-sm text-muted-foreground">
                        No featured post yet.
                    </div>
                </div>
            </div>
        </section>

        <!-- GRID: Latest + Sidebar -->
        <section class="grid gap-6 lg:grid-cols-3" id="latest">
            <!-- Latest posts -->
            <div class="space-y-4 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold tracking-tight">Latest posts</h2>
                    <div class="text-xs text-muted-foreground">
                        {{ props.posts.data.length }} shown
                    </div>
                </div>

                <article v-for="post in props.posts.data" :key="post.id"
                    class="group rounded-xl border bg-card p-5 hover:bg-muted/20 transition-colors">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-xs text-muted-foreground">
                            <Link v-if="post.category?.slug" :href="`/categories/${post.category.slug}`"
                                class="font-medium text-foreground hover:underline">
                                {{ post.category.name }}
                            </Link>
                            <span v-else class="font-medium text-foreground">{{ post.category?.name }}</span>

                            <span class="mx-2">•</span>
                            <span>{{ post.author?.name }}</span>
                        </div>

                        <div v-if="post.published_at" class="text-xs text-muted-foreground">
                            {{ post.published_at }}
                        </div>
                    </div>

                    <h3 class="mt-2 text-lg font-semibold tracking-tight">
                        <Link :href="`/posts/${post.slug}`" class="hover:underline">
                            {{ post.title }}
                        </Link>
                    </h3>

                    <p v-if="post.excerpt" class="mt-2 text-sm text-muted-foreground">
                        {{ post.excerpt }}
                    </p>

                    <div v-if="post.tags?.length" class="mt-4 flex flex-wrap gap-2">
                        <Link v-for="t in post.tags" :key="t.id" :href="`/tags/${t.slug}`"
                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-muted-foreground hover:bg-muted">
                            #{{ t.name }}
                        </Link>
                    </div>
                </article>

                <div v-if="props.posts.data.length === 0"
                    class="rounded-xl border bg-card p-10 text-center text-sm text-muted-foreground">
                    No posts yet.
                </div>

                <!-- Pagination -->
                <div v-if="props.posts.links?.length" class="flex flex-wrap gap-1 pt-2">
                    <Link v-for="link in props.posts.links" :key="link.label" :href="link.url ?? ''" :class="[
                        'rounded-md border px-3 py-1.5 text-sm',
                        link.active ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:bg-muted',
                        !link.url && 'pointer-events-none opacity-50',
                    ]" v-html="link.label" />
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-4">
                <!-- Categories -->
                <div class="rounded-xl border bg-card p-5">
                    <h3 class="text-sm font-semibold">Categories</h3>
                    <div class="mt-3 grid gap-2">
                        <Link v-for="c in props.categories" :key="c.id" :href="`/categories/${c.slug}`"
                            class="flex items-center justify-between rounded-md px-2 py-2 text-sm text-muted-foreground hover:bg-muted hover:text-foreground">
                            <span>{{ c.name }}</span>
                            <span class="text-xs">→</span>
                        </Link>
                    </div>
                </div>

                <!-- Tags -->
                <div class="rounded-xl border bg-card p-5">
                    <h3 class="text-sm font-semibold">Trending tags</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <Link v-for="t in props.tags" :key="t.id" :href="`/tags/${t.slug}`"
                            class="inline-flex items-center rounded-full border px-2 py-1 text-xs text-muted-foreground hover:bg-muted">
                            #{{ t.name }}
                        </Link>
                    </div>
                </div>

                <!-- Banner / Info -->
                <div class="rounded-xl border bg-muted/20 p-5">
                    <div class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Newsletter</div>
                    <h3 class="mt-2 text-base font-semibold tracking-tight">Get weekly dev notes</h3>
                    <p class="mt-1 text-sm text-muted-foreground">
                        One email per week. No spam. Just practical tips about Laravel, Vue, and architecture.
                    </p>

                    <div class="mt-4 flex gap-2">
                        <input type="email" placeholder="you@example.com"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" />
                        <button type="button"
                            class="rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                            Join
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-muted-foreground">
                        (MVP) This is a placeholder UI — we can wire it later.
                    </p>
                </div>
            </aside>
        </section>
    </div>
</template>

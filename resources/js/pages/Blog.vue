<script setup lang="ts">
import BlogLayout from '@modules/core/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    comments: {
        id: number
        body: string
        created_at: string
        user: { id: number; name: string }
    }[]
}>()

const body = ref('');

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


        <section class="border-t pt-8 space-y-6">
            <h2 class="text-lg font-semibold tracking-tight">Comments</h2>

            <!-- Form (solo auth) -->
            <div v-if="($page.props as any).auth?.user" class="rounded-xl border bg-card p-5">
                <form @submit.prevent="$inertia.post(`/posts/${props.post.slug}/comments`, { body: body })"
                    class="space-y-3">
                    <label class="text-sm font-medium">Leave a comment</label>
                    <textarea v-model="body" rows="4"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="Write your comment..." />
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-muted-foreground">Comments are moderated.</p>
                        <button
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

            <div v-else class="rounded-xl border bg-card p-5 text-sm text-muted-foreground">
                <span>You must </span>
                <Link href="/login" class="text-primary hover:underline">log in</Link>
                <span> to comment.</span>
            </div>

            <!-- List -->
            <div class="space-y-4">
                <div v-if="props.comments?.length === 0" class="text-sm text-muted-foreground">
                    No comments yet.
                </div>

                <div v-for="c in props.comments" :key="c.id" class="rounded-xl border bg-card p-5">
                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                        <span class="font-medium text-foreground">{{ c.user.name }}</span>
                        <span>{{ c.created_at }}</span>
                    </div>
                    <p class="mt-2 text-sm text-muted-foreground whitespace-pre-wrap">{{ c.body }}</p>
                </div>
            </div>
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

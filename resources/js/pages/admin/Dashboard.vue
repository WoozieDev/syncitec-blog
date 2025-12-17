<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'

defineOptions({ layout: AdminLayout })

type Stats = {
    posts_total: number
    posts_draft: number
    posts_published: number
    posts_scheduled: number
    comments_pending: number
    comments_approved: number
    users_total: number
    categories_total: number
    tags_total: number
}

type RecentPost = {
    id: number
    title: string
    status: 'draft' | 'published' | 'scheduled'
    published_at: string | null
    category: string | null
    author: string | null
}

type PendingComment = {
    id: number
    body: string
    created_at: string
    user: { name: string; email: string }
    post: { title: string; slug: string }
}

const props = defineProps<{
    title?: string
    stats: Stats
    recentPosts: RecentPost[]
    pendingComments: PendingComment[]
}>()

const statusBadge = (status: RecentPost['status']) => {
    if (status === 'published') return 'default'
    if (status === 'scheduled') return 'secondary'
    return 'outline'
}

const statusLabel = (status: RecentPost['status']) => {
    if (status === 'published') return 'Published'
    if (status === 'scheduled') return 'Scheduled'
    return 'Draft'
}
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Dashboard</h1>
                <p class="text-sm text-muted-foreground">
                    Quick overview of content, moderation, and growth.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <Button as-child>
                    <Link href="/admin/posts/create">New post</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link href="/admin/comments">Moderate comments</Link>
                </Button>
            </div>
        </div>

        <!-- KPI cards -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <Card>
                <CardHeader class="pb-2">
                    <CardTitle class="text-sm text-muted-foreground">Posts</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div class="text-2xl font-semibold">{{ props.stats.posts_total }}</div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="rounded-full border px-2 py-0.5 text-muted-foreground">Draft: {{
                            props.stats.posts_draft }}</span>
                        <span class="rounded-full border px-2 py-0.5 text-muted-foreground">Published: {{
                            props.stats.posts_published }}</span>
                        <span class="rounded-full border px-2 py-0.5 text-muted-foreground">Scheduled: {{
                            props.stats.posts_scheduled }}</span>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="pb-2">
                    <CardTitle class="text-sm text-muted-foreground">Comments</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div class="text-2xl font-semibold">{{ props.stats.comments_pending }}</div>
                    <div class="text-xs text-muted-foreground">
                        Pending moderation (Approved: {{ props.stats.comments_approved }})
                    </div>
                    <div class="pt-1">
                        <Badge variant="secondary" v-if="props.stats.comments_pending > 0">
                            Action needed
                        </Badge>
                        <Badge variant="outline" v-else>
                            All clear
                        </Badge>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="pb-2">
                    <CardTitle class="text-sm text-muted-foreground">Users</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div class="text-2xl font-semibold">{{ props.stats.users_total }}</div>
                    <div class="text-xs text-muted-foreground">Total registered users</div>
                    <div class="pt-1">
                        <Button variant="outline" size="sm" as-child>
                            <Link href="/admin/users">Manage users</Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader class="pb-2">
                    <CardTitle class="text-sm text-muted-foreground">Taxonomy</CardTitle>
                </CardHeader>
                <CardContent class="space-y-2">
                    <div class="text-2xl font-semibold">
                        {{ props.stats.categories_total }} / {{ props.stats.tags_total }}
                    </div>
                    <div class="text-xs text-muted-foreground">Categories / Tags</div>
                    <div class="flex gap-2 pt-1">
                        <Button variant="outline" size="sm" as-child>
                            <Link href="/admin/categories">Categories</Link>
                        </Button>
                        <Button variant="outline" size="sm" as-child>
                            <Link href="/admin/tags">Tags</Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Activity -->
        <div class="grid gap-4 lg:grid-cols-2">
            <!-- Recent posts -->
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-base">Recent posts</CardTitle>
                    <Button variant="outline" size="sm" as-child>
                        <Link href="/admin/posts">View all</Link>
                    </Button>
                </CardHeader>

                <CardContent>
                    <div v-if="props.recentPosts.length === 0" class="text-sm text-muted-foreground">
                        No posts yet.
                    </div>

                    <div v-else class="space-y-3">
                        <div v-for="p in props.recentPosts" :key="p.id"
                            class="flex items-start justify-between gap-3 rounded-lg border bg-muted/10 p-3">
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ p.title }}</div>
                                <div class="mt-1 text-xs text-muted-foreground">
                                    <span v-if="p.category">{{ p.category }}</span>
                                    <span v-if="p.category && p.author"> • </span>
                                    <span v-if="p.author">{{ p.author }}</span>
                                    <span v-if="p.published_at"> • {{ p.published_at }}</span>
                                </div>
                            </div>

                            <Badge :variant="statusBadge(p.status)" class="shrink-0">
                                {{ statusLabel(p.status) }}
                            </Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Pending comments -->
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-base">Pending comments</CardTitle>
                    <Button variant="outline" size="sm" as-child>
                        <Link href="/admin/comments">Moderate</Link>
                    </Button>
                </CardHeader>

                <CardContent>
                    <div v-if="props.pendingComments.length === 0" class="text-sm text-muted-foreground">
                        No pending comments 🎉
                    </div>

                    <div v-else class="space-y-3">
                        <div v-for="c in props.pendingComments" :key="c.id" class="rounded-lg border bg-muted/10 p-3">
                            <div class="flex items-center justify-between text-xs text-muted-foreground">
                                <div class="truncate">
                                    <span class="font-medium text-foreground">{{ c.user.name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ c.user.email }}</span>
                                </div>
                                <span class="shrink-0">{{ c.created_at }}</span>
                            </div>

                            <div class="mt-2 line-clamp-3 whitespace-pre-wrap text-sm text-foreground/90">
                                {{ c.body }}
                            </div>

                            <div class="mt-2 text-xs">
                                <Link :href="`/posts/${c.post.slug}`" class="text-primary hover:underline">
                                    {{ c.post.title }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

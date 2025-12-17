<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const title = computed(() => (page.props as any)?.title ?? 'Blog')
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <header class="border-b bg-background/80 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 md:px-6">
                <Link href="/" class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="text-xl font-bold">S</span>
                    </div>
                    <span class="text-sm font-semibold tracking-tight">Syncitec Blog</span>
                </Link>

                <nav class="flex items-center gap-3 text-sm">
                    <Link href="/" class="text-muted-foreground hover:text-foreground">Home</Link>
                    <Link href="/login" class="text-muted-foreground hover:text-foreground">Login</Link>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 md:px-6">
            <slot />
        </main>

        <footer class="border-t">
            <div class="mx-auto max-w-6xl px-4 py-6 text-xs text-muted-foreground md:px-6">
                © {{ new Date().getFullYear() }} Syncitec Blog
            </div>
        </footer>
    </div>
</template>

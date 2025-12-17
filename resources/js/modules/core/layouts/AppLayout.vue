<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const title = computed(() => (page.props as any)?.title ?? 'Syncitec Blog')
const auth = computed(() => (page.props as any)?.auth)
const user = computed(() => auth.value?.user)
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <!-- Top glow (tech vibe) -->
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-primary/15 to-transparent" />

        <header class="sticky top-0 z-40 border-b bg-background/75 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 md:px-6">
                <Link href="/" class="flex items-center gap-2">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/15 text-primary ring-1 ring-primary/20">
                        <span class="text-base font-bold">S</span>
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold tracking-tight">Syncitec Blog</div>
                        <div class="text-[11px] text-muted-foreground">Tech • Dev • Architecture</div>
                    </div>
                </Link>

                <nav class="flex items-center gap-2">
                    <Link href="/"
                        class="rounded-md px-2 py-1 text-sm text-muted-foreground hover:text-foreground hover:bg-muted">
                        Home
                    </Link>

                    <Link v-if="user" href="/admin"
                        class="rounded-md px-2 py-1 text-sm text-muted-foreground hover:text-foreground hover:bg-muted">
                        Admin
                    </Link>

                    <Link v-if="!user" href="/login"
                        class="rounded-md bg-primary px-3 py-1.5 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                        Login
                    </Link>

                    <Link v-else href="/logout" method="post" as="button"
                        class="rounded-md border px-3 py-1.5 text-sm font-medium text-muted-foreground hover:bg-muted">
                        Logout
                    </Link>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 md:px-6">
            <slot />
        </main>

        <footer class="border-t">
            <div class="mx-auto max-w-6xl px-4 py-8 text-xs text-muted-foreground md:px-6">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <span>© {{ new Date().getFullYear() }} Syncitec Blog</span>
                    <span>Built with Laravel • Inertia • Vue • Tailwind</span>
                </div>
            </div>
        </footer>
    </div>
</template>

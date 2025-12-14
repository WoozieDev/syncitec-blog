<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Sidebar from '@modules/core/components/Sidebar.vue';
import FlashMessage from '@modules/core/components/FlashMessage.vue';
import type { AdminPageProps, AuthUser } from '@modules/core/types';


const page = usePage<AdminPageProps>()

const mobileSidebarOpen = ref(false);

const title = computed(() => page.props.title ?? 'Dashboard')

const user = computed<AuthUser | undefined>(() => page.props.auth?.user)

const toggleMobileSidebar = () => {
    mobileSidebarOpen.value = !mobileSidebarOpen.value
}

</script>

<template>
    <div class="flex min-h-screen bg-background text-foreground">
        <!-- Sidebar desktop -->
        <Sidebar />

        <!-- Sidebar mobile overlay -->
        <transition name="fade">
            <div v-if="mobileSidebarOpen" class="fixed inset-0 z-40 flex md:hidden">
                <div class="relative flex w-64 flex-col bg-sidebar border-r text-sidebar-foreground">
                    <Sidebar />
                </div>

                <div class="flex-1 bg-black/50" @click="mobileSidebarOpen = false" />
            </div>
        </transition>

        <!-- Contenido principal -->
        <div class="flex min-h-screen flex-1 flex-col">
            <!-- Topbar -->
            <header
                class="flex h-16 items-center justify-between border-b bg-background/80 px-4 backdrop-blur-sm md:px-6">
                <div class="flex items-center gap-2">
                    <!-- Botón menú mobile -->
                    <button
                        class="inline-flex items-center justify-center rounded-md border border-border p-2 text-sm text-muted-foreground hover:bg-muted hover:text-foreground md:hidden"
                        @click="toggleMobileSidebar">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex flex-col">
                        <h1 class="text-base font-semibold leading-tight md:text-lg">
                            {{ title }}
                        </h1>
                        <p class="text-xs text-muted-foreground md:text-sm">
                            Admin • Blog management
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                            {{ user?.name?.[0] ?? 'U' }}
                        </div>
                        <div class="hidden flex-col text-right text-xs md:flex">
                            <span class="font-medium">
                                {{ user?.name ?? 'User' }}
                            </span>
                            <span class="text-muted-foreground">
                                {{ user?.email ?? '' }}
                            </span>
                        </div>
                    </div>

                    <Link href="/logout" method="post" as="button"
                        class="hidden rounded-md bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground shadow-sm hover:bg-primary/90 md:inline-flex">
                        Logout
                    </Link>
                </div>
            </header>

            <!-- Contenido -->
            <main class="flex-1 bg-gradient-to-b from-background to-background/95">
                <div class="mx-auto w-full max-w-6xl px-4 py-6 md:px-6 md:py-8">

                    <FlashMessage class="mb-6" />

                    <slot />
                    
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
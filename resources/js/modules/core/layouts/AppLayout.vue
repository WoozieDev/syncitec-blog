<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useTheme } from '@modules/core/composables/useTheme'

// shadcn
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

const { theme, toggleTheme } = useTheme()

const page = usePage()

const title = computed(() => (page.props as any)?.title ?? 'Syncitec Blog')
const auth = computed(() => (page.props as any)?.auth ?? {})
const user = computed(() => auth.value?.user ?? null)
const role = computed(() => auth.value?.role ?? null)
const canAccessAdmin = computed(() => Boolean(auth.value?.canAccessAdmin))
</script>

<template>
  <div class="min-h-screen bg-background text-foreground">
    <!-- Top glow (tech vibe) -->
    <div
      class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-primary/15 to-transparent"
    />

    <header class="sticky top-0 z-40 border-b bg-background/75 backdrop-blur">
      <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 md:px-6">
        <!-- Brand -->
        <Link href="/" class="flex items-center gap-2">
          <div
            class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/15 text-primary ring-1 ring-primary/20"
          >
            <span class="text-base font-bold">S</span>
          </div>

          <div class="leading-tight">
            <div class="text-sm font-semibold tracking-tight">Syncitec Blog</div>
            <div class="text-[11px] text-muted-foreground">Tech • Dev • Architecture</div>
          </div>
        </Link>

        <!-- Nav -->
        <nav class="flex items-center gap-2">
          <Link
            href="/"
            class="rounded-md px-2 py-1 text-sm text-muted-foreground hover:bg-muted hover:text-foreground"
          >
            Home
          </Link>

          <!-- Theme toggle -->
          <button
            type="button"
            @click="toggleTheme"
            class="inline-flex h-9 w-9 items-center justify-center rounded-md border text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
          >
            <span class="sr-only">Toggle theme</span>

            <!-- Sun -->
            <svg
              v-if="theme === 'dark'"
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0-1.414-1.414M7.05 7.05 5.636 5.636M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"
              />
            </svg>

            <!-- Moon -->
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M21 12.79A9 9 0 0 1 11.21 3a7 7 0 1 0 9.79 9.79z"
              />
            </svg>
          </button>

          <!-- Account dropdown -->
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="outline" class="gap-2">
                <span
                  class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary"
                >
                  {{ user?.name?.[0] ?? 'W' }}
                </span>
                <span class="hidden text-sm md:inline">
                  {{ user?.name ?? 'Account' }}
                </span>
              </Button>
            </DropdownMenuTrigger>

            <DropdownMenuContent align="end" class="w-56">
              <!-- Logged in -->
              <template v-if="user">
                <DropdownMenuLabel class="space-y-1">
                  <div class="text-sm font-medium leading-none">{{ user.name }}</div>
                  <div class="text-xs text-muted-foreground">{{ user.email }}</div>

                  <div v-if="role" class="pt-1">
                    <span class="inline-flex rounded-md bg-muted px-2 py-0.5 text-xs text-muted-foreground">
                      {{ role }}
                    </span>
                  </div>
                </DropdownMenuLabel>

                <DropdownMenuSeparator />

                <DropdownMenuItem v-if="canAccessAdmin" as-child>
                  <Link href="/admin" class="w-full">Admin panel</Link>
                </DropdownMenuItem>

                <DropdownMenuItem as-child>
                  <Link href="/" class="w-full">Home</Link>
                </DropdownMenuItem>

                <DropdownMenuSeparator />

                <DropdownMenuItem as-child>
                  <Link href="/logout" method="post" as="button" class="w-full text-left">
                    Logout
                  </Link>
                </DropdownMenuItem>
              </template>

              <!-- Guest -->
              <template v-else>
                <DropdownMenuLabel>Welcome</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <DropdownMenuItem as-child>
                  <Link href="/login" class="w-full">Login</Link>
                </DropdownMenuItem>

                <DropdownMenuItem as-child>
                  <Link href="/register" class="w-full">Register</Link>
                </DropdownMenuItem>
              </template>
            </DropdownMenuContent>
          </DropdownMenu>
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

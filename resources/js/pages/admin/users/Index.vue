<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout,
})

// más adelante tipamos bien esto con un tipo Paginated<User>
defineProps<{
    title?: string
    users: any
}>()
</script>

<template>
    <div class="space-y-4">
        <header class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold tracking-tight">
                    Users
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Gestión de usuarios del sistema (admin, editor, reader).
                </p>
            </div>
        </header>

        <!-- Placeholder: luego aquí irá el componente UsersTable del módulo users -->
        <div class="rounded-xl border bg-card p-4 text-sm text-muted-foreground">
            Users module coming soon…
        </div>
    </div>
</template>

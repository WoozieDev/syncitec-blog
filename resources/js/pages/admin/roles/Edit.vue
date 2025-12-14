<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    title?: string
    role: {
        id: number
        name: string
        permissions: string[]
    }
    permissions: string[]
}>()

const form = useForm({
    permissions: props.role.permissions ?? [],
})

const submit = () => {
    form.put(`/admin/roles/${props.role.id}`)
}
</script>

<template>
    <div class="space-y-6">
        <header>
            <h2 class="text-lg font-semibold tracking-tight capitalize">
                Edit role: {{ props.role.name }}
            </h2>
            <p class="mt-1 text-sm text-muted-foreground">
                Assign permissions to this role.
            </p>
        </header>

        <div class="rounded-xl border bg-card p-6">
            <form class="space-y-6" @submit.prevent="submit">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium">Permissions</h3>
                        <button type="button" class="text-xs text-muted-foreground hover:underline"
                            @click="form.permissions = []">
                            Clear
                        </button>
                    </div>

                    <div class="grid gap-2 md:grid-cols-3">
                        <label v-for="permission in props.permissions" :key="permission"
                            class="flex items-center gap-2 rounded-md border bg-card px-3 py-2 text-sm">
                            <input v-model="form.permissions" type="checkbox" :value="permission" class="h-4 w-4" />
                            <span>{{ permission }}</span>
                        </label>
                    </div>

                    <p v-if="form.errors.permissions" class="text-xs text-destructive">
                        {{ form.errors.permissions }}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <Link href="/admin/roles"
                        class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted">
                        Cancel
                    </Link>

                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                        :disabled="form.processing">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

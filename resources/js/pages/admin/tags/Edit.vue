<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    tag: {
        id: number
        name: string
        slug: string
        deleted_at: string | null
    }
}>()

const form = useForm({
    name: props.tag.name,
    slug: props.tag.slug,
})

const submit = () => {
    form.put(`/admin/tags/${props.tag.id}`)
}
</script>

<template>
    <div class="space-y-6">
        <header>
            <h2 class="text-lg font-semibold tracking-tight">Edit tag</h2>
            <p class="mt-1 text-sm text-muted-foreground">
                Update tag fields. Slug will be normalized automatically.
            </p>
        </header>

        <div class="rounded-xl border bg-card p-6">
            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1">
                        <label class="text-sm font-medium">Name</label>
                        <input v-model="form.name" type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            @input="form.clearErrors('name')" />
                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium">Slug</label>
                        <input v-model="form.slug" type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            @input="form.clearErrors('slug')" />
                        <p v-if="form.errors.slug" class="text-xs text-destructive">{{ form.errors.slug }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <Link href="/admin/tags"
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

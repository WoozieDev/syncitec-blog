<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue'
import FlashMessage from '@modules/core/components/FlashMessage.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

defineOptions({ layout: AdminLayout })

type Permission = {
    id: number
    name: string // e.g. posts_view
    display_name: string | null
    description: string | null
}

const props = defineProps<{
    title?: string
    role: { id: number; name: string }
    permissions: Permission[]
    assignedPermissionIds: number[]
}>()

const form = useForm<{
    permission_ids: number[]
}>({
    permission_ids: props.assignedPermissionIds.map((id) => Number(id)),
})

/**
 * shadcn Checkbox can emit: boolean | "indeterminate"
 * Normalize it to boolean.
 */
const toBool = (v: boolean | 'indeterminate') => v === true

const selected = computed(() => new Set<number>(form.permission_ids))

const grouped = computed(() => {
    const map: Record<string, Permission[]> = {}

    for (const p of props.permissions) {
        // ✅ snake_case: module is prefix before first "_"
        const module = p.name.split('_')[0]
        map[module] ??= []
        map[module].push(p)
    }

    return Object.entries(map)
        .map(([module, items]) => ({
            module,
            title: module.charAt(0).toUpperCase() + module.slice(1),
            items: items.sort((a, b) => a.name.localeCompare(b.name)),
        }))
        .sort((a, b) => a.module.localeCompare(b.module))
})

const isChecked = (id: number) => selected.value.has(id)

const setOne = (id: number, checked: boolean) => {
    if (checked) {
        const nid = Number(id)
        if (!isChecked(nid)) form.permission_ids = [...form.permission_ids, nid]
        return
    }
    form.permission_ids = form.permission_ids.filter((x) => x !== id)
}

const moduleIds = (module: string) =>
    grouped.value.find((g) => g.module === module)?.items.map((i) => i.id) ?? []

const moduleAllChecked = (module: string) => {
    const ids = moduleIds(module)
    return ids.length > 0 && ids.every((id) => isChecked(id))
}

const moduleSomeChecked = (module: string) => {
    const ids = moduleIds(module)
    return ids.some((id) => isChecked(id)) && !moduleAllChecked(module)
}

const setModule = (module: string, checked: boolean) => {
    const ids = moduleIds(module)

    if (checked) {
        const next = new Set(form.permission_ids)
        ids.forEach((id) => next.add(id))
        form.permission_ids = Array.from(next)
        return
    }

    form.permission_ids = form.permission_ids.filter((id) => !ids.includes(id))
}

const allChecked = computed(() => props.permissions.length > 0 && props.permissions.every((p) => isChecked(p.id)))
const someChecked = computed(() => props.permissions.some((p) => isChecked(p.id)) && !allChecked.value)

const setAll = (checked: boolean) => {
    form.permission_ids = checked ? props.permissions.map((p) => Number(p.id)) : []
}

const submit = () => {
    form.put(`/admin/roles/${props.role.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="space-y-6">

        <Head :title="`Edit role: ${props.role.name}`" />

        <FlashMessage />

        <!-- Header -->
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">
                    Edit role: <span class="text-primary">{{ props.role.name }}</span>
                </h1>
                <p class="text-sm text-muted-foreground">
                    Assign permissions grouped by module.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <Button variant="outline" as-child>
                    <Link href="/admin/roles">Back</Link>
                </Button>

                <Button :disabled="form.processing" @click="submit">
                    Save changes
                </Button>
            </div>
        </div>

        <!-- Global select -->
        <Card>
            <CardHeader class="flex flex-row items-center justify-between">
                <CardTitle class="text-base">All permissions</CardTitle>

                <div class="flex items-center gap-2">
                    <Checkbox :checked="allChecked" @update:checked="(v) => setAll(toBool(v))" />
                    <span class="text-sm">
                        Select all
                        <span v-if="someChecked" class="text-muted-foreground">(partial)</span>
                    </span>
                </div>
            </CardHeader>

            <CardContent class="text-sm text-muted-foreground">
                Selected:
                <span class="font-medium text-foreground">{{ form.permission_ids.length }}</span>
            </CardContent>
        </Card>

        <!-- Groups -->
        <div class="space-y-5">
            <Card v-for="group in grouped" :key="group.module">
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-base">{{ group.title }}</CardTitle>

                    <div class="flex items-center gap-2">
                        <Checkbox :checked="moduleAllChecked(group.module)"
                            @update:checked="(v) => setModule(group.module, toBool(v))" />
                        <span class="text-sm">
                            Select all
                            <span v-if="moduleSomeChecked(group.module)" class="text-muted-foreground">(partial)</span>
                        </span>
                    </div>
                </CardHeader>

                <CardContent>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label v-for="p in group.items" :key="p.id"
                            class="flex items-start gap-3 rounded-lg border bg-muted/10 p-3 hover:bg-muted/20">
                            <Checkbox :checked="isChecked(p.id)" @update:checked="(v) => setOne(p.id, toBool(v))" />

                            <div class="min-w-0">
                                <div class="text-sm font-medium">
                                    {{ p.display_name ?? p.name }}
                                </div>

                                <div v-if="p.description" class="mt-0.5 text-xs text-muted-foreground">
                                    {{ p.description }}
                                </div>

                                <div class="mt-1 text-xs text-muted-foreground">
                                    {{ p.name }}
                                </div>
                            </div>
                        </label>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Footer actions -->
        <div class="flex justify-end gap-2">
            <Button variant="outline" as-child>
                <Link href="/admin/roles">Cancel</Link>
            </Button>

            <Button :disabled="form.processing" @click="submit">
                Save changes
            </Button>
        </div>
    </div>
</template>

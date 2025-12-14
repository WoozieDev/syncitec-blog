<script setup lang="ts">
import AdminLayout from '@modules/core/layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


defineOptions({ layout: AdminLayout })

const props = defineProps<{
  title?: string
  roleOptions: string[]
}>()

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [] as string[],
})

const submit = () => {
  form.post('/admin/users', {
    onSuccess: () => {
      form.reset('password', 'password_confirmation')
    },
  })
}
</script>

<template>
  <div class="space-y-6">
    <header>
      <h2 class="text-lg font-semibold tracking-tight">Create user</h2>
      <p class="mt-1 text-sm text-muted-foreground">
        Create a new user and assign roles.
      </p>
    </header>

    <div class="rounded-xl border bg-card p-6">
      <form class="space-y-6" @submit.prevent="submit">
        <div class="grid gap-4 md:grid-cols-2">
          <!-- Name -->
          <div class="space-y-1">
            <label class="text-sm font-medium">Name</label>
            <input v-model="form.name" type="text"
              class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="John Doe" @input="form.clearErrors('name')" />
            <p v-if="form.errors.name" class="text-xs text-destructive">
              {{ form.errors.name }}
            </p>
          </div>

          <!-- Email -->
          <div class="space-y-1">
            <label class="text-sm font-medium">Email</label>
            <input v-model="form.email" type="email"
              class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="john@example.com" @input="form.clearErrors('email')" />
            <p v-if="form.errors.email" class="text-xs text-destructive">
              {{ form.errors.email }}
            </p>
          </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <!-- Password -->
          <div class="space-y-1">
            <label class="text-sm font-medium">Password</label>
            <input v-model="form.password" type="password"
              class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="********" @input="form.clearErrors('password')" />
            <p v-if="form.errors.password" class="text-xs text-destructive">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Password confirmation -->
          <div class="space-y-1">
            <label class="text-sm font-medium">Confirm password</label>
            <input v-model="form.password_confirmation" type="password"
              class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
              placeholder="********" @input="form.clearErrors('password_confirmation')" />
            <p v-if="form.errors.password_confirmation" class="text-xs text-destructive">
              {{ form.errors.password_confirmation }}
            </p>
          </div>
        </div>

        <!-- Roles -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="text-sm font-medium">Roles</label>
            <button type="button" class="text-xs text-muted-foreground hover:underline" @click="form.roles = []">
              Clear
            </button>
          </div>

          <div class="grid gap-2 md:grid-cols-3">
            <label v-for="role in props.roleOptions" :key="role"
              class="flex items-center gap-2 rounded-md border bg-card px-3 py-2 text-sm">
              <input v-model="form.roles" type="checkbox" :value="role" class="h-4 w-4"
                @change="form.clearErrors('roles')" />
              <span class="capitalize">{{ role }}</span>
            </label>
          </div>

          <p v-if="form.errors.roles" class="text-xs text-destructive">
            {{ form.errors.roles }}
          </p>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-2">
          <Link href="/admin/users"
            class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium text-muted-foreground hover:bg-muted">
            Cancel
          </Link>

          <button type="submit"
            class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
            :disabled="form.processing">
            Create user
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

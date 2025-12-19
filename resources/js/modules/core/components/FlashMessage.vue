<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { X } from 'lucide-vue-next'

const page = usePage()
const visible = ref(true)

const flash = computed(() => page.props.flash || {})

const message = computed(() =>
    flash.value.success ||
    flash.value.error ||
    flash.value.warning ||
    null
)

const type = computed<'success' | 'error' | 'warning' | null>(() => {
    if (flash.value.success) return 'success'
    if (flash.value.error) return 'error'
    if (flash.value.warning) return 'warning'
    return null
})

watch(message, () => {
    visible.value = true
})

watch(
    message,
    () => {
        if (!message.value) return
        setTimeout(() => (visible.value = false), 6000)
    },
    { immediate: true }
)
</script>

<template>
    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
        enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="message && visible" class="relative rounded-lg border px-4 py-3 text-sm" :class="{
            // ✅ SUCCESS (green)
            'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200':
                type === 'success',

            // ❌ ERROR (red)
            'border-red-200 bg-red-50 text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200':
                type === 'error',

            // ⚠️ WARNING (orange)
            'border-orange-200 bg-orange-50 text-orange-800 dark:border-orange-900/40 dark:bg-orange-900/20 dark:text-orange-200':
                type === 'warning',
        }">
            <!-- Message -->
            <div class="pr-8">
                {{ message }}
            </div>

            <!-- Close -->
            <button type="button" class="absolute right-2 top-2 rounded-md p-1 opacity-70 transition hover:opacity-100 cursor-pointer"
                @click="visible = false">
                <X class="h-4 w-4" />
            </button>
        </div>
    </transition>
</template>

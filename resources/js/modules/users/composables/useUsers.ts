import { router } from '@inertiajs/vue3';
import { ref } from 'vue';


interface UserFilters {
    search?: string | null;
    role?: string | null;
    trashed?: string | null;
}

export default function useUsers() {
    const processing = ref(false);

    const index = (filters: UserFilters = {}) => {
        processing.value = true;

        router.get('/admin/users', filters, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onFinish: () => (processing.value = false),
        });
    }

    const destroyUser = (userId: number) => {
        if (!confirm('Are you sure you want to delete this user?')) return;

        processing.value = true;

        router.delete(`/admin/users/${userId}`, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => (processing.value = false),
        });
    }

    const restoreUser = (userId: number) => {
        processing.value = true

        router.patch(`/admin/users/${userId}/restore`, {}, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => (processing.value = false),
        })
    }

    return {
        processing,
        index,
        destroyUser,
        restoreUser,
    }
}

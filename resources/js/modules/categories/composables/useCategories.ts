import { router } from '@inertiajs/vue3';
import type { CategoryFilters } from '@modules/categories/types/category';
import { ref } from 'vue';

export default function useCategories() {
    const processing = ref(false);

    const list = (filters: CategoryFilters = {}) => {
        processing.value = true;

        router.get(
            '/admin/categories',
            {
                search: filters.search ?? null,
                trashed: filters.trashed ?? null,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => (processing.value = false),
            },
        );
    };

    const destroyCategory = (id: number) => {
        if (!confirm('Are you sure you want to delete this category?')) return;

        processing.value = true;

        router.delete(`/admin/categories/${id}`, {
            preserveScroll: true,
            onFinish: () => (processing.value = false),
        });
    };

    const restoreCategory = (id: number) => {
        processing.value = true;

        router.patch(
            `/admin/categories/${id}/restore`,
            {},
            {
                preserveScroll: true,
                onFinish: () => (processing.value = false),
            },
        );
    };

    return {
        processing,
        list,
        destroyCategory,
        restoreCategory,
    };
}

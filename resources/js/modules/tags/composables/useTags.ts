import { router } from '@inertiajs/vue3';
import type { TagFilters } from '@modules/tags/types/tag';
import { ref } from 'vue';

export default function useTags() {
    const processing = ref(false);

    const list = (filters: TagFilters = {}) => {
        processing.value = true;

        router.get(
            '/admin/tags',
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

    const destroyTag = (id: number) => {
        if (!confirm('Are you sure you want to delete this tag?')) return;

        processing.value = true;

        router.delete(`/admin/tags/${id}`, {
            preserveScroll: true,
            onFinish: () => (processing.value = false),
        });
    };

    const restoreTag = (id: number) => {
        processing.value = true;

        router.patch(
            `/admin/tags/${id}/restore`,
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
        destroyTag,
        restoreTag,
    };
}

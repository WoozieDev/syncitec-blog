import { router } from '@inertiajs/vue3';
import type { PostFilters } from '@modules/posts/types/post';
import { ref } from 'vue';

export default function usePosts() {
    const processing = ref(false);

    const list = (filters: PostFilters = {}) => {
        processing.value = true;

        router.get(
            '/admin/posts',
            {
                search: filters.search ?? null,
                status: filters.status ?? null,
                category_id: filters.category_id ?? null,
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

    const destroyPost = (id: number) => {
        if (!confirm('Are you sure you want to delete this post?')) return;

        processing.value = true;

        router.delete(`/admin/posts/${id}`, {
            preserveScroll: true,
            onFinish: () => (processing.value = false),
        });
    };

    const restorePost = (id: number) => {
        processing.value = true;

        router.patch(
            `/admin/posts/${id}/restore`,
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
        destroyPost,
        restorePost,
    };
}

import { router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

type Filters = { status: string; q: string };

export function useCommentFilters() {
    const page = usePage();
    const initial = (page.props as any).filters as Partial<Filters> | undefined;

    const status = ref(initial?.status ?? 'pending');
    const q = ref(initial?.q ?? '');

    let timer: number | undefined;

    watch([status, q], () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => {
            router.get(
                '/admin/comments',
                { status: status.value, q: q.value },
                { preserveState: true, replace: true, preserveScroll: true },
            );
        }, 350);
    });

    const reset = () => {
        status.value = 'pending';
        q.value = '';
    };

    return { status, q, reset };
}

export type CategoryFilters = {
    search?: string | null;
    trashed?: '' | 'with' | 'only' | null;
};

export type CategoryListItem = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    deleted_at: string | null;
};

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type Paginated<T> = {
    data: T[];
    links: PaginationLink[];
};

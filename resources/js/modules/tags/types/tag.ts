export type TagFilters = {
    search?: string | null;
    trashed?: '' | 'with' | 'only' | null;
};

export type TagListItem = {
    id: number;
    name: string;
    slug: string;
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

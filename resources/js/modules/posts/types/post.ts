export type PostStatus = 'draft' | 'published' | 'scheduled';

export type PostFilters = {
    search?: string | null;
    status?: '' | PostStatus | null;
    category_id?: number | null;
    trashed?: '' | 'with' | 'only' | null;
};

export type SelectOption = {
    id: number;
    name: string;
};

export type TagOption = {
    id: number;
    name: string;
};

export type PostListItem = {
    id: number;
    title: string;
    slug: string;
    status: PostStatus;
    published_at: string | null;
    deleted_at: string | null;
    category: { id: number; name: string };
    author: { id: number; name: string };
};

export type PostFormData = {
    category_id: number | null;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    status: PostStatus;
    published_at: string | null;
    meta_title: string;
    meta_description: string;
    og_title: string;
    og_description: string;
    og_image: string;
    tag_ids: number[];
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

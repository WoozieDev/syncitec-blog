<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { NavItem } from '@modules/core/types';

const page = usePage();

const navItems = computed<NavItem[]>(() => [
	{
		label: 'Dashboard',
		href: '/admin',
		pattern: /^\/admin\/?$/,
	},
	{
		label: 'Posts',
		href: '/admin/posts',
		pattern: /^\/admin\/posts/,
	},
	{
		label: 'Categories',
		href: '/admin/categories',
		pattern: /^\/admin\/categories/,
	},
	{
		label: 'Tags',
		href: '/admin/tags',
		pattern: /^\/admin\/tags/,
	},
	{
		label: 'Comments',
		href: '/admin/comments',
		pattern: /^\/admin\/comments/,
	},
	{
		label: 'Users',
		href: '/admin/users',
		pattern: /^\/admin\/users/,
	},
	{
		label: 'Roles',
		href: '/admin/roles',
		pattern: /^\/admin\/roles/,
	},
	{
		label: 'Permissions',
		href: '/admin/permissions',
		pattern: /^\/admin\/permissions/,
	},
]);

const currentUrl = computed(() => page.url as string);
const isActive = (item: NavItem): boolean => item.pattern.test(currentUrl.value);

</script>

<template>
	<aside class="hidden md:flex h-full w-64 flex-col border-r bg-sidebar text-sidebar-foreground">
		<!-- Logo / Brand -->
		<div class="flex h-16 items-center gap-2 border-b px-4">
			<div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
				<span class="text-xl font-bold">S</span>
			</div>
			<div class="flex flex-col">
				<span class="text-sm font-semibold tracking-tight">
					Syncitec Blog
				</span>
				<span class="text-xs text-muted-foreground">
					Admin panel
				</span>
			</div>
		</div>

		<!-- Navegación -->
		<nav class="flex-1 space-y-1 overflow-y-auto px-2 py-3">
			<h2 class="px-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
				Management
			</h2>

			<div class="mt-2 space-y-1">
				<Link v-for="item in navItems" :key="item.href" :href="item.href" :class="[
					'group flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-medium transition-colors',
					isActive(item)
						? 'bg-primary/10 text-primary'
						: 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground'
				]">
					<!-- Placeholder de icono (por ahora la inicial) -->
					<span class="flex h-7 w-7 items-center justify-center rounded-md bg-muted text-xs font-semibold">
						{{ item.label[0] }}
					</span>
					<span>{{ item.label }}</span>
				</Link>
			</div>
		</nav>

		<!-- Footer -->
		<div class="border-t px-4 py-3 text-xs text-muted-foreground">
			<p>Logged in as</p>
			<p class="font-medium truncate">
				{{ (page.props as any).auth?.user?.name }}
			</p>
		</div>
	</aside>
</template>
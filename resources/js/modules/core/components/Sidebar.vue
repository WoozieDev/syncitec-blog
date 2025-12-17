<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { NavItem } from '@modules/core/types'

// Icons (lucide)
import {
	LayoutDashboard,
	FileText,
	Folder,
	Tag,
	MessageSquare,
	Users,
	Shield,
	KeyRound,
	MessageCircleDashedIcon
} from 'lucide-vue-next'

const props = withDefaults(defineProps<{ variant?: 'desktop' | 'mobile' }>(), {
	variant: 'desktop',
})

const emit = defineEmits<{ (e: 'navigate'): void }>()

const page = usePage()

const currentUrl = computed(() => page.url as string)

const can = computed<Record<string, boolean>>(
	() => ((page.props as any)?.auth?.can ?? {}) as Record<string, boolean>,
)

const allNavItems = computed<NavItem[]>(() => [
	{
		label: 'Dashboard',
		href: '/admin',
		pattern: /^\/admin\/?$/,
		icon: LayoutDashboard,
		permission: 'dashboard.view',
	},
	{
		label: 'Posts',
		href: '/admin/posts',
		pattern: /^\/admin\/posts/,
		icon: FileText,
		permission: 'posts.view',
	},
	{
		label: 'Categories',
		href: '/admin/categories',
		pattern: /^\/admin\/categories/,
		icon: Folder,
		permission: 'categories.view',
	},
	{
		label: 'Tags',
		href: '/admin/tags',
		pattern: /^\/admin\/tags/,
		icon: Tag,
		permission: 'tags.view',
	},
	{
		label: 'Comments',
		href: '/admin/comments',
		pattern: /^\/admin\/comments/,
		icon: MessageSquare,
		permission: 'comments.view',
	},
	{
		label: 'Users',
		href: '/admin/users',
		pattern: /^\/admin\/users/,
		icon: Users,
		permission: 'users.view',
	},
	{
		label: 'Roles',
		href: '/admin/roles',
		pattern: /^\/admin\/roles/,
		icon: Shield,
		permission: 'roles.view',
	},
	{
		label: 'Permissions',
		href: '/admin/permissions',
		pattern: /^\/admin\/permissions/,
		icon: KeyRound,
		permission: 'permissions.view',
	},
])

const navItems = computed(() =>
	allNavItems.value.filter((item) => {
		if (!item.permission) return true
		return !!can.value[item.permission]
	}),
)

const isActive = (item: NavItem): boolean => item.pattern.test(currentUrl.value)

const onNavigate = () => emit('navigate')
</script>

<template>
	<aside :class="[
		'h-screen w-64 flex-col border-r bg-sidebar text-sidebar-foreground',
		props.variant === 'desktop' ? 'hidden md:flex sticky top-0' : 'flex',
	]">
		<!-- Logo / Brand -->
		<div class="flex h-16 items-center gap-2 border-b px-4">
			<div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
				<span class="text-xl font-bold">S</span>
			</div>
			<div class="flex flex-col">
				<span class="text-sm font-semibold tracking-tight">Syncitec Blog</span>
				<span class="text-xs text-muted-foreground">Admin panel</span>
			</div>
		</div>

		<!-- Navegación -->
		<nav class="flex-1 overflow-y-auto px-2 py-3">
			<h2 class="px-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
				Management
			</h2>

			<div class="mt-2 space-y-1">
				<Link v-for="item in navItems" :key="item.href" :href="item.href" @click="onNavigate" :class="[
					'group flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-medium transition-colors',
					isActive(item)
						? 'bg-primary/10 text-primary'
						: 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground',
				]">
					<!-- Icono -->
					<span
						class="flex h-8 w-8 items-center justify-center rounded-md bg-muted/60 text-muted-foreground group-hover:text-foreground"
						:class="isActive(item) ? 'bg-primary/10 text-primary' : ''">
						<component :is="item.icon" class="h-4 w-4" />
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

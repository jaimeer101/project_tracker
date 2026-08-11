<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';


const page = usePage();
const user = computed(() => page.props.auth?.user);
const navItems = [
	{ label: 'Dashboard', icon: 'dashboard', path: '/', route: 'dashboard' },
	{ label: 'Project', icon: 'folder_open', path: '/projects', route: 'projects.index', routePattern: 'projects.*' },
];
const isActive = (item) => {
	if (item.routePattern) {
		return route().current(item.routePattern);
	}
	return route().current(item.route);
};
</script>
<template>
	<aside
		class="w-[280px] h-screen fixed left-0 top-0 bg-surface-container-lowest border-r border-outline-variant flex flex-col py-8">
		<div class="px-6 mb-10 flex items-center gap-3">
			<div class="w-8 h-8 bg-primary rounded flex items-center justify-center text-white">
				<i class="material-symbols-outlined text-sm">business</i>
			</div>
			<div>
				<h1 class="font-bold text-lg text-primary leading-tight">Project Tracker</h1>
				<p class="text-xs text-secondary">Track your project</p>
			</div>
		</div>

		<nav class="flex-1 px-4 space-y-1">
			<Link v-for="item in navItems" :key="item.label" :href="route(item.route)"
				class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors"
				:class="isActive(item) ? 'bg-primary/10 text-primary border-l-4 border-primary font-medium' : 'text-secondary hover:bg-surface-container-low'">
				<i class="material-symbols-outlined">{{ item.icon }}</i>
				<span>{{ item.label }}</span>
			</Link>
		</nav>

		<div class="px-4 mt-auto pt-6 border-t border-outline-variant">
			<div class="flex items-center gap-3 px-4 py-2">
				<div class="w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center">
					<i class="material-symbols-outlined text-secondary">person</i>
				</div>
				<div class="overflow-hidden">
					<p class="text-sm font-medium truncate">Admin User</p>
				</div>
			</div>
		</div>
	</aside>
</template>

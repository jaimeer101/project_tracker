<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
	title: { type: String, default: 'Dashboard' }
});
</script>
<template>
	<header
		class="h-16 fixed top-0 right-0 left-[280px] bg-surface-container-lowest border-b border-outline-variant z-10 flex items-center justify-between px-8">
		<h2 class="text-xl font-bold text-on-surface">{{ title }}</h2>

		<div class="flex items-center gap-6">

			<div class="flex items-center gap-4 text-secondary">
				<Link :href="route('logout')" method="post" as="button"
					class="flex w-full items-center gap-3 px-4 py-2 text-sm text-error hover:bg-error/10 cursor-pointer">
					<span class="material-symbols-outlined text-[20px]">logout</span>
					Logout
				</Link>
			</div>
		</div>
	</header>
</template>

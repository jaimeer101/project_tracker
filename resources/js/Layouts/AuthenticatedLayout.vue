<script setup>

import Sidebar from '@/Components/navigation/Sidebar.vue';
import TopNav from '@/Components/navigation/TopNav.vue';
import { computed } from 'vue';
defineProps({
    title: {
        type: String,
        default: 'Project Management',
    },
});
</script>
<template>
    <div class="min-h-screen bg-surface">
        <Sidebar />
        <div class="ml-[280px]">
            <TopNav :title="title" />
            <main class="p-8 mt-16">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

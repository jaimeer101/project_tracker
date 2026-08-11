<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex justify-between items-end mb-8">
            <h3>Welcome</h3>
        </div>
    </AuthenticatedLayout>
</template>

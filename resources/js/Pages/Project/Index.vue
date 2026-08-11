<script setup>
import ProjectTable from '@/Components/project/ProjectTable.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, reactive, watch } from 'vue';

const page = usePage();
const statuses = page.props.enums?.statuses || [];
const priorities = page.props.enums?.priorities || [];
const projects = ref([]);
const pagination = ref({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 });
const isLoading = ref(true);
const errorMessage = ref(null);
const successMessage = ref(null);

// Reactive State for Search, Filters, Sorting & Pagination
const filters = reactive({
    search: '',
    status: '',
    priority: '',
    sort_by: 'id',
    sort_order: 'desc',
    page: 1,
});

// Debounce timer for search input
let searchTimeout = null;

const fetchProjects = async () => {
    isLoading.value = true;
    errorMessage.value = null;

    try {
        const response = await axios.get(route('projects.api.index'), {
            params: {
                search: filters.search,
                status: filters.status,
                priority: filters.priority,
                sort_by: filters.sort_by,
                sort_order: filters.sort_order,
                page: filters.page,
            }
        });

        projects.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total,
            from: response.data.from,
            to: response.data.to,
        };
    } catch (error) {
        console.error('Failed to load projects:', error);
        errorMessage.value = 'Failed to load projects. Please try again.';
    } finally {
        isLoading.value = false;
    }
};

// Handle Search input with 300ms debounce
const onSearchInput = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filters.page = 1; // Reset to page 1 when searching
        fetchProjects();
    }, 300);
};

// Handle Column Sort
const handleSort = ({ sortBy, sortOrder }) => {
    filters.sort_by = sortBy;
    filters.sort_order = sortOrder;
    fetchProjects();
};

// Handle Page Change
const handlePageChange = (newPage) => {
    if (newPage >= 1 && newPage <= pagination.value.last_page) {
        filters.page = newPage;
        fetchProjects();
    }
};

// Reset all filters
const resetFilters = () => {
    filters.search = '';
    filters.status = '';
    filters.priority = '';
    filters.sort_by = 'id';
    filters.sort_order = 'desc';
    filters.page = 1;
    fetchProjects();
};

// Auto-fetch when Status or Priority dropdowns change
watch([() => filters.status, () => filters.priority], () => {
    filters.page = 1;
    fetchProjects();
});

const handleProjectDeleted = (msg) => {
    successMessage.value = msg || 'Project deleted successfully.';
    fetchProjects();
};

const handleProjectDeleteError = (msg) => {
    errorMessage.value = msg || 'Failed to delete project.';
};

onMounted(() => {
    fetchProjects();
});
</script>

<template>

    <Head title="Projects" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-on-surface mb-2">Projects</h2>
                <p class="text-secondary">Manage and track all enterprise initiatives.</p>
            </div>
            <Link :href="route('projects.create')"
                class="bg-primary text-white px-6 py-2.5 rounded font-medium flex items-center gap-2 hover:bg-primary/90 transition-colors">
                <i class="material-symbols-outlined">add</i>
                New Project
            </Link>
        </div>

        <!-- Success Alert -->
        <div v-if="successMessage"
            class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i class="material-symbols-outlined text-xl text-emerald-600">check_circle</i>
                <span class="font-medium text-sm">{{ successMessage }}</span>
            </div>
            <button @click="successMessage = null" class="hover:opacity-75 cursor-pointer p-1 rounded">
                <i class="material-symbols-outlined text-lg">close</i>
            </button>
        </div>

        <!-- Error Alert -->
        <div v-if="errorMessage"
            class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i class="material-symbols-outlined text-xl text-red-600">error</i>
                <span class="font-medium text-sm">{{ errorMessage }}</span>
            </div>
            <button @click="errorMessage = null" class="hover:opacity-75 cursor-pointer p-1 rounded">
                <i class="material-symbols-outlined text-lg">close</i>
            </button>
        </div>

        <!-- Search and Filter Bar -->
        <div
            class="mb-6 p-4 bg-surface-container-lowest rounded-xl border border-outline-variant flex flex-wrap gap-4 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto flex-1">
                <!-- Search Input -->
                <div class="relative w-full sm:w-72">
                    <i
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-lg">search</i>
                    <input v-model="filters.search" @input="onSearchInput" type="text"
                        placeholder="Search project or client..."
                        class="w-full pl-9 pr-4 py-2 border border-outline-variant rounded-lg text-sm focus:outline-none focus:border-primary bg-surface-container-low text-on-surface" />
                </div>

                <!-- Status Filter -->
                <select v-model="filters.status"
                    class="py-2 pl-3 pr-8 min-w-[140px] border border-outline-variant rounded-lg text-sm focus:outline-none focus:border-primary bg-surface-container-low text-on-surface cursor-pointer">
                    <option value="">All Statuses</option>
                    <option v-for="status in statuses" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>

                <!-- Priority Filter -->
                <select v-model="filters.priority"
                    class="py-2 pl-3 pr-8 min-w-[140px] border border-outline-variant rounded-lg text-sm focus:outline-none focus:border-primary bg-surface-container-low text-on-surface cursor-pointer">
                    <option value="">All Priorities</option>
                    <option v-for="priority in priorities" :key="priority" :value="priority">
                        {{ priority }}
                    </option>
                </select>

                <!-- Reset Button -->
                <button v-if="filters.search || filters.status || filters.priority" @click="resetFilters"
                    class="text-xs text-secondary hover:text-error transition-colors flex items-center gap-1">
                    <i class="material-symbols-outlined text-sm">restart_alt</i> Reset
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
            <div v-if="isLoading"
                class="p-12 text-center text-secondary flex flex-col items-center justify-center gap-2">
                <i class="material-symbols-outlined animate-spin text-3xl text-primary">progress_activity</i>
                <p>Loading projects...</p>
            </div>

            <ProjectTable v-else :projects="projects" :pagination="pagination" :sort-by="filters.sort_by"
                :sort-order="filters.sort_order" @sort="handleSort" @page-change="handlePageChange"
                @deleted="handleProjectDeleted" @delete-error="handleProjectDeleteError" />
        </div>
    </AuthenticatedLayout>
</template>
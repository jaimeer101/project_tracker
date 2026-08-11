<script setup>
import { Link, router } from '@inertiajs/vue3';
import DeleteConfirmModal from '../modal/DeleteConfirmModal.vue';
import { ref } from 'vue';

const props = defineProps({
    projects: {
        type: Array,
        required: true
    },
    pagination: {
        type: Object,
        default: () => ({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    },
    sortBy: {
        type: String,
        default: 'id'
    },
    sortOrder: {
        type: String,
        default: 'desc'
    }
});
// console.log(projects)
const emit = defineEmits(['edit', 'delete', 'deleted', 'delete-error', 'sort', 'page-change']);

const isModalOpen = ref(false);
const selectedProject = ref(null);

const handleSort = (column) => {
    let newOrder = 'asc';
    if (props.sortBy === column && props.sortOrder === 'asc') {
        newOrder = 'desc';
    }
    emit('sort', { sortBy: column, sortOrder: newOrder });
};

const openDeleteModal = (project) => {
    // Maps project_name to name so DeleteProjectModal renders the title properly
    selectedProject.value = {
        id: project.id,
        name: project.project_name || project.name
    };
    isModalOpen.value = true;
};

const closeDeleteModal = () => {
    isModalOpen.value = false;
    selectedProject.value = null;
};

const handleConfirmDelete = (id) => {
    if (!id) return;

    router.delete(route('projects.api.delete', id), {
        onSuccess: () => {
            closeDeleteModal();
            emit('deleted'); // Notify parent if needed
        },
        onError: () => {
            closeDeleteModal();
            emit('delete-error', 'An error occurred while deleting the project.');
        }
    });
};
const getStatusClass = (status) => {
    const map = {
        'Planning': 'bg-surface-container-low text-secondary',
        'In Progress': 'bg-primary/10 text-primary',
        'On Hold': 'bg-error/10 text-error',
        'Completed': 'bg-green-100 text-green-700'
    };
    return map[status] || 'bg-gray-100 text-gray-600';
};

const getPriorityClass = (priority) => {
    const map = {
        'High': 'text-error',
        'Medium': 'text-primary',
        'Low': 'text-secondary'
    };
    return map[priority] || 'text-gray-500';
};
</script>
<template>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-xs uppercase tracking-wider text-secondary">
                    <th class="px-6 py-4 font-medium">#</th>
                    <th @click="handleSort('project_name')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Project Name
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>

                    <th @click="handleSort('client_name')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Client
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>

                    <th @click="handleSort('status')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Status
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>

                    <th @click="handleSort('priority')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Priority
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>

                    <th @click="handleSort('start_date')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Start Date
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>

                    <th @click="handleSort('due_date')"
                        class="px-6 py-4 font-medium cursor-pointer hover:text-on-surface">
                        <div class="flex items-center gap-1">
                            Due Date
                            <i class="material-symbols-outlined text-sm opacity-60">
                                {{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
                            </i>
                        </div>
                    </th>
                    <th class="px-6 py-4 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                <tr v-for="(project, index) in projects" :key="project.id"
                    class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-6 py-4 text-secondary font-mono text-xs">{{ (pagination.current_page - 1) * 10 + index
                        + 1 }}</td>
                    <td class="px-6 py-4 font-bold text-on-surface">{{ project.project_name }}</td>
                    <td class="px-6 py-4 text-secondary">{{ project.client_name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold" :class="getStatusClass(project.status)">
                            {{ project.status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1.5 text-xs font-bold"
                            :class="getPriorityClass(project.priority)">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            {{ project.priority }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-secondary">{{ project.start_date }}</td>
                    <td class="px-6 py-4 text-secondary">{{ project.due_date }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <Link :href="route('projects.edit', project.id)"
                                class="text-primary p-1.5 hover:bg-primary/10 rounded transition-colors cursor-pointer"
                                title="Edit Project">
                                <i class="material-symbols-outlined text-sm">edit</i>
                            </Link>
                            <button @click="openDeleteModal(project)"
                                class="text-secondary p-1.5 hover:bg-error/10 hover:text-error rounded transition-colors cursor-pointer"
                                title="Delete Project">
                                <i class="material-symbols-outlined text-sm">delete</i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <!-- Pagination Controls -->
    <div v-if="pagination.total > 0"
        class="px-6 py-4 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-secondary">
            Showing <span class="font-semibold text-on-surface">{{ pagination.from }}</span> to
            <span class="font-semibold text-on-surface">{{ pagination.to }}</span> of
            <span class="font-semibold text-on-surface">{{ pagination.total }}</span> entries
        </div>

        <div class="flex items-center gap-2">
            <button @click="emit('page-change', pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                class="px-3 py-1.5 border border-outline-variant rounded text-sm disabled:opacity-40 disabled:cursor-not-allowed hover:bg-surface-container-low transition-colors">
                Previous
            </button>

            <button v-for="page in pagination.last_page" :key="page" @click="emit('page-change', page)" :class="[
                'px-3 py-1.5 rounded text-sm font-medium transition-colors',
                page === pagination.current_page
                    ? 'bg-primary text-white'
                    : 'border border-outline-variant hover:bg-surface-container-low text-on-surface'
            ]">
                {{ page }}
            </button>

            <button @click="emit('page-change', pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1.5 border border-outline-variant rounded text-sm disabled:opacity-40 disabled:cursor-not-allowed hover:bg-surface-container-low transition-colors">
                Next
            </button>
        </div>
    </div>
    <DeleteConfirmModal :is-open="isModalOpen" :project="selectedProject" @confirm="handleConfirmDelete"
        @cancel="closeDeleteModal" />
</template>

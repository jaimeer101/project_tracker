<script setup lang="ts">
import { computed, ref, onMounted, watch } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectForm from '@/Components/project/ProjectForm.vue';

interface ProjectData {
    id?: number;
    name?: string;
    client?: string;
    description?: string;
    status?: string;
    priority?: string;
    startDate?: string;
    dueDate?: string;
}

const props = defineProps<{
    id?: number | string;
}>();

const page = usePage();
const isEdit = computed(() => Boolean(props.id));
const isLoading = ref(false);
const alert = ref<{ type: 'success' | 'error'; message: string } | null>(null);

// Initialize empty Inertia Form helper
const form = useForm({
    name: '',
    client: '',
    description: '',
    status: 'Planning',
    priority: 'Medium',
    startDate: '',
    dueDate: '',
});

// Fetch project details from API when in edit mode
const fetchProject = async () => {
    if (!props.id) return;

    isLoading.value = true;
    try {
        // Adjust API endpoint path if needed (e.g., `/api/projects/${props.id}` or route('projects.api.show', props.id))
        const response = await axios.get(route('projects.api.show', props.id));
        const data = response.data.data || response.data;

        // Populate Inertia form state with API response
        form.name = data.name || data.project_name || '';
        form.client = data.client || data.client_name || '';
        form.description = data.description || '';
        form.status = data.status || 'Planning';
        form.priority = data.priority || 'Medium';
        form.startDate = data.startDate || data.start_date || '';
        form.dueDate = data.dueDate || data.due_date || '';
    } catch (error) {
        alert.value = { type: 'error', message: 'Failed to load project details. Please try again.' };
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    if (isEdit.value) {
        fetchProject();
    }
});

watch(
    () => page.props.flash,
    (flash: any) => {
        if (flash?.success) {
            alert.value = { type: 'success', message: flash.success };
        } else if (flash?.error) {
            alert.value = { type: 'error', message: flash.error };
        }
    },
    { immediate: true, deep: true }
);

// Handle Form Submission via Inertia
const handleSave = (formData: ProjectData) => {
    Object.assign(form, formData);

    if (isEdit.value && props.id) {
        form.put(route('projects.api.update', { id: props.id }), {
            onSuccess: () => {
                alert.value = { type: 'success', message: 'Project updated successfully!' };
            },
            onError: () => {
                alert.value = { type: 'error', message: 'Please resolve the highlighted errors in the form.' };
            },
        });
    } else {
        form.post(route('projects.api.create'), {
            onSuccess: () => {
                alert.value = { type: 'success', message: 'Project created successfully!' };
                form.reset();
                form.clearErrors();
            },
            onError: () => {
                alert.value = { type: 'error', message: 'Please resolve the highlighted errors in the form.' };
            },
        });
    }
};

const handleCancel = () => {
    router.visit('/projects/view');
};

const dismissAlert = () => {
    alert.value = null;
};
</script>

<template>

    <Head :title="isEdit ? 'Edit Project' : 'Create Project'" />

    <AuthenticatedLayout :title="isEdit ? 'Edit Project' : 'Create Project'">
        <div class="max-w-5xl mx-auto">
            <div class="mb-8">
                <button @click="handleCancel"
                    class="text-primary flex items-center gap-2 mb-4 hover:underline cursor-pointer">
                    <i class="material-symbols-outlined text-sm">arrow_back</i> Back to Projects
                </button>
                <h2 class="text-3xl font-bold text-on-surface">
                    {{ isEdit ? 'Edit Project' : 'Create New Project' }}
                </h2>
            </div>

            <!-- Alert Notification -->
            <div v-if="alert" :class="[
                'mb-6 p-4 rounded-xl border flex items-center justify-between shadow-sm transition-all',
                alert.type === 'success'
                    ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                    : 'bg-red-50 border-red-200 text-red-800'
            ]">
                <div class="flex items-center gap-3">
                    <i class="material-symbols-outlined text-xl">
                        {{ alert.type === 'success' ? 'check_circle' : 'error' }}
                    </i>
                    <span class="font-medium text-sm">{{ alert.message }}</span>
                </div>
                <button @click="dismissAlert" class="hover:opacity-75 cursor-pointer p-1 rounded">
                    <i class="material-symbols-outlined text-lg">close</i>
                </button>
            </div>

            <!-- Loading Spinner State -->
            <div v-if="isLoading"
                class="p-12 text-center bg-surface-container-lowest rounded-xl border border-outline-variant">
                <i class="material-symbols-outlined text-4xl animate-spin text-primary mb-2">progress_activity</i>
                <p class="text-secondary text-sm font-medium">Loading project details...</p>
            </div>

            <!-- Project Form Component -->
            <ProjectForm v-else :is-edit="isEdit" :initial-data="form" :is-submitting="form.processing"
                :errors="form.errors" @submit="handleSave" @cancel="handleCancel" />
        </div>
    </AuthenticatedLayout>
</template>
<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
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
    project?: ProjectData;
}>();
const page = usePage();
const isEdit = computed(() => Boolean(props.project?.id));
const alert = ref<{ type: 'success' | 'error'; message: string } | null>(null);
// Initialize Inertia Form helper
console.log(props.project)
const form = useForm({
    name: props.project?.name || '',
    client: props.project?.client || '',
    description: props.project?.description || '',
    status: props.project?.status || 'Planning',
    priority: props.project?.priority || 'Medium',
    startDate: props.project?.startDate || '',
    dueDate: props.project?.dueDate || '',
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
    // Sync latest data emitted from ProjectForm to Inertia form state
    Object.assign(form, formData);

    if (isEdit.value && props.project?.id) {
        form.put(route('projects.api.update', { id: props.project.id }), {
            onSuccess: () => {
                alert.value = { type: 'success', message: 'Project updated successfully!' };
            },
            onError: () => {
                alert.value = { type: 'error', message: 'Please resolve the highlighted errors in the form.' };
            },
        });
    } else {
        // Point to your web store route (e.g. route('projects.store') or '/projects/store')
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
            <!-- Project Form Component -->
            <ProjectForm :is-edit="isEdit" :initial-data="form" :is-submitting="form.processing" :errors="form.errors"
                @submit="handleSave" @cancel="handleCancel" />
        </div>
    </AuthenticatedLayout>
</template>
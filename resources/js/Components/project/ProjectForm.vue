<script setup>
import { usePage } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';

const page = usePage();
const statuses = page.props.enums?.statuses || [];
const priorities = page.props.enums?.priorities || [];

const props = defineProps({
    initialData: {
        type: Object,
        default: () => ({})
    },
    isEdit: {
        type: Boolean,
        default: false
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    isSubmitting: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['submit', 'cancel']);

// Local reactive state synchronized with incoming initialData
const form = reactive({
    name: props.initialData?.name || '',
    client: props.initialData?.client || '',
    description: props.initialData?.description || '',
    status: props.initialData?.status || 'Planning',
    priority: props.initialData?.priority || 'Medium',
    startDate: props.initialData?.startDate || '',
    dueDate: props.initialData?.dueDate || ''
});

// Watch for initialData changes (useful when loaded asynchronously or during edits)
watch(
    () => props.initialData,
    (newData) => {
        if (newData) {
            form.name = newData.name || '';
            form.client = newData.client || '';
            form.description = newData.description || '';
            form.status = newData.status || 'Planning';
            form.priority = newData.priority || 'Medium';
            form.startDate = newData.startDate || '';
            form.dueDate = newData.dueDate || '';
        }
    },
    { deep: true }
);

const handleSubmit = () => {
    emit('submit', { ...form });
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-8">
        <div
            class="grid grid-cols-2 gap-x-8 gap-y-6 bg-surface-container-lowest p-8 rounded-xl border border-outline-variant shadow-sm">

            <!-- General Information Header -->
            <h3
                class="col-span-2 text-xl font-bold border-b border-outline-variant pb-4 mb-2 flex items-center gap-2 text-on-surface">
                <i class="material-symbols-outlined text-primary">info</i>
                General Information
            </h3>

            <!-- Project Name -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Project Name <span class="text-error">*</span>
                </label>
                <input v-model="form.name" type="text" :class="[
                    'w-full border rounded p-3 focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.name ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]" placeholder="e.g., Q4 Enterprise Migration">
                <p v-if="errors.name" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.name }}
                </p>
            </div>

            <!-- Client Name -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Client Name <span class="text-error">*</span>
                </label>
                <input v-model="form.client" type="text" :class="[
                    'w-full border rounded p-3 focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.client ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]" placeholder="e.g., Acme Corp">
                <p v-if="errors.client" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.client }}
                </p>
            </div>

            <!-- Description -->
            <div class="col-span-2 space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Description
                </label>
                <textarea v-model="form.description" rows="4" :class="[
                    'w-full border rounded p-3 focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.description ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]" placeholder="Briefly describe the scope and objectives..."></textarea>
                <p v-if="errors.description" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.description }}
                </p>
            </div>

            <!-- Classification Header -->
            <h3
                class="col-span-2 text-xl font-bold border-b border-outline-variant pb-4 mt-4 flex items-center gap-2 text-on-surface">
                <i class="material-symbols-outlined text-primary">category</i>
                Classification
            </h3>

            <!-- Status -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Current Status
                </label>
                <select v-model="form.status" :class="[
                    'w-full border rounded p-3 bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.status ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]">
                    <option v-for="status in statuses" :key="status" :value="status">
                        {{ status }}
                    </option>
                </select>
                <p v-if="errors.status" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.status }}
                </p>
            </div>

            <!-- Priority -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Priority Level
                </label>
                <select v-model="form.priority" :class="[
                    'w-full border rounded p-3 bg-white focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.priority ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]">
                    <option v-for="priority in priorities" :key="priority" :value="priority">
                        {{ priority }}
                    </option>
                </select>
                <p v-if="errors.priority" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.priority }}
                </p>
            </div>

            <!-- Timeline Header -->
            <h3
                class="col-span-2 text-xl font-bold border-b border-outline-variant pb-4 mt-4 flex items-center gap-2 text-on-surface">
                <i class="material-symbols-outlined text-primary">calendar_today</i>
                Timeline & Schedule
            </h3>

            <!-- Start Date -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Start Date
                </label>
                <input v-model="form.startDate" type="date" :class="[
                    'w-full border rounded p-3 focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.startDate ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]">
                <p v-if="errors.startDate" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.startDate }}
                </p>
            </div>

            <!-- Due Date -->
            <div class="space-y-2">
                <label class="block text-xs uppercase tracking-wider font-bold text-secondary">
                    Due Date
                </label>
                <input v-model="form.dueDate" type="date" :class="[
                    'w-full border rounded p-3 focus:ring-2 focus:ring-primary/20 outline-none transition-all',
                    errors.dueDate ? 'border-red-500 focus:ring-red-200' : 'border-outline'
                ]">
                <p v-if="errors.dueDate" class="text-red-500 text-xs mt-1 font-medium">
                    {{ errors.dueDate }}
                </p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-outline-variant">
            <button type="button" @click="$emit('cancel')" :disabled="isSubmitting"
                class="px-8 py-2.5 border border-outline rounded font-medium hover:bg-surface-container-low transition-colors disabled:opacity-50">
                Cancel
            </button>
            <button type="submit" :disabled="isSubmitting"
                class="px-8 py-2.5 bg-primary text-white rounded font-bold hover:bg-primary/90 transition-all shadow-sm active:transform active:scale-95 disabled:opacity-50 flex items-center gap-2">
                <span v-if="isSubmitting" class="animate-spin text-sm">⏳</span>
                {{ isEdit ? 'Update Project' : 'Create Project' }}
            </button>
        </div>
    </form>
</template>
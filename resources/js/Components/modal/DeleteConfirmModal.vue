<script setup>
/**
 * DeleteProjectModal.vue
 * 
 * A reusable confirmation modal for project deletion.
 * Matches the Kinetic Enterprise design system.
 */

defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    project: {
        type: Object,
        required: false,
        default: () => ({ name: '', id: null })
    }
});

defineEmits(['confirm', 'cancel']);
</script>
<template>
    <!-- Overlay Backdrop -->
    <div v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-surface/40 backdrop-blur-sm transition-opacity"
        @click.self="$emit('cancel')">
        <!-- Modal Container -->
        <div class="w-full max-w-md bg-surface-container-lowest rounded-xl shadow-xl border border-outline-variant overflow-hidden transform transition-all"
            role="dialog" aria-modal="true">
            <div class="p-8">
                <!-- Icon & Title Section -->
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center flex-shrink-0 text-error">
                        <i class="material-symbols-outlined text-2xl">warning_amber</i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-on-surface mb-2">Delete Project?</h3>
                        <p class="text-secondary leading-relaxed">
                            Are you sure you want to delete <span class="font-bold text-on-surface">"{{ project?.name ||
                                'this project' }}"</span>?
                            This action cannot be undone and will permanently remove all associated tasks, files, and
                            team access.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <button type="button" @click="$emit('cancel')"
                        class="px-6 py-2.5 border border-outline rounded font-medium hover:bg-surface-container-low transition-colors text-on-surface order-2 sm:order-1">
                        Cancel
                    </button>
                    <button type="button" @click="$emit('confirm', project?.id)"
                        class="px-6 py-2.5 bg-error text-white rounded font-bold hover:bg-error/90 transition-all shadow-sm active:transform active:scale-95 flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="material-symbols-outlined text-sm">delete_outline</i>
                        Delete Project
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Modal Entrance Animations */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
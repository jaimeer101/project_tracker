<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Login" />
    <div class="min-h-screen flex font-sans">
        <!-- Left Side: Brand Imagery -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-surface-container-low overflow-hidden">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAOR9bgKGpRNEGXgGhUaF1YmzXfmmMqAoUjCiB-Q3BYzr7b13VfetHaaNUteqzafxirshCVhl-CS1S29gnMksSV1Ho4Dldm4t5p8LKheYB4wCJrfIbgmR2Dmf_kRlE2UGUPt95LqGpvR2fmKi1Ac7eIYECmR2RtWr5zMZfdub9PHeLR-by1zO8GCIU_ata7Mp82yHzXpE7dJkef3O3c5K3-HkideJhK6lCgNb_VvGozvfeqNBt5Yjs2rw"
                alt="Project Tracker" class="absolute inset-0 w-full h-full object-cover opacity-100">
            <div class="relative z-10 p-20 flex flex-col justify-end h-full text-white">
                <h2 class="text-5xl font-bold mb-6 leading-tight">Project Tracker</h2>
                <p class="text-lg opacity-90 max-w-md mb-12">Simple Create-Read-Update-Delete of Projects</p>


            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 bg-surface-container-lowest flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <div class="mb-12">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center text-primary mb-6">
                        <i class="material-symbols-outlined">business</i>
                    </div>
                    <h1 class="text-4xl font-bold text-on-surface mb-2">Project Tracker</h1>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs uppercase tracking-wider font-bold text-secondary">Email
                            Address</label>
                        <div class="relative">
                            <i
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary/60 text-sm">mail_outline</i>
                            <input id="email" v-model="form.email" type="email" placeholder="name@company.com"
                                class="w-full border border-outline rounded p-4 pl-12 focus:ring-2 focus:ring-primary/20 outline-none"
                                required>
                        </div>
                        <p v-if="form.errors.email" class="text-error font-body-md text-xs mt-1">{{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label
                                class="block text-xs uppercase tracking-wider font-bold text-secondary">Password</label>

                        </div>
                        <div class="relative">
                            <i
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary/60 text-sm">lock_outline</i>
                            <input id="email" v-model="form.password" type="password" placeholder="••••••••"
                                class="w-full border border-outline rounded p-4 pl-12 focus:ring-2 focus:ring-primary/20 outline-none"
                                required>
                            <i
                                class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary/60 text-sm cursor-pointer hover:text-primary">visibility_off</i>
                        </div>
                        <p v-if="form.errors.password" class="text-error font-body-md text-xs mt-1">{{
                            form.errors.password }}
                        </p>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white py-4 rounded font-bold hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                        Sign In <i class="material-symbols-outlined text-sm">arrow_forward</i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

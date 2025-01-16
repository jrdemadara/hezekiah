<script setup>
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Loader2, MoveLeft } from 'lucide-vue-next';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Password changed successfully.');
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Add Member" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('settings.index')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Update Password
                </h2>
                <Link :href="route('settings.index')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] select-none flex-col px-6 pb-16">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Update Password
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Ensure your account is using a long, random password to stay
                    secure.
                </p>
            </header>

            <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                <div>
                    <InputLabel
                        for="current_password"
                        value="Current Password"
                    />

                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                    />

                    <InputError
                        :message="form.errors.current_password"
                        class="mt-2"
                    />
                </div>

                <div>
                    <InputLabel for="password" value="New Password" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                    />

                    <InputError
                        :message="form.errors.password_confirmation"
                        class="mt-2"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] text-sm tracking-widest text-white hover:bg-[#427E00]"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <Loader2
                            class="flex-shrink-0 animate-spin"
                            v-if="form.processing"
                        />
                        <span
                            v-text="
                                form.processing ? 'Please wait...' : 'Proceed'
                            "
                        ></span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

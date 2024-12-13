<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Workflow } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
const form = useForm({
    code: '',
});

const checkCode = () => {
    form.post(route('referral-code.check'), {
        onSuccess: () => {
            toast.success('You’ve successfully linked to your referrer.');
            setTimeout(() => {
                window.location.href = route('dashboard');
            }, 5000);
        },
        onError: (error) => {
            switch (error.status) {
                case 404:
                    toast.error('No matching referral code found.');
                    break;
                case 403:
                    toast.error('You are already linked to a referrer.');
                    break;
                case 500:
                    toast.error(
                        'An unexpected error occurred. Please try again.',
                    );
                    break;
            }
        },
    });
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="flex h-screen flex-col items-center bg-white py-6">
            <div class="rounded-lg p-2 ring-1 ring-slate-200">
                <Workflow class="mr-0.5 text-black" :size="26" />
            </div>
            <h2 class="mt-4 text-xl font-bold">Referral Code</h2>
            <p class="mt-1 text-xs font-light text-slate-700">
                Please enter the referral code of the person who invited you.
            </p>
            <div class="mt-4 flex w-3/4 flex-col items-center justify-center">
                <div class="w-full">
                    <TextInput
                        id="code"
                        type="text"
                        class="mt-1 block h-12 w-full uppercase"
                        v-model="form.code"
                        required
                        placeholder="Referral Code"
                    />
                    <InputError class="mt-2" :message="form.errors.code" />
                </div>
                <Button
                    @click="checkCode()"
                    class="mt-5 flex h-12 w-full items-center justify-center rounded-md border bg-[#458500] px-2 text-white"
                >
                    Proceed
                </Button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

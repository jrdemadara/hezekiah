<script setup>
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, MailCheck } from 'lucide-vue-next';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Head title="Email Verification" />
    <MainLayout>
        <div class="flex h-full flex-col items-center py-6">
            <div class="rounded-lg p-2 ring-1 ring-slate-700">
                <MailCheck class="mr-0.5 text-black" :size="26" />
            </div>
            <h2 class="mt-4 text-xl font-bold">Email Verification</h2>

            <div class="my-2 text-center text-sm text-gray-600">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            <div
                class="mb-4 text-sm font-medium text-green-600"
                v-if="verificationLinkSent"
            >
                A new verification link has been sent to the email address you
                provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div
                    class="mt-4 flex flex-col items-center justify-between space-y-3"
                >
                    <PrimaryButton
                        class="mt-5 flex h-12 w-full items-center justify-center rounded-md border bg-[#458500] px-2 text-white"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Resend Verification Email
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >Log Out</Link
                    >
                </div>
            </form>
        </div>
    </MainLayout>
</template>

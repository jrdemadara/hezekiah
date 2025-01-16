<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Loader2, MoveLeft } from 'lucide-vue-next';

const user = usePage().props.auth.user;

const form = useForm({
    lastname: user.lastname,
    firstname: user.firstname,
    middlename: user.middlename,
    phone: user.phone,
    address: user.address,
});
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
                    Profile Information
                </h2>
                <Link :href="route('settings.index')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] select-none flex-col px-6 pb-16">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Profile Information
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Update your account's profile information.
                </p>
            </header>
            <form
                @submit.prevent="form.patch(route('profile.update'))"
                class="mt-6 space-y-6"
            >
                <div>
                    <InputLabel for="lastname" value="Lastname" />

                    <TextInput
                        id="lastname"
                        type="text"
                        class="mt-1 block w-full capitalize"
                        v-model="form.lastname"
                        required
                        autofocus
                        autocomplete="lastname"
                    />

                    <InputError class="mt-2" :message="form.errors.lastname" />
                </div>

                <div>
                    <InputLabel for="firstname" value="Firstname" />

                    <TextInput
                        id="firstname"
                        type="text"
                        class="mt-1 block w-full capitalize"
                        v-model="form.firstname"
                        required
                        autofocus
                        autocomplete="firstname"
                    />

                    <InputError class="mt-2" :message="form.errors.firstname" />
                </div>

                <div>
                    <InputLabel for="middlename" value="Middlename" />

                    <TextInput
                        id="middlename"
                        type="text"
                        class="mt-1 block w-full capitalize"
                        v-model="form.middlename"
                        required
                        autofocus
                        autocomplete="middlename"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.middlename"
                    />
                </div>

                <div>
                    <InputLabel for="phone" value="Phone" />

                    <TextInput
                        id="phone"
                        type="phone"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        required
                        readonly
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div>
                    <InputLabel for="address" value="Address" />

                    <TextInput
                        id="address"
                        type="address"
                        class="mt-1 block w-full capitalize"
                        v-model="form.address"
                        required
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <div v-if="mustVerifyEmail && user.email_verified_at === null">
                    <p class="mt-2 text-sm text-gray-800">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-sm font-medium text-green-600"
                    >
                        A new verification link has been sent to your email
                        address.
                    </div>
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

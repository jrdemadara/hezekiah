<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, useForm, router } from '@inertiajs/vue3';
import { Check, Loader2, MoveLeft, Plus } from 'lucide-vue-next';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const form = useForm({
    lastname: null,
    firstname: null,
    middlename: null,
    phone: null,
    address: null,
    username: null,
    code: null,
});

const save = async () => {
    form.post(route('add-member.store'), {
        onSuccess: () => {
            toast.success('Success!');
            form.reset();
        },
        onError: (err) => {
            toast.error(err.message);
        },
    });
};
</script>

<template>
    <Head title="Add Member" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Add Member
                </h2>
                <Link :href="route('dashboard')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] flex-col pb-16">
            <form @submit.prevent="save" class="h-52 w-full px-4">
                <h2 class="font-semibold">Add New Member</h2>
                <div class="my-4">
                    <InputLabel value="Lastname" />
                    <TextInput
                        id="lastname"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="form.lastname"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.lastname" />
                </div>
                <div class="my-4">
                    <InputLabel value="Firstname" />
                    <TextInput
                        id="firstname"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="form.firstname"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.firstname" />
                </div>
                <div class="my-4">
                    <InputLabel value="Middlename" />
                    <TextInput
                        id="middlename"
                        type="text"
                        class="h-province mt-1 block w-full capitalize"
                        v-model="form.middlename"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.middlename"
                    />
                </div>
                <div class="my-4">
                    <InputLabel value="Phone Nummber" />
                    <TextInput
                        id="phone"
                        type="number"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="form.phone"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>
                <div class="mb-8 mt-4">
                    <InputLabel value="Complete Address" />
                    <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="form.address"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <hr />
                <div class="my-4">
                    <InputLabel value="Username" />
                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block h-12 w-full lowercase"
                        v-model="form.username"
                        required
                        placeholder="Member's Username"
                    />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <hr />
                <div class="mb-4 mt-6">
                    <InputLabel value="Code" />
                    <TextInput
                        id="code"
                        type="text"
                        class="mt-1 block h-12 w-full uppercase"
                        v-model="form.code"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.code" />
                </div>

                <div class="mb-4 mt-6">
                    <p>
                        <strong class="text-red-500">Reminder:</strong> The new
                        member's default password is their
                        <strong>username</strong> followed by the last 4 digits
                        of their mobile number (e.g.,
                        <strong>john1357</strong>). Please make sure to change
                        it after logging in for the first time.
                    </p>
                </div>

                <div class="mt-8 flex items-center justify-between space-x-2">
                    <Link
                        :href="route('dashboard')"
                        class="flex h-12 w-1/2 items-center justify-center space-x-1 rounded-lg bg-gray-200 hover:bg-gray-300"
                    >
                        Cancel
                    </Link>
                    <button
                        class="flex h-12 w-1/2 items-center justify-center space-x-1 rounded-lg bg-[#458500] text-white hover:bg-[#427E00]"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <Loader2
                            class="flex-shrink-0 animate-spin"
                            v-if="form.processing"
                        />
                        <span
                            v-text="form.processing ? 'Please wait...' : 'Save'"
                        ></span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Loader2, MoveLeft } from 'lucide-vue-next';

const props = usePage().props;
const user = usePage().props.auth.user;
const form = useForm({
    wallet: props.auth.ewallet !== null ? props.auth.ewallet[0].wallet : null,
    account_number:
        props.auth.ewallet !== null
            ? props.auth.ewallet[0].account_number
            : null,
    account_name:
        props.auth.ewallet !== null ? props.auth.ewallet[0].account_name : null,
});
</script>

<template>
    <Head title="Cashout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('settings.index')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    E-wallet
                </h2>
                <Link :href="route('settings.index')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] select-none flex-col px-6 pb-16">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Default E-wallet
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Provide your default e-wallet for cashouts.
                </p>
            </header>
            <form
                @submit.prevent="form.post(route('e-wallet.store'))"
                class="mt-6 space-y-6"
            >
                <div>
                    <InputLabel for="wallet" value="E-Wallet" />
                    <select
                        class="focus:border-indigo-[#458500] h-12 w-full rounded-lg border-gray-300 shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
                        id="wallet"
                        v-model="form.wallet"
                    >
                        <option selected disabled value="none">
                            Select E-wallet
                        </option>
                        <option value="gcash">Gcash</option>
                        <option value="maya">Maya</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.lastname" />
                </div>

                <div>
                    <InputLabel for="account_number" value="Account Number" />

                    <TextInput
                        id="account_number"
                        type="text"
                        class="mt-1 block w-full font-semibold capitalize"
                        v-model="form.account_number"
                        required
                        autofocus
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.account_number"
                    />
                    <div class="mt-2 flex items-center">
                        <input
                            @click="form.account_number = user.phone"
                            type="checkbox"
                            id="same_as_phone"
                            class="h-5 w-5 rounded border-gray-300 text-[#458500] focus:ring-[#458500]"
                        />
                        <label
                            @click="form.account_number = user.phone"
                            for="same_as_phone"
                            class="ml-2"
                            >Same as:
                            <span class="font-semibold text-[#458500]">{{
                                user.phone
                            }}</span></label
                        >
                    </div>
                </div>

                <div>
                    <InputLabel for="account_name" value="Account Name" />

                    <TextInput
                        id="account_name"
                        type="text"
                        class="mt-1 block w-full font-semibold capitalize"
                        v-model="form.account_name"
                        required
                        autofocus
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.account_name"
                    />
                    <div class="mt-2 flex items-center">
                        <input
                            @click="
                                form.account_name =
                                    user.firstname +
                                    ' ' +
                                    user.middlename +
                                    ' ' +
                                    user.lastname
                            "
                            type="checkbox"
                            id="same_as_name"
                            class="h-5 w-5 rounded border-gray-300 text-[#458500] focus:ring-[#458500]"
                        />
                        <label
                            @click="
                                form.account_name =
                                    user.firstname +
                                    ' ' +
                                    user.middlename +
                                    ' ' +
                                    user.lastname
                            "
                            for="same_as_name"
                            class="ml-2"
                            >Same as:
                            <span class="font-semibold uppercase text-[#458500]"
                                >{{ user.firstname }} {{ user.middlename }}
                                {{ user.lastname }}</span
                            ></label
                        >
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

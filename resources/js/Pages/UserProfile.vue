<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import {
    ChevronRight,
    Lock,
    MoveLeft,
    Receipt,
    SquareStack,
    Trash,
    Trash2,
    User2,
    Wallet2,
} from 'lucide-vue-next';

const { props } = usePage();

const firstLetter = computed(() => {
    return props.auth.user.firstname ? props.auth.user.firstname.charAt(0) : '';
});

const logoutLink = computed(() => {
    return props.auth.user ? route('logout') : route('auth');
});
</script>

<template>
    <Head title="User Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Profile
                </h2>
                <Link :href="route('dashboard')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex flex-col items-center space-y-5 px-4">
            <div class="flex flex-col items-center justify-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-[#50962a]"
                >
                    <h2
                        class="font-serif text-5xl font-semibold uppercase text-white"
                    >
                        {{ firstLetter }}
                    </h2>
                </div>
                <h4 class="mt-1 font-mono text-lg font-semibold uppercase">
                    {{ props.auth.user.firstname }}
                    {{ props.auth.user.lastname }}
                </h4>

                <div class="-mt-1 flex flex-col items-center -space-y-0.5">
                    <span class="font-normal lowercase text-gray-700"
                        >@{{ props.auth.user.username }}</span
                    >
                    <span class="font-normal lowercase text-gray-700">{{
                        props.auth.user.phone
                    }}</span>
                </div>
            </div>
            <div class="w-full space-y-5 rounded-xl p-4 ring-1 ring-gray-200">
                <small class="uppercase text-gray-500">Profile Menu</small>
                <div class="flex flex-col space-y-3">
                    <div class="flex w-full items-center justify-between">
                        <User2 :size="28" />
                        <strong
                            class="ml-3 w-full text-start text-lg font-normal capitalize"
                            >My Profile</strong
                        >
                        <ChevronRight class="text-gray-500" :size="32" />
                    </div>
                    <div class="flex w-full items-center justify-between">
                        <Lock :size="28" />
                        <strong
                            class="ml-3 w-full text-start text-lg font-normal capitalize"
                            >Security</strong
                        >
                        <ChevronRight class="text-gray-500" :size="32" />
                    </div>
                    <Link
                        :href="route('e-wallet.index')"
                        class="flex w-full items-center justify-between"
                    >
                        <Wallet2 :size="28" />
                        <strong
                            class="ml-3 w-full text-start text-lg font-normal capitalize"
                            >Cashout E-wallet</strong
                        >
                        <ChevronRight class="text-gray-500" :size="32" />
                    </Link>
                    <div class="flex w-full items-center justify-between">
                        <SquareStack :size="28" />
                        <strong
                            class="ml-3 w-full text-start text-lg font-normal capitalize"
                            >Multi Accounts</strong
                        >
                        <ChevronRight class="text-gray-500" :size="32" />
                    </div>
                    <div class="flex w-full items-center justify-between">
                        <Trash2 :size="28" />
                        <strong
                            class="ml-3 w-full text-start text-lg font-normal capitalize"
                            >Delete Account</strong
                        >
                        <ChevronRight class="text-gray-500" :size="32" />
                    </div>
                </div>
                <Link
                    class="h-12 w-full rounded-xl bg-gray-200 text-lg font-medium"
                    :href="logoutLink"
                    method="post"
                    as="button"
                >
                    Log Out
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

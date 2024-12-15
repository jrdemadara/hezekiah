<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import { ChevronRight, Minus, MoveLeft, Plus, X } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';

const { props } = usePage();
const bagStore = useBagStore();

const backLink = computed(() => {
    return props.auth.user ? route('bag') : route('home');
});

const checkoutLink = computed(() => {
    return props.auth.user ? route('dashboard') : route('auth');
});
</script>

<template>
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="backLink">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center font-mono text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Checkout
                </h2>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] flex-col overflow-y-auto pb-16">
            <div
                class="flex w-full items-center justify-between px-5 py-5 shadow"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">Shipping Address</h2>
                    <p class="mt-2 text-xs font-semibold text-black">
                        Johnny Roger Sunico Demadara
                    </p>
                    <p class="text-xs text-stone-600">
                        Mabini Street, Quirino Ave.
                    </p>
                    <p class="text-xs text-stone-600">Poblacion</p>
                    <p class="text-xs text-stone-600">
                        Tacurong City, Sultan Kudarat 9800
                    </p>
                    <p class="text-xs text-stone-600">Philippines</p>
                    <p class="text-xs text-stone-600">+639972430944</p>
                </div>
                <div class="flex">
                    <Link
                        :href="route('address.index')"
                        class="rounded-full bg-stone-200 p-2"
                    >
                        <ChevronRight />
                    </Link>
                </div>
            </div>
            <div
                class="flex w-full items-center justify-between px-5 py-5 shadow"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">Shipping Method</h2>
                    <p class="mt-2 text-xs font-semibold text-black">
                        Johnny Roger Sunico Demadara
                    </p>
                </div>
                <div class="flex">
                    <Link
                        :href="route('address.index')"
                        class="rounded-full bg-stone-200 p-2"
                    >
                        <ChevronRight />
                    </Link>
                </div>
            </div>
            <div
                class="flex w-full items-center justify-between px-5 py-5 shadow"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">Payment Method</h2>
                    <p class="mt-2 text-xs font-semibold text-black">
                        Johnny Roger Sunico Demadara
                    </p>
                </div>
                <div class="flex">
                    <Link
                        :href="route('address.index')"
                        class="rounded-full bg-stone-200 p-2"
                    >
                        <ChevronRight />
                    </Link>
                </div>
            </div>
            <div
                class="flex w-full items-center justify-between px-5 py-5 shadow"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">2 Items</h2>
                    <p class="mt-2 text-xs font-semibold text-black">
                        Johnny Roger Sunico Demadara
                    </p>
                </div>
                <div class="flex">
                    <Link
                        :href="route('address.index')"
                        class="rounded-full bg-stone-200 p-2"
                    >
                        <ChevronRight />
                    </Link>
                </div>
            </div>
        </div>

        <!-- Fixed button at the bottom -->
        <div
            class="fixed bottom-0 left-0 right-0 flex items-center justify-between bg-white p-4 px-4 sm:px-6 lg:px-8"
        >
            <div class="flex flex-col">
                <small class="text-blue-600">Checkout Total</small>

                <h4 class="font-mono text-4xl font-semibold">
                    <span>₱</span>{{ bagStore.subtotal }}
                </h4>
            </div>

            <Link
                :href="checkoutLink"
                class="w-fit max-w-md justify-center rounded-full bg-[#EF6B21] px-6 py-3 text-center font-medium text-white shadow-md"
            >
                <span class="font-mono">Place Order</span>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>

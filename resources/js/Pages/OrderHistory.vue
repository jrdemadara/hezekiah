<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, useForm } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import { ChevronRight, MoveLeft } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const { props } = usePage();
const bagStore = useBagStore();
const defaultAddress = props.auth.address.find(
    (address) => address.is_default === 1,
);



const backLink = computed(() => {
    return props.auth.user ? route('bag') : route('home');
});

const placeOrder = async () => {
    toast.success('Order placed!');
    // form.post(route('address.save'), {
    //     onSuccess: () => {
    //         toast.success('Success!');
    //     },
    //     onError: () => {
    //         toast.error('Failed to add address!');
    //     },
    // });
};
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
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Checkout
                </h2>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] flex-col overflow-y-auto pb-16">
            <div
                class="flex w-full items-center justify-between px-5 py-5 shadow-sm"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">Shipping Address</h2>
                    <div v-if="defaultAddress" class="flex flex-col">
                        <p
                            class="mt-2 text-xs font-semibold capitalize text-black"
                        >
                            {{ defaultAddress.name }}
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            {{ defaultAddress.street }}
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            Poblacion
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            {{ defaultAddress.city }},
                            {{ defaultAddress.province }}
                            {{ defaultAddress.zipcode }}
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            Philippines
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            {{ defaultAddress.phone }}
                        </p>
                    </div>
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
                class="flex w-full items-center justify-between px-5 py-5 shadow-sm"
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
                class="flex w-full items-center justify-between px-5 py-5 shadow-sm"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">Payment Method</h2>
                    <div class="mt-2 flex flex-col items-start justify-center">
                        <p class="mb-4 text-sm text-stone-700">
                            Select Payment Method
                        </p>
                        <div class="flex items-center justify-center space-x-5">
                            <img
                                src="../../assets/images/gcash.png"
                                alt="maya"
                                class="h-8 rounded-sm"
                            />

                            <img
                                src="../../assets/images/maya.png"
                                alt="maya"
                                class="h-8 rounded-sm bg-black p-1"
                            />

                            <img
                                src="../../assets/images/visa.svg"
                                alt="maya"
                                class="h-8"
                            />
                        </div>
                    </div>
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
                class="flex w-full items-center justify-between px-5 py-5 shadow-sm"
            >
                <div class="flex flex-col">
                    <h2 class="font-semibold text-black">
                        {{ bagStore.bagCount }} Items
                    </h2>
                    <div
                        v-if="bagStore.bagCount > 0"
                        class="mt-4 flex space-x-4"
                    >
                        <div
                            v-for="item in bagStore.items"
                            :key="item.id"
                            class="flex flex-col items-center justify-center"
                        >
                            <img
                                :src="`/images/${item.id}.png`"
                                alt="Product Image"
                                class="h-14"
                            />
                            <p class="mt-2 text-sm text-black">
                                x {{ item.quantity }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <Link
                        :href="route('bag')"
                        class="rounded-full bg-stone-200 p-2"
                    >
                        <ChevronRight />
                    </Link>
                </div>
            </div>
        </div>

        <!-- Fixed button at the bottom -->
        <div
            class="fixed bottom-0 left-0 right-0 flex items-center justify-between space-x-8 bg-white p-4 px-4 sm:px-6 lg:px-8"
        >
            <div class="flex flex-col">
                <small class="text-blue-600">Checkout Total</small>

                <h4 class="font-mono text-3xl font-semibold">
                    <span>₱</span>{{ bagStore.subtotal }}
                </h4>
            </div>

            <button
                @click="placeOrder"
                class="flex h-14 w-full max-w-md items-center justify-center rounded-full bg-[#EF6B21] px-6 py-3 text-center font-medium text-white shadow-md"
            >
                <span class="font-mono">Place Order</span>
            </button>
        </div>
    </AuthenticatedLayout>
</template>

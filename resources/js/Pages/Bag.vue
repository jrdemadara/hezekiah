<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import { Minus, MoveLeft, Plus, X } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';

const { props } = usePage();
const bagStore = useBagStore();

const increment = (productId) => {
    bagStore.incrementQuantity(productId);
};

const decrement = (productId) => {
    bagStore.decrementQuantity(productId);
};

const backLink = computed(() => {
    return props.auth.user ? route('dashboard') : route('home');
});

const checkoutLink = computed(() => {
    return props.auth.user ? route('dashboard') : route('auth');
});
</script>

<template>
    <Head title="Bag" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="backLink">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center font-mono text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Bag
                </h2>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] flex-col overflow-y-auto pb-16">
            <div v-for="item in bagStore.items" :key="item.id" class="flex p-4">
                <!-- <img
                    :src="`/images/${item.id}.png`"
                    alt="Product Image"
                    class="mr-3 h-24 rounded-lg"
                /> -->
                <div class="flex w-full flex-col justify-between">
                    <div class="flex items-center justify-between py-1">
                        <h4 class="font-mono capitalize tracking-tight">
                            {{ item.name }}
                        </h4>
                        <div
                            @click="bagStore.removeFromBag(item.id)"
                            class="rounded-full p-1 ring-1 ring-gray-300"
                        >
                            <X :size="20" />
                        </div>
                    </div>
                    <div class="flex items-end justify-between py-1">
                        <h4 class="font-mono text-lg font-medium">
                            ₱{{ item.price }}
                        </h4>
                        <div
                            class="flex h-10 w-28 items-center justify-between rounded-full bg-gray-100 px-3 py-2"
                        >
                            <Minus @click="decrement(item.id)" :size="16" />
                            <p class="font-mono">{{ item.quantity }}</p>
                            <Plus @click="increment(item.id)" :size="16" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed button at the bottom -->
        <div
            class="fixed bottom-0 left-0 right-0 flex items-center justify-between p-4"
        >
            <h4 class="font-mono text-3xl font-medium tracking-tighter">
                {{ bagStore.subtotal }}
            </h4>
            <Link
                :href="checkoutLink"
                class="w-fit max-w-md justify-center rounded-lg bg-[#EF6B21] px-6 py-3 text-center font-medium text-white shadow-md"
            >
                <span class="font-mono">Go to Checkout</span>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>

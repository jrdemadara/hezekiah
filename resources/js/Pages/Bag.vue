<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, Head, Link, useForm } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import { Minus, MoveLeft, Plus, X } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const { props } = usePage();
const bagStore = useBagStore();

const bagForm = useForm({
    product_id: '',
});

const increment = (productId) => {
    bagForm.product_id = productId;
    if (props.auth.user) {
        bagForm.patch('bag-increment', {
            onSuccess: () => {
                bagStore.incrementQuantity(productId);
            },
            onError: (errors) => {
                toast.error('Something went wrong! Please try again.');
            },
        });
    } else {
        bagStore.incrementQuantity(productId);
    }
};

const decrement = (productId, quantity) => {
    bagForm.product_id = productId;
    if (quantity > 1) {
        if (props.auth.user) {
            bagForm.patch('bag-decrement', {
                onSuccess: () => {
                    bagStore.decrementQuantity(productId);
                },
                onError: () => {
                    toast.error('Something went wrong! Please try again.');
                },
            });
        } else {
            bagStore.decrementQuantity(productId);
        }
    }
};

const remove = (productId) => {
    bagForm.product_id = productId;
    if (props.auth.user) {
        bagForm.delete('bag-remove', {
            onSuccess: () => {
                bagStore.removeFromBag(productId);
            },
            onError: (errors) => {
                toast.error('Something went wrong! Please try again.');
            },
        });
    } else {
        bagStore.removeFromBag(productId);
    }
};

const backLink = computed(() => {
    return props.auth.user ? route('dashboard') : route('home');
});

const checkoutLink = computed(() => {
    return props.auth.user ? route('checkout.index') : route('auth');
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
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Bag
                </h2>
            </div>
        </template>

        <div
            v-if="bagStore.bagCount < 1"
            class="flex flex-col items-center justify-center"
        >
            <div
                class="mt-8 flex h-52 w-52 items-center justify-center rounded-full bg-stone-100 p-8"
            >
                <img
                    src="../../assets/images/empty-bag.svg"
                    alt="Bag Image"
                    class="w-24"
                />
            </div>

            <h2 class="mt-4 text-xl font-semibold">Your bag is empty.</h2>
        </div>
        <div
            v-if="bagStore.bagCount > 0"
            class="flex h-[calc(100vh-64px)] flex-col overflow-y-auto pb-16"
        >
            <div v-for="item in bagStore.items" :key="item.id" class="flex p-4">
                <img
                    :src="`/images/${item.id}.png`"
                    alt="Product Image"
                    class="mr-3 h-24 rounded-lg"
                />
                <div class="flex w-full flex-col justify-between">
                    <div class="flex items-center justify-between py-1">
                        <h4 class="font-mono capitalize tracking-tight">
                            {{ item.name }}
                        </h4>
                        <div
                            @click="remove(item.id)"
                            :disabled="bagForm.processing"
                            :class="{ 'opacity-25': bagForm.processing }"
                            class="rounded-full p-1 ring-1 ring-gray-300"
                        >
                            <X :size="20" />
                        </div>
                    </div>
                    <div class="flex items-end justify-between py-1">
                        <h4 class="font-mono text-lg font-semibold">
                            <span>₱</span>{{ item.price }}
                        </h4>
                        <div
                            class="flex h-10 w-28 items-center justify-between rounded-full bg-gray-100 px-3 py-2"
                        >
                            <Minus
                                @click="decrement(item.id, item.quantity)"
                                :disabled="bagForm.processing"
                                :class="{ 'opacity-25': bagForm.processing }"
                                :size="16"
                            />
                            <p class="font-mono">
                                {{ bagStore.getQuantityById(item.id) }}
                            </p>
                            <Plus
                                @click="increment(item.id)"
                                :disabled="bagForm.processing"
                                :class="{ 'opacity-25': bagForm.processing }"
                                :size="16"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fixed button at the bottom -->
        <div
            v-if="bagStore.bagCount > 0"
            class="fixed bottom-0 left-0 right-0 flex items-center justify-between bg-white p-4 px-4 sm:px-6 lg:px-8"
        >
            <h4 class="font-mono text-4xl font-semibold tracking-tighter">
                <span>₱</span>{{ bagStore.subtotal }}
            </h4>
            <Link
                :href="checkoutLink"
                class="flex h-14 max-w-md items-center justify-center rounded-full bg-[#EF6B21] px-6 py-3 text-center font-medium text-white shadow-md"
            >
                <span class="font-mono">Go to Checkout</span>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>

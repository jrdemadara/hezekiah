<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ArrowDown, BadgePlus, Plus, PlusCircle } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },

    products: {
        type: Array, // Accept the products prop directly here
        required: true,
    },
});

const bagStore = useBagStore();

const addToBag = (product) => {
    bagStore.addToBag(product);
};
</script>

<template>
    <MainLayout>
        <Head title="Welcome" />
        <div class="flex flex-col px-4 sm:px-0">
            <!-- First Section: Title and Description -->
            <div
                class="mt-10 flex flex-col items-center justify-center sm:mt-20 sm:items-start"
            >
                <h2
                    class="animate-slideIn font-areplos text-5xl font-black text-[#0C1F15] sm:text-8xl"
                >
                    Organic Oil
                </h2>
                <h6
                    style="animation-delay: 0.1s"
                    class="mt-2 animate-slideIn text-center font-areplos text-2xl font-medium tracking-wider text-[#EF6B21] sm:mt-6 sm:text-start sm:text-4xl"
                >
                    Pure natural organic oil
                </h6>
                <p
                    style="animation-delay: 0.2s"
                    class="mt-6 animate-slideIn text-center text-lg text-stone-600 sm:w-1/2"
                >
                    Discover the essence of purity with our organic
                    oil—harvested from nature's finest ingredients to nourish,
                    protect, and revitalize. Every drop is crafted with care,
                    offering you the healthiest choice for a radiant, natural
                    glow inside and out.
                </p>
                <div
                    class="mt-6 flex w-full flex-col items-center space-y-2 sm:w-fit"
                >
                    <ArrowDown class="animate-bounce" :size="32" />
                    <button
                        style="animation-delay: 0.3s"
                        class="h-14 w-full animate-slideUp rounded-full bg-[#EF6B21] text-lg font-medium text-white sm:w-40"
                    >
                        Buy now
                    </button>
                </div>
            </div>

            <!-- Second Section: Reviews/Testimonials -->
            <div class="mt-16 flex h-fit">
                <!-- Image Carousel (People) -->
                <div class="relative flex w-40 flex-col items-start">
                    <div class="absolute rounded-full ring-4 ring-gray-200">
                        <img
                            src="../../assets/images/person-1.jpg"
                            alt="Person 1"
                            class="h-14 w-14 rounded-full object-cover"
                        />
                    </div>
                    <div
                        class="absolute left-12 rounded-full ring-4 ring-gray-200"
                    >
                        <img
                            src="../../assets/images/person-2.jpg"
                            alt="Person 2"
                            class="h-14 w-14 rounded-full object-cover"
                        />
                    </div>
                    <div
                        class="absolute left-24 rounded-full ring-4 ring-gray-200"
                    >
                        <img
                            src="../../assets/images/person-3.jpg"
                            alt="Person 3"
                            class="h-14 w-14 rounded-full object-cover"
                        />
                    </div>
                </div>

                <!-- Customer Review -->
                <div class="ml-8 flex flex-col">
                    <h4 class="font-semibold sm:text-xl">
                        Our Satisfied Customers
                    </h4>
                    <div class="flex items-center space-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="#EF6B21"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5 text-[#EF6B21]"
                        >
                            <polygon
                                points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                            />
                        </svg>
                        <p class="font-semibold">
                            4.9
                            <span class="font-normal text-gray-600"
                                >(220 ratings)</span
                            >
                        </p>
                    </div>
                </div>
            </div>

            <!-- Third Section: Products -->
            <div class="mt-24 flex flex-col items-center">
                <h2 class="text-center font-areplos text-5xl text-[#0C1F15]">
                    Our Products
                </h2>
                <div
                    class="mt-12 grid grid-cols-1 justify-center gap-8 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        class="flex w-80 flex-col rounded-3xl bg-slate-50/60 p-6 shadow-md ring-1 ring-slate-50 hover:shadow-lg"
                        v-for="product in products"
                        :key="product.id"
                    >
                        <img
                            src="../../assets/images/prebio.png"
                            alt="Product Image"
                            class="h-full rounded-lg object-cover"
                        />
                        <p class="mt-4 text-xl font-semibold capitalize">
                            {{ product.name }}
                        </p>
                        <p class="text-gray-600">₱{{ product.price }}</p>
                        <button
                            class="mt-4 flex justify-center rounded-md bg-orange-500 px-4 py-2 text-white transition hover:bg-orange-400"
                            @click="addToBag(product)"
                        >
                            <Plus />
                            Add to bag
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

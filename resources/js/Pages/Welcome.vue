<script setup>
import { ref } from 'vue';
import { usePage, Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ArrowDown, Plus } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
// import heroImage from '../../assets/images/welcome-background.png';

const { props } = usePage();

defineProps({
    products: {
        type: Array, // Accept the products prop directly here
        required: true,
    },
});

const bagStore = useBagStore();

const bagForm = useForm({
    product_id: '',
});

const addToBag = (product) => {
    bagForm.product_id = product.id;
    if (props.auth.user) {
        bagForm.post('bag-add', {
            onSuccess: () => {
                bagStore.addToBag(product);
                //location.reload();
                toast.success('Item is added to bag!');
            },
            onError: (errors) => {
                console.error('Error adding to bag:', errors);
                toast.error('Something went wrong! Please try again later.');
            },
        });
    } else {
        bagStore.addToBag(product);
        toast.success('Item is added to bag!');
    }
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
                    class="animate-slideUp text-center font-areplos text-5xl font-black text-[#0C1F15] sm:animate-slideIn sm:text-8xl"
                >
                    Food Supplements
                </h2>
                <h6
                    style="animation-delay: 0.1s"
                    class="mt-2 animate-slideUp text-center font-areplos font-medium tracking-wider text-[#EF6B21] sm:mt-6 sm:animate-slideIn sm:text-start sm:text-4xl"
                >
                    Pure natural organic supplements
                </h6>
                <p
                    style="animation-delay: 0.2s"
                    class="mt-4 animate-slideUp text-center text-lg text-stone-600 sm:w-1/2 sm:animate-slideIn sm:text-start"
                >
                    Fuel your body and mind with our carefully crafted food
                    supplements and health-focused coffee blends. Whether you're
                    boosting your energy, supporting immunity, or finding
                    balance in your busy day, we've got you covered.
                </p>
                <div
                    class="mt-6 flex w-full flex-col items-center space-y-2 sm:w-fit"
                >
                    <ArrowDown class="animate-bounce" :size="32" />
                    <Link
                        :href="route('auth')"
                        style="animation-delay: 0.3s"
                        class="flex h-14 w-full animate-slideUp items-center justify-center rounded-full bg-[#EF6B21] text-lg font-medium text-white sm:w-40"
                    >
                        Join us!
                    </Link>
                </div>
            </div>

            <!-- Second Section: Reviews/Testimonials -->
            <div class="mt-16 flex h-fit">
                <!-- Image Carousel (People) -->
                <div
                    style="animation-delay: 0.4s"
                    class="relative flex w-40 animate-slideUp flex-col items-start"
                >
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
                <div
                    style="animation-delay: 0.5s"
                    class="ml-8 flex animate-slideUp flex-col"
                >
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
                    style="animation-delay: 0.6s"
                    class="mt-12 grid animate-slideUp grid-cols-1 justify-center gap-8 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        class="flex w-80 flex-col rounded-3xl bg-slate-50/60 p-6 shadow-md ring-1 ring-slate-50 hover:shadow-lg"
                        v-for="product in products"
                        :key="product.id"
                    >
                        <img
                            :src="`/images/${product.id}.png`"
                            alt="Product Image"
                            class="h-full w-full object-contain"
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

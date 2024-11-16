<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Search, ShoppingBag, ArrowDown } from 'lucide-vue-next';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const isMenuOpen = ref(false); // State for tracking the menu's visibility

// Toggle the menu open and closed
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// Close the menu
const closeMenu = () => {
    isMenuOpen.value = false;
};

defineProps({
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
});
</script>

<template>
    <MainLayout>
        <Head title="Welcome" />

        <main class="flex">
            <div class="mt-20 flex flex-col justify-center space-y-6">
                <h2
                    class="animate-slideIn font-areplos text-8xl font-black text-[#0C1F15]"
                >
                    Organic Oil
                </h2>
                <h6
                    style="animation-delay: 0.1s"
                    class="animate-slideIn mt-4 font-areplos text-4xl font-medium tracking-wider text-[#EF6B21]"
                >
                    Pure natural organic oil
                </h6>
                <p
                    style="animation-delay: 0.2s"
                    class="animate-slideIn w-1/2 text-lg text-stone-700"
                >
                    Discover the essence of purity with our organic
                    oil—harvested from nature's finest ingredients to nourish,
                    protect, and revitalize. Every drop is crafted with care,
                    offering you the healthiest choice for a radiant, natural
                    glow inside and out.
                </p>
                <div class="flex w-fit flex-col items-center space-y-2">
                    <ArrowDown class="animate-bounce" :size="32" />
                    <button
                        style="animation-delay: 0.3s"
                        class="animate-slideUp h-14 w-40 rounded-full bg-[#EF6B21] text-lg font-medium text-white"
                    >
                        Buy now
                    </button>
                </div>

                <div class="flex w-1/2 items-center bg-red-200">
                    <div class="flex"></div>
                    <div class="flex">2</div>
                    <div class="flex">3</div>
                </div>
            </div>
        </main>
    </MainLayout>
</template>

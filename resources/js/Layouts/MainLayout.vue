<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { ShoppingBag, User2 } from 'lucide-vue-next';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { useBagStore } from '@/Stores/bag';

const { props } = usePage();
const bagStore = useBagStore();

const isMenuOpen = ref(false); // State for tracking the menu's visibility

// Toggle the menu open and closed
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const bagCount = computed(() => {
    // If the user is authenticated, update the bag store with the server data
    if (props.auth.user && props.auth.bags) {
        // Clear existing items from the store
        bagStore.clearBag();

        // Add bags from server-side props to the store
        props.auth.bags.forEach((bag) => {
            bagStore.addToBag({
                id: bag.id,
                name: bag.name,
                price: bag.price,
                quantity: bag.quantity,
            });
        });
    }
    return bagStore.bagCount;
});
</script>

<template>
    <div class="bg-gradient-to-tr from-[#b9d898] to-[#DBE5CB] px-3 sm:px-16">
        <header class="animate-slideUp">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center justify-center">
                    <ApplicationLogo class="w-28" />
                </div>

                <nav
                    class="-mx-3 hidden flex-1 justify-center space-x-16 md:flex"
                >
                    <NavLink
                        v-if="props.auth.user"
                        :href="route('dashboard')"
                        class="group rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Dashboard
                        <div
                            class="h-0.5 w-full rounded-full bg-[#328458] opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100"
                        ></div>
                    </NavLink>
                    <NavLink
                        :href="route('home')"
                        :active="route().current('home')"
                        class="group px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Home
                    </NavLink>

                    <NavLink
                        :href="route('login')"
                        class="group px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Contact us
                    </NavLink>
                    <NavLink
                        :href="route('login')"
                        class="group rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        About us
                    </NavLink>
                </nav>

                <div class="flex items-center justify-center space-x-5">
                    <div class="flex items-center justify-center space-x-2">
                        <Link
                            :href="route('bag')"
                            class="relative rounded-full p-2 hover:bg-[#328458] hover:text-white"
                        >
                            <ShoppingBag class="text-black" />
                            <!-- Badge for showing the item count -->
                            <span
                                v-if="bagCount > 0"
                                class="absolute -right-0 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                            >
                                {{ bagCount }}
                            </span>
                        </Link>
                        <NavLink
                            :href="route('dashboard')"
                            class="relative rounded-full p-2 hover:bg-[#328458] hover:text-white"
                        >
                            <User2 class="text-black" />
                        </NavLink>
                    </div>
                    <!-- Hamburger Menu for Mobile -->
                    <div class="md:hidden">
                        <button @click="toggleMenu" class="focus:outline-none">
                            <!-- Hamburger Icon -->
                            <svg
                                v-if="!isMenuOpen"
                                class="h-8 w-8 transition-transform duration-300 ease-in-out"
                                fill="none"
                                stroke="#0C1F15"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"
                                />
                            </svg>
                            <!-- X Icon -->
                            <svg
                                v-else
                                class="h-8 w-8 transition-transform duration-300 ease-in-out"
                                fill="none"
                                stroke="#0C1F15"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile Navigation Menu -->

            <nav v-if="isMenuOpen" class="flex flex-col sm:hidden">
                <ResponsiveNavLink
                    v-if="props.auth.user"
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                    class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('home')"
                    :active="route().current('home')"
                    class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Home
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('home')"
                    class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Contact us
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('home')"
                    class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    About us
                </ResponsiveNavLink>
            </nav>
        </header>

        <main>
            <slot />
        </main>
        <footer class="flex animate-slideDown items-center justify-center p-6">
            <p class="font-semibold">© 2024 Hezekiah Health</p>
        </footer>
    </div>
</template>

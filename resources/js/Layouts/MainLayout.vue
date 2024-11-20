<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { ShoppingBag, User2, ChevronDown } from 'lucide-vue-next';
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
    if (props.auth.user) {
        // If authenticated, use the bag count from the server-side props
        return props.auth.bags || 0;
    } else {
        console.log('not');
        // If not authenticated, use the bag count from the local store
        return bagStore.bagCount;
    }
});
</script>

<template>
    <div class="bg-gradient-to-tr from-[#C6DCAE] to-[#DBE5CB] px-3 sm:px-16">
        <header>
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center justify-center">
                    <ApplicationLogo class="w-28" />
                </div>

                <nav
                    class="-mx-3 hidden flex-1 justify-center space-x-16 md:flex"
                >
                    <Link
                        :href="route('login')"
                        class="group rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Home
                        <div
                            class="h-0.5 w-full rounded-full bg-[#328458] opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100"
                        ></div>
                    </Link>

                    <Link
                        :href="route('login')"
                        class="group rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Contact us
                        <div
                            class="h-0.5 w-full rounded-full bg-[#328458] opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100"
                        ></div>
                    </Link>
                    <Link
                        :href="route('login')"
                        class="group rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-[#328458] focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        About us
                        <div
                            class="h-0.5 w-full rounded-full bg-[#328458] opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100"
                        ></div>
                    </Link>
                </nav>

                <div class="flex items-center justify-center space-x-5">
                    <div class="flex items-center justify-center space-x-2">
                        <Link
                            :href="route('bag')"
                            class="relative rounded-full p-2 hover:bg-[#328458] hover:text-white"
                        >
                            <ShoppingBag />
                            <!-- Badge for showing the item count -->
                            <span
                                v-if="bagCount > 0"
                                class="absolute -right-0 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                            >
                                {{ bagCount }}
                            </span>
                        </Link>
                        <div class="group relative">
                            <!-- Sign In Button -->
                            <div
                                class="flex cursor-pointer items-center rounded-full p-2 text-[#0C1F15] hover:bg-[#328458] hover:text-white"
                            >
                                <User2 />
                                <p class="ml-2 hidden sm:block">Sign in</p>
                                <ChevronDown :size="20" :stroke-width="1.5" />
                            </div>

                            <!-- Dropdown Menu -->
                            <div
                                class="absolute left-0 top-full mt-1 hidden w-40 flex-col divide-y divide-gray-200 rounded-lg bg-white shadow-lg group-hover:flex"
                            >
                                <a
                                    href="auth"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Login</a
                                >
                                <a
                                    href="auth"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Register</a
                                >
                                <a
                                    href="profile"
                                    class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >Profile</a
                                >
                            </div>
                        </div>
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

            <nav
                v-if="isMenuOpen && canLogin"
                class="flex flex-col space-y-2 p-4 md:hidden"
            >
                <Link
                    :href="route('dashboard')"
                    class="flex items-center font-semibold"
                >
                    Subscribe To Newsletters
                    <Mail class="ml-2" :size="18" />
                </Link>

                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md px-3 py-2 text-[#0C1F15] ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <main>
            <slot />
        </main>
        <footer class="flex items-center justify-center p-6">
            <p class="font-semibold">© 2024 Hezekiah Health</p>
        </footer>
    </div>
</template>

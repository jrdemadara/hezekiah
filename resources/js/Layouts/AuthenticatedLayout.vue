<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { usePage, Link } from '@inertiajs/vue3';
import { Bell, ShoppingBag } from 'lucide-vue-next';
import { useBagStore } from '@/Stores/bag';

const { props } = usePage();
const bagStore = useBagStore();

const showingNavigationDropdown = ref(false);

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

onMounted(() => {
    if (props.auth.user && props.auth.bags) {
        bagStore.clearBag();
        props.auth.bags.forEach((bag) => {
            bagStore.addToBag({
                id: bag.id,
                name: bag.name,
                description: bag.description,
                price: bag.price,
                image_url: bag.image_url,
                quantity: bag.quantity,
            });
        });
    }
});
</script>

<template>
    <div>
        <div class="bg-gray-100 sm:min-h-screen">
            <nav class="border-b border-gray-100 bg-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <!-- Left Section: Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-12 w-auto fill-current text-gray-800"
                                />
                            </Link>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div class="hidden space-x-8 sm:flex">
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Dashboard
                            </NavLink>
                            <NavLink
                                :href="route('home')"
                                :active="route().current('home')"
                            >
                                Products
                            </NavLink>
                        </div>

                        <!-- Right Section: Desktop Cart and User Menu -->
                        <div
                            class="hidden sm:flex sm:items-center sm:space-x-2"
                        >
                            <!-- Shopping Bag -->
                            <Link
                                :href="route('bag')"
                                class="relative rounded-full p-2 hover:bg-[#328458] hover:text-white"
                            >
                                <ShoppingBag />
                                <span
                                    v-if="bagStore.bagCount > 0"
                                    class="absolute -right-0 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                                >
                                    {{ bagStore.bagCount }}
                                </span>
                            </Link>

                            <!-- User Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="$page.props.auth.user"
                                            type="button"
                                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium capitalize text-gray-500 hover:text-gray-700 focus:outline-none"
                                        >
                                            {{
                                                $page.props.auth.user.firstname
                                            }}
                                            {{ $page.props.auth.user.lastname }}
                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile Section: Bell, Bag, and Menu -->
                        <div class="flex items-center space-x-4 sm:hidden">
                            <!-- Bell Icon -->
                            <div class="relative rounded-full p-2">
                                <Bell class="text-gray-800" />
                                <span
                                    class="absolute -right-0 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                                >
                                    1
                                </span>
                            </div>

                            <!-- Shopping Bag -->
                            <Link
                                :href="route('bag')"
                                class="relative rounded-full p-2"
                            >
                                <ShoppingBag class="text-gray-800" />
                                <span
                                    v-if="bagStore.bagCount > 0"
                                    class="absolute -right-0 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                                >
                                    {{ bagStore.bagCount }}
                                </span>
                            </Link>

                            <!-- Hamburger Menu -->
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    v-if="!showingNavigationDropdown"
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
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

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('home')"
                            :active="route().current('home')"
                        >
                            Products
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <!-- <div class="rounded bg-orange-200 p-2 text-center text-lg">
                    Verify your email
                </div> -->
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="bg-white">
                <slot />
            </main>
            <footer class="flex items-center justify-center bg-white p-6">
                <p class="text-sm">© 2024 Hezekiah</p>
            </footer>
        </div>
    </div>
</template>

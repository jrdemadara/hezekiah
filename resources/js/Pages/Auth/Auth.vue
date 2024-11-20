<script setup>
import { ref, computed, onMounted } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useBagStore } from '@/Stores/bag';
import {
    CalendarCheck,
    CircleAlert,
    CircleX,
    Eye,
    EyeClosed,
    FlaskConical,
    Loader2,
    ShieldPlus,
    Star,
    ThumbsUp,
    Warehouse,
} from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const pageStatus = ref('check-email');
const loginFailed = ref(false);
const registerFailed = ref(false);

const bagStore = useBagStore(); // Access Pinia store

const checkEmailForm = useForm({
    email: '',
});

onMounted(() => {
    // You can also call loadBag if you're manually loading from localStorage
    console.log(registerForm.bag_items);
});

const registerForm = useForm({
    referral_code: '',
    lastname: '',
    firstname: '',
    email: computed(() => checkEmailForm.email),
    password: '',
    bag_items: computed(() => bagStore.items),
});

// Method to check the email
const checkEmail = async () => {
    checkEmailForm.post(route('check-email'), {
        onSuccess: ({ props }) => {
            if (props.status) {
                pageStatus.value = 'login';
            } else {
                pageStatus.value = 'register';
            }
        },
        onError: (e) => {
            // Handle validation errors if needed
            registerFailed.value = true;
        },
    });
};

const register = async () => {
    registerForm.post(route('register'), {
        onSuccess: ({ props }) => {
            console.log(props);
        },
        onError: (e) => {
            // Handle validation errors if needed
            registerFailed.value = true;
        },
    });
};

const loginForm = useForm({
    email: computed(() => checkEmailForm.email),
    password: '',
});

const login = async () => {
    loginForm.post(route('login'), {
        onSuccess: ({ props }) => {
            console.log(props);
        },
        onError: (errors) => {
            if (errors.email) {
                loginFailed.value = true;
            }
        },
    });
};

const formHeader = computed(() => {
    switch (pageStatus.value) {
        case 'check-email':
            return 'Sign in or create an account';
        case 'register':
            return 'Create a new account';
        case 'login':
            return '';
        default:
            return '';
    }
});

// State to track if the password is visible or not
const isPasswordVisible = ref(false);
// Toggle password visibility
const togglePasswordVisibility = () => {
    isPasswordVisible.value = !isPasswordVisible.value;
};

// Google login
const googleLogin = () => {
    window.location.href = '/auth/google';
};

defineProps(['status']);
</script>

<template>
    <Head title="Register" />
    <div
        class="flex flex-col items-center justify-center space-y-4 px-0 sm:h-screen sm:space-y-7 sm:px-32"
    >
        <ApplicationLogo class="w-48" />
        <div
            class="flex flex-col rounded-lg p-4 ring-stone-300 sm:p-8 sm:ring-1"
            :class="{
                'sm:w-2/3': pageStatus !== 'login',
                'sm:w-1/3': pageStatus === 'login',
            }"
        >
            <Link
                v-show="pageStatus === 'check-email'"
                :href="route('home')"
                class="group w-fit cursor-pointer rounded-md px-3 font-semibold text-blue-500 ring-1 ring-transparent transition hover:text-blue-600 focus:outline-none focus-visible:ring-[#FF2D20] sm:py-2"
            >
                Cancel
            </Link>

            <h4
                v-show="pageStatus !== 'check-email'"
                @click="pageStatus = 'check-email'"
                class="group w-fit cursor-pointer rounded-md px-3 font-semibold text-blue-500 ring-1 ring-transparent transition hover:text-blue-600 hover:underline focus:outline-none focus-visible:ring-[#FF2D20] sm:py-2"
                :class="{
                    'py-2': pageStatus !== 'login',
                    'py-0': pageStatus === 'login',
                }"
            >
                Back
            </h4>

            <div
                v-show="pageStatus === 'login' && loginFailed"
                class="mt-4 flex w-full items-center justify-start space-x-1 rounded-lg bg-red-200/90 p-2"
            >
                <CircleAlert class="flex-shrink-0 text-red-600" />
                <span>Invalid credentials</span>
            </div>
            <div
                v-show="pageStatus !== 'login' && registerFailed"
                class="my-4 flex w-full items-center justify-start space-x-1 rounded-lg bg-red-200/90 p-2"
            >
                <CircleX class="flex-shrink-0 text-red-600" />
                <span>Something went wrong! Please try again later.</span>
            </div>

            <!-- <div
                v-if="status"
                class="my-4 flex w-full items-center justify-start space-x-1 rounded-lg bg-red-200/90 p-2"
            >
                <CircleX class="flex-shrink-0 text-red-600" />
                <span> {{ status }}</span>
            </div> -->
            <h4
                v-show="pageStatus"
                class="mt-5 text-center text-2xl font-semibold sm:mt-0"
            >
                {{ formHeader }}
            </h4>
            <p
                v-show="pageStatus == 'check-email'"
                class="mt-2 text-center text-base text-stone-600"
            >
                Enter your email or mobile number to get started. If you already
                have an account, we’ll find it for you.
            </p>

            <div
                class="flex flex-col justify-evenly space-x-5 sm:flex-row"
                :class="{
                    'mt-7': pageStatus !== 'login',
                    'mt-0': pageStatus === 'login',
                }"
            >
                <!-- Left Column -->
                <div
                    class="flex flex-1 flex-col items-center"
                    :class="{
                        'space-y-6 sm:space-y-6': pageStatus !== 'register',
                        'space-y-0 sm:space-y-6': pageStatus === 'register',
                    }"
                >
                    <form
                        v-show="pageStatus === 'check-email'"
                        @submit.prevent="checkEmail"
                        class="w-full"
                    >
                        <div>
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block h-12 w-full"
                                v-model="checkEmailForm.email"
                                required
                                placeholder="Email"
                                autocomplete="username"
                            />
                            <InputError
                                class="mt-2"
                                :message="checkEmailForm.errors.email"
                            />
                        </div>
                        <button
                            class="mt-6 flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] font-bold text-white hover:bg-[#427E00]"
                            :class="{ 'opacity-25': checkEmailForm.processing }"
                            :disabled="checkEmailForm.processing"
                        >
                            <Loader2
                                class="flex-shrink-0 animate-spin"
                                v-if="checkEmailForm.processing"
                            />
                            <span
                                v-text="
                                    checkEmailForm.processing
                                        ? 'Please wait...'
                                        : 'Continue'
                                "
                            ></span>
                        </button>
                    </form>

                    <form
                        v-show="pageStatus === 'register'"
                        @submit.prevent="register"
                        class="w-full"
                    >
                        <div class="flex flex-col">
                            <small>Email Address:</small>
                            <div
                                class="flex items-center justify-start space-x-6"
                            >
                                <span class="font-bold">{{
                                    checkEmailForm.email
                                }}</span>
                                <span
                                    @click="pageStatus = 'check-email'"
                                    class="cursor-pointer text-sm text-blue-500 hover:text-blue-600 hover:underline"
                                    >change</span
                                >
                            </div>
                        </div>
                        <p class="mt-2">
                            We didn’t find an account with that email address.
                            Provide your name and password to create a new
                            account.
                        </p>
                        <div class="my-4">
                            <TextInput
                                id="referral_code"
                                type="text"
                                class="mt-1 block h-12 w-full"
                                v-model="registerForm.referral_code"
                                required
                                placeholder="Referral code"
                            />
                            <InputError
                                class="mt-2"
                                :message="registerForm.errors.referral_code"
                            />
                        </div>
                        <div class="my-4">
                            <div class="flex space-x-2">
                                <div>
                                    <input
                                        id="lastname"
                                        type="text"
                                        class="focus:border-indigo-[#458500] mt-1 block h-12 w-full rounded-lg border-gray-300 capitalize shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
                                        v-model="registerForm.lastname"
                                        required
                                        placeholder="Lastname"
                                        autocomplete="lastname"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="registerForm.errors.lastname"
                                    />
                                </div>

                                <div>
                                    <input
                                        id="name"
                                        type="text"
                                        class="focus:border-indigo-[#458500] mt-1 block h-12 w-full rounded-lg border-gray-300 capitalize shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
                                        v-model="registerForm.firstname"
                                        required
                                        placeholder="Firstname"
                                        autocomplete="firstname"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="registerForm.errors.firstname"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="relative">
                                <!-- Password input field -->
                                <input
                                    v-model="registerForm.password"
                                    :type="
                                        isPasswordVisible ? 'text' : 'password'
                                    "
                                    class="focus:border-indigo-[#458500] h-12 w-full rounded-lg border-gray-300 shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
                                    placeholder="Create a password"
                                />

                                <!-- Eye icon for toggling password visibility -->
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 transform"
                                >
                                    <EyeClosed v-if="!isPasswordVisible" />
                                    <Eye v-if="isPasswordVisible" />
                                </button>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="registerForm.errors.password"
                            />
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <!-- Agreement Checkbox -->
                            <input
                                type="checkbox"
                                id="agreement"
                                v-model="isAgreed"
                                class="h-5 w-5 rounded border-gray-300 text-[#458500] focus:ring-[#458500]"
                            />
                            <label
                                for="agreement"
                                class="text-sm text-gray-700"
                            >
                                I want to receive exclusive deals and Rewards
                                offers by email.
                            </label>
                        </div>
                        <small class="mt-4 line-clamp-2"
                            >By continuing, you’ve read and agree to our
                            <span
                                class="cursor-pointer text-blue-500 hover:underline"
                                >Terms and Conditions</span
                            >
                            and
                            <span
                                class="cursor-pointer text-blue-500 hover:underline"
                                >Privacy Policy</span
                            >
                            .
                        </small>
                        <button
                            class="mt-6 flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] font-bold text-white hover:bg-[#427E00] sm:mt-4"
                            :class="{ 'opacity-25': registerForm.processing }"
                            :disabled="registerForm.processing"
                        >
                            <Loader2
                                class="flex-shrink-0 animate-spin"
                                v-if="registerForm.processing"
                            />
                            <span
                                v-text="
                                    registerForm.processing
                                        ? 'Please wait...'
                                        : 'Create account'
                                "
                            ></span>
                        </button>
                    </form>

                    <form
                        v-show="pageStatus === 'login'"
                        @submit.prevent="login"
                        class="w-full"
                    >
                        <div class="flex flex-col">
                            <small>Email Address:</small>
                            <div
                                class="flex items-center justify-start space-x-6"
                            >
                                <span class="font-bold">{{
                                    loginForm.email
                                }}</span>
                                <span
                                    @click="pageStatus = 'check-email'"
                                    class="cursor-pointer text-sm text-blue-500 hover:text-blue-600"
                                    >change</span
                                >
                            </div>
                        </div>
                        <h4 class="mt-6 text-center text-3xl font-semibold">
                            Welcome back!
                        </h4>
                        <p class="mt-1 text-center leading-5 text-gray-600">
                            Enter your password to sign in to your account.
                        </p>
                        <div class="mt-6">
                            <div class="relative">
                                <!-- Password input field -->
                                <input
                                    v-model="loginForm.password"
                                    :type="
                                        isPasswordVisible ? 'text' : 'password'
                                    "
                                    class="focus:border-indigo-[#458500] h-12 w-full rounded-lg border-gray-300 shadow-sm focus:border-none focus:outline-none focus:ring-[#458500]"
                                    placeholder="Enter your password"
                                />

                                <!-- Eye icon for toggling password visibility -->
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 transform"
                                >
                                    <EyeClosed v-if="!isPasswordVisible" />
                                    <Eye v-if="isPasswordVisible" />
                                </button>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="loginForm.errors.password"
                            />
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div class="flex space-x-2">
                                <!-- Agreement Checkbox -->
                                <input
                                    type="checkbox"
                                    id="agreement"
                                    v-model="isAgreed"
                                    class="h-5 w-5 rounded border-gray-300 text-[#458500] focus:ring-[#458500]"
                                />
                                <label
                                    for="agreement"
                                    class="text-sm text-gray-700"
                                >
                                    Keep me signed in
                                </label>
                            </div>
                            <p class="text-sm text-blue-500 underline">
                                Forgot password?
                            </p>
                        </div>

                        <button
                            class="mt-6 flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] font-bold text-white hover:bg-[#427E00] sm:mt-4"
                            :class="{ 'opacity-25': loginForm.processing }"
                            :disabled="loginForm.processing"
                        >
                            <Loader2
                                class="flex-shrink-0 animate-spin"
                                v-if="loginForm.processing"
                            />
                            <span
                                v-text="
                                    loginForm.processing
                                        ? 'Please wait...'
                                        : 'Sign in'
                                "
                            ></span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div
                        v-show="pageStatus !== 'register'"
                        class="flex w-full items-center"
                    >
                        <div class="my-2 h-px w-full bg-stone-200/80"></div>
                        <span class="mx-4">or</span>
                        <div class="my-2 h-px w-full bg-stone-200/80"></div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div
                        v-show="pageStatus !== 'register'"
                        class="flex w-full flex-col space-y-3"
                    >
                        <button
                            @click="googleLogin"
                            class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                        >
                            <img
                                src="../../../assets/images/google.png"
                                alt="Google Logo"
                                class="mr-2 h-5 w-5"
                            />
                            <span class="flex-grow text-center"
                                >Sign in with Google</span
                            >
                        </button>

                        <button
                            class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                        >
                            <img
                                src="../../../assets/images/facebook.png"
                                alt="Google Logo"
                                class="mr-2 h-5 w-5"
                            />
                            <span class="flex-grow text-center"
                                >Sign in with Facebook</span
                            >
                        </button>
                    </div>

                    <p
                        v-show="pageStatus === 'check-email'"
                        class="mt-4 text-xs sm:mt-0"
                    >
                        By continuing, you’ve read and agree to our
                        <span
                            class="cursor-pointer text-blue-600 hover:underline"
                            >Terms and Conditions</span
                        >
                        and
                        <span
                            class="cursor-pointer text-blue-600 hover:underline"
                        >
                            Privacy Policy.</span
                        >
                    </p>
                </div>

                <!-- Divider between columns -->
                <div
                    v-show="pageStatus !== 'login'"
                    class="mx-2 w-px bg-stone-200/80"
                ></div>

                <!-- Right Column -->
                <div
                    v-show="pageStatus !== 'login'"
                    class="mt-7 flex flex-1 flex-col items-start space-y-4 sm:mt-0 sm:p-2"
                >
                    <h4 class="w-full text-center font-bold">Why Hezekiah?</h4>
                    <div class="flex space-x-2">
                        <Warehouse class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            All temperature-controlled facilities to ensure
                            quality
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <Star class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            Genuine reviews only from verified customers
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <ShieldPlus class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            No third-party sales. Direct from suppliers and
                            authorized distributors
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <FlaskConical class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            Independent lab testing on Hezekiah's House Brands
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <CalendarCheck class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            Expiration dates on product descriptions
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <ThumbsUp class="flex-shrink-0 text-[#458500]" />
                        <p class="text-sm">
                            FDA Approved – Compliant with FDA regulations for
                            dietary supplements.
                        </p>
                    </div>
                    <div
                        class="flex w-full justify-between rounded bg-gray-100 px-5 py-2"
                    >
                        <div class="flex flex-col">
                            <div
                                class="flex items-center space-x-1 text-[#EF6B21]"
                            >
                                <h6 class="text-sm">4.8</h6>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <path
                                        d="M12 2L15 8L22 9L17 14L18 21L12 17L6 21L7 14L2 9L9 8L12 2z"
                                        fill="none"
                                    />
                                    <path
                                        d="M12 2L15 8L22 9L17 14L18 21L12 17L6 21L7 14L2 9L9 8L12 2z"
                                        fill="#EF6B21"
                                        clip-path="inset(0 50% 0 0)"
                                    />
                                </svg>
                            </div>
                            <h6 class="text-xs font-semibold">Hezekiah</h6>
                            <h6 class="text-sm font-semibold">Store Reviews</h6>
                        </div>
                        <div class="flex flex-col">
                            <div
                                class="flex items-center space-x-1 text-[#EF6B21]"
                            >
                                <h6 class="text-sm">4.5</h6>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="#EF6B21"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <polygon
                                        points="12 2 15 8 22 9 17 14 18 21 12 17 6 21 7 14 2 9 9 8"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="h-4 w-4 text-[#EF6B21]"
                                >
                                    <path
                                        d="M12 2L15 8L22 9L17 14L18 21L12 17L6 21L7 14L2 9L9 8L12 2z"
                                        fill="none"
                                    />
                                    <path
                                        d="M12 2L15 8L22 9L17 14L18 21L12 17L6 21L7 14L2 9L9 8L12 2z"
                                        fill="none"
                                        clip-path="inset(0 50% 0 0)"
                                    />
                                </svg>
                            </div>
                            <h6 class="text-xs font-semibold">Online</h6>
                            <h6 class="text-sm font-semibold">
                                Customer Reviews
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

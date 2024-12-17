<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, useForm, router } from '@inertiajs/vue3';
import { Check, Loader2, MoveLeft, Plus } from 'lucide-vue-next';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const { props } = usePage();
const pageState = ref('list');

const addForm = useForm({
    name: null,
    phone: null,
    province: null,
    city: null,
    barangay: null,
    street: null,
    house_number: null,
    zipcode: null,
    landmark: null,
    coordinates: null,
    default: false,
});

const changeDefaultForm = useForm({
    id: null,
    default: false,
});

const save = async () => {
    addForm.post(route('address.save'), {
        onSuccess: () => {
            toast.success('Success!');
        },
        onError: () => {
            toast.error('Failed to add address!');
        },
    });
};

const changeDefault = async (id) => {
    changeDefaultForm.id = id;
    changeDefaultForm.default = true;
    changeDefaultForm.patch(route('address.change'), {
        onSuccess: () => {
            location.reload(); // change this to more efficient way
            // router.reload({ only: ['auth.address'] });
            // router.reload({
            //     only: ['auth.address'],
            //     preserveScroll: true, // Keep the scroll position
            // });
        },
        onError: () => {
            toast.error('Something went wrong, Please try again!');
        },
    });
};
</script>

<template>
    <Head title="Checkout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('checkout.index')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Shipping Address
                </h2>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] flex-col pb-16">
            <div
                v-if="pageState == 'list'"
                @click="pageState = 'new'"
                class="text flex h-12 w-full items-center justify-start space-x-4 bg-[#AFEC70] px-5"
            >
                <Plus />
                <h6>Add a new shipping address.</h6>
            </div>
            <div v-if="pageState == 'list'">
                <div
                    v-for="address in props.auth.address"
                    :key="address.id"
                    class="flex w-full items-center justify-between px-5 py-5 shadow"
                >
                    <div class="flex flex-col">
                        <h2 class="font-semibold capitalize text-black">
                            {{ address.street }}
                        </h2>
                        <p
                            class="mt-2 text-xs font-semibold capitalize text-black"
                        >
                            {{ address.barangay }}, {{ address.city }}
                            {{ address.zipcode }}
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            {{ address.province }}
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            Philippines
                        </p>
                        <p class="text-xs capitalize text-stone-600">
                            {{ address.phone }}
                        </p>
                    </div>
                    <div class="flex">
                        <div
                            v-if="address.is_default"
                            class="flex items-center justify-center space-x-1 rounded-full bg-[#AFEC70] px-5 py-3 text-sm"
                        >
                            <Check :size="18" />
                            <span>Default</span>
                        </div>

                        <label
                            v-if="!address.is_default"
                            class="flex cursor-pointer items-center justify-center space-x-2 rounded-full bg-stone-100 p-4 text-sm"
                        >
                            <input
                                @click="changeDefault(address.id)"
                                type="radio"
                                name="default"
                                :value="address.id"
                            />
                            <span v-if="address.is_default">Default</span>
                            <span v-if="!address.is_default"
                                >Set as default</span
                            >
                        </label>
                    </div>
                </div>
            </div>

            <form
                v-if="pageState == 'new'"
                @submit.prevent="save"
                class="h-52 w-full px-4"
            >
                <h2 class="font-semibold">Add New Address</h2>
                <div class="my-4">
                    <InputLabel value="Consignee" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.name"
                        required
                    />
                    <InputError class="mt-2" :message="addForm.errors.name" />
                </div>
                <div class="my-4">
                    <InputLabel value="Phone Number" />
                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block h-12 w-full"
                        v-model="addForm.phone"
                        required
                    />
                    <InputError class="mt-2" :message="addForm.errors.phone" />
                </div>
                <div class="my-4">
                    <InputLabel value="Province" />
                    <TextInput
                        id="province"
                        type="text"
                        class="h-province mt-1 block w-full capitalize"
                        v-model="addForm.province"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="addForm.errors.province"
                    />
                </div>
                <div class="my-4">
                    <InputLabel value="City" />
                    <TextInput
                        id="city"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.city"
                        required
                    />
                    <InputError class="mt-2" :message="addForm.errors.city" />
                </div>
                <div class="my-4">
                    <InputLabel value="Barangay" />
                    <TextInput
                        id="barangay"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.barangay"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="addForm.errors.barangay"
                    />
                </div>
                <div class="my-4">
                    <InputLabel value="Street" />
                    <TextInput
                        id="street"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.street"
                        required
                    />
                    <InputError class="mt-2" :message="addForm.errors.street" />
                </div>
                <div class="my-4">
                    <InputLabel value="House No./Unit/Building No." />
                    <TextInput
                        id="house_number"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.house_number"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="addForm.errors.house_number"
                    />
                </div>
                <div class="my-4">
                    <InputLabel value="Zipcode" />
                    <TextInput
                        id="zipcode"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.zipcode"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="addForm.errors.zipcode"
                    />
                </div>
                <div class="my-4">
                    <InputLabel value="Landmark" />
                    <TextInput
                        id="landmark"
                        type="text"
                        class="mt-1 block h-12 w-full capitalize"
                        v-model="addForm.landmark"
                        required
                    />
                    <InputError
                        class="mt-2"
                        :message="addForm.errors.landmark"
                    />
                </div>

                <div class="my-6 flex space-x-2">
                    <input
                        type="checkbox"
                        id="default"
                        v-model="addForm.default"
                        class="h-5 w-5 rounded border-gray-300 text-[#458500] focus:ring-[#458500]"
                    />
                    <label for="default" class="font-semibold text-gray-700">
                        Set this address as default.
                    </label>
                </div>

                <div class="mt-8 flex items-center justify-between space-x-2">
                    <button
                        @click="pageState = 'list'"
                        class="flex h-12 w-1/2 items-center justify-center space-x-1 rounded-lg bg-gray-200 hover:bg-gray-300"
                    >
                        Cancel
                    </button>
                    <button
                        class="flex h-12 w-1/2 items-center justify-center space-x-1 rounded-lg bg-[#458500] text-white hover:bg-[#427E00]"
                        :class="{ 'opacity-25': addForm.processing }"
                        :disabled="addForm.processing"
                    >
                        <Loader2
                            class="flex-shrink-0 animate-spin"
                            v-if="addForm.processing"
                        />
                        <span
                            v-text="
                                addForm.processing ? 'Please wait...' : 'Save'
                            "
                        ></span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ScanBarcode, Workflow } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { QrcodeStream } from 'vue3-qrcode-reader';

const form = useForm({
    code: null,
});

// Handle the QR code decoding
const onDecode = (result) => {
    form.code = result;
    saveOrder;
};

// Handle initialization
const onInit = (promise) => {
    promise
        .then(() => {
            //toast.success('Camera permission granted!');
        })
        .catch(() => {
            toast.error('Camera initialization failed.');
        });
};

const saveOrder = () => {
    form.patch(route('order.store'), {
        onSuccess: () => {
            toast.success('Order successfully added.');
            form.reset();
            // setTimeout(() => {
            //     window.location.href = route('dashboard');
            // }, 5000);
        },
        onError: (error) => {
            toast.error(error.message);
        },
    });
};
</script>

<template>
    <Head title="Order" />

    <AuthenticatedLayout>
        <div class="flex h-screen flex-col items-center bg-white py-6">
            <div class="rounded-lg p-2 ring-1 ring-slate-200">
                <ScanBarcode class="mr-0.5 text-black" :size="26" />
            </div>
            <h2 class="mt-4 text-xl font-bold">Product Order</h2>
            <p class="mt-1 text-xs font-light text-slate-700">
                For convenience you can scan the product code.
            </p>
            <div class="mt-4 flex w-3/4 flex-col items-center justify-center">
                <QrcodeStream @decode="onDecode" @init="onInit" />
                <div
                    class="mb-6 mt-6 flex w-full items-center justify-center space-x-2"
                >
                    <div class="h-0.5 flex-grow rounded bg-slate-100"></div>
                    <p class="text-xs font-light text-slate-700">
                        or enter the code manually
                    </p>
                    <div class="h-0.5 flex-grow rounded bg-slate-100"></div>
                </div>
                <form @submit.prevent="saveOrder" class="w-full space-y-5">
                    <TextInput
                        id="code"
                        type="text"
                        class="mt-1 block h-12 w-full uppercase"
                        v-model="form.code"
                        required
                        placeholder="Product Code"
                    />
                    <InputError class="mt-2" :message="form.errors.code" />

                    <button
                        class="flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] text-sm tracking-widest text-white hover:bg-[#427E00]"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <Loader2
                            class="flex-shrink-0 animate-spin"
                            v-if="form.processing"
                        />
                        <span
                            v-text="
                                form.processing ? 'Please wait...' : 'Proceed'
                            "
                        ></span>
                    </button>
                    <Link
                        :href="route('dashboard')"
                        class="inline-flex h-12 w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                    >
                        Order History
                    </Link>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

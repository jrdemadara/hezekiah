<script setup>
import { ref } from 'vue';
import { useClipboard } from '@vueuse/core';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Check, Copy, ScanQrCode } from 'lucide-vue-next';
import QrcodeVue from 'qrcode.vue';

const { props } = usePage();
const qrcode = props.auth.user.code;
const source = ref('');
const value = ref('');

value.value = `https://hezekiahhealth.com/invite?code=${qrcode}`;
source.value = qrcode;

const { text, copy, copied, isSupported } = useClipboard({ source });
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="flex h-screen flex-col items-center bg-white py-6">
            <div class="rounded-lg p-2 ring-1 ring-slate-200">
                <ScanQrCode class="mr-0.5 text-black" :size="26" />
            </div>
            <h2 class="mt-4 text-xl font-bold">Scan QR code</h2>
            <p class="mt-1 text-xs font-light text-slate-700">
                Scan this QR code to refer or invite anyone.
            </p>

            <qrcode-vue
                :value="value"
                level="H"
                :size="250"
                :margin="2"
                class="mt-4 rounded-lg ring-1 ring-slate-200"
                render-as="svg"
            />

            <div class="mt-5 flex w-3/4 flex-col items-center justify-center">
                <div class="flex w-full items-center justify-center space-x-2">
                    <div class="h-0.5 flex-grow rounded bg-slate-100"></div>
                    <p class="text-xs font-light text-slate-700">
                        or copy the code manually
                    </p>
                    <div class="h-0.5 flex-grow rounded bg-slate-100"></div>
                </div>
                <div class="mt-5 flex w-full space-x-2">
                    <div
                        class="flex h-10 w-full items-center justify-start rounded px-2 font-bold uppercase text-black ring-1 ring-slate-200"
                    >
                        {{ qrcode }}
                    </div>
                    <div
                        @click="copy(source)"
                        class="flex h-10 w-10 items-center justify-center rounded text-sm font-semibold text-black ring-1 ring-slate-200"
                    >
                        <span v-if="!copied">
                            <Copy class="mr-0.5 text-slate-800" :size="16"
                        /></span>
                        <span v-else>
                            <Check class="mr-0.5 text-green-600" :size="16"
                        /></span>
                    </div>
                </div>
                <Link
                    :href="route('dashboard')"
                    class="mt-5 flex h-12 w-full items-center justify-center rounded-lg border bg-[#458500] px-2 text-white"
                >
                    I'm done
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

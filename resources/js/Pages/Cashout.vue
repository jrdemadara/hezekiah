<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, useForm } from '@inertiajs/vue3';
import { BadgeCheck, Loader2, MoveLeft } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const { props } = usePage();
const pageState = ref('start');
const cashoutPercentage = ref(null);
const amount = ref(null);
const transactionFee = ref(null);

const totalPoints =
    Number(props.auth.user.referral_points) +
    Number(props.auth.user.order_points);

const form = useForm({
    gross_amount: null,
});

let timeoutId;

const handleInputChange = (event) => {
    const inputAmount = event.target.value;

    if (isNaN(inputAmount) || inputAmount === '') {
        amount.value = null;
        form.gross_amount = null;
        clearTimeout(timeoutId);
    } else {
        clearTimeout(timeoutId);
        cashoutPercentage.value = null;
        timeoutId = setTimeout(() => {
            if (inputAmount >= 500) {
                if (totalPoints >= inputAmount) {
                    const fee = (inputAmount * 10) / 100;
                    const netCashout = inputAmount - fee;
                    amount.value = netCashout.toLocaleString('en-US');
                    transactionFee.value = fee.toLocaleString('en-US');
                    form.gross_amount = inputAmount;
                } else {
                    toast.error('Amount should not exceeds total points.');
                }
            } else {
                toast.error('Minimum cashout amount is 500.');
            }
        }, 2500);
    }
};

const calculatePercentage = (percentage) => {
    cashoutPercentage.value = percentage;
    if (totalPoints >= 500) {
        const grossCashout = (totalPoints * percentage) / 100;
        if (grossCashout >= 500) {
            const fee = (grossCashout * 10) / 100;
            const netCashout = grossCashout - fee;
            amount.value = netCashout.toLocaleString('en-US');
            transactionFee.value = fee.toLocaleString('en-US');
            form.gross_amount = grossCashout;
        } else {
            toast.error('Minimum cashout amount is 500.');
        }
    } else {
        toast.error('Insufficient balance.');
    }
};

const save = async () => {
    if (form.gross_amount >= 500) {
        form.post(route('cashout.save'), {
            onSuccess: () => {
                form.reset();
                pageState.value = 'done';
            },
            onError: (err) => {
                toast.error(err.message);
            },
        });
    }
};
</script>

<template>
    <Head title="Cashout" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Cashout
                </h2>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] select-none flex-col pb-16">
            <form
                v-if="pageState == 'start'"
                @submit.prevent="save"
                class="h-52 w-full px-4"
            >
                <div class="mb-7 mt-4">
                    <div
                        class="border-indigo-[#458500] flex items-end justify-between rounded-lg border border-gray-300 p-2 shadow-sm focus:outline-none focus:ring-[#458500]"
                    >
                        <div class="flex w-2/4">
                            <strong>₱</strong>
                            <input
                                id="amount"
                                type="text"
                                class="w-full border-0 px-1 py-0 capitalize focus:outline-none focus:ring-0"
                                :class="{ 'text-2xl font-bold': amount }"
                                v-model="amount"
                                placeholder="Amount"
                                required
                                @input="handleInputChange"
                            />
                        </div>

                        <small
                            v-if="transactionFee"
                            class="text-xs text-red-500"
                        >
                            -{{ transactionFee }} transaction fee
                        </small>
                    </div>

                    <InputError
                        class="mt-2"
                        :message="form.errors.gross_amount"
                    />

                    <div class="flex justify-between">
                        <p class="mt-1 text-sm text-gray-600">
                            Current Balance:
                            <span class="font-bold text-[#458500]">
                                ₱{{
                                    new Intl.NumberFormat('en-US', {
                                        maximumFractionDigits: 2,
                                    }).format(
                                        parseFloat(
                                            props.auth.user.referral_points ||
                                                0,
                                        ) +
                                            parseFloat(
                                                props.auth.user.order_points ||
                                                    0,
                                            ),
                                    )
                                }}</span
                            >
                        </p>
                        <p class="mt-1 text-xs text-gray-600">
                            10% transaction fee may apply
                        </p>
                    </div>
                    <div class="mt-5 flex flex-col">
                        <div class="flex w-full justify-between space-x-3">
                            <div
                                @click="calculatePercentage(25)"
                                class="flex w-full cursor-pointer justify-center rounded-xl bg-[#AFEC70]/10 p-2 text-sm font-semibold text-[#458500] ring-1 ring-[#5DA414]/20"
                                :class="{
                                    'bg-[#AFEC70]/70': cashoutPercentage == 25,
                                }"
                            >
                                25%
                            </div>
                            <div
                                @click="calculatePercentage(50)"
                                :class="{
                                    'bg-[#AFEC70]/70': cashoutPercentage == 50,
                                }"
                                class="flex w-full cursor-pointer justify-center rounded-xl bg-[#AFEC70]/10 p-2 text-sm font-semibold text-[#458500] ring-1 ring-[#5DA414]/20"
                            >
                                50%
                            </div>
                            <div
                                @click="calculatePercentage(75)"
                                :class="{
                                    'bg-[#AFEC70]/70': cashoutPercentage == 75,
                                }"
                                class="flex w-full cursor-pointer justify-center rounded-xl bg-[#AFEC70]/10 p-2 text-sm font-semibold text-[#458500] ring-1 ring-[#5DA414]/20"
                            >
                                75%
                            </div>
                            <div
                                @click="calculatePercentage(100)"
                                :class="{
                                    'bg-[#AFEC70]/70': cashoutPercentage == 100,
                                }"
                                class="flex w-full cursor-pointer justify-center rounded-xl bg-[#AFEC70]/10 p-2 text-sm font-semibold text-[#458500] ring-1 ring-[#5DA414]/20"
                            >
                                Max
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <p class="mb-2 mt-7 text-center text-sm text-gray-600">
                    Cashout requests will be processed once reviewed by the
                    admin.
                </p>
                <button
                    class="mt-3 flex h-12 w-full items-center justify-center space-x-1 rounded-lg bg-[#458500] text-white hover:bg-[#427E00]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <Loader2
                        class="flex-shrink-0 animate-spin"
                        v-if="form.processing"
                    />
                    <span
                        v-text="form.processing ? 'Please wait...' : 'Continue'"
                    ></span>
                </button>
            </form>
            <div
                v-if="pageState == 'done'"
                class="mt-4 flex flex-col items-center justify-center px-4"
            >
                <div
                    class="flex h-24 w-24 items-center justify-center rounded-full bg-[#AFEC70]/80"
                >
                    <BadgeCheck
                        :size="42"
                        class="flex-shrink-0 text-[#50962a]"
                    />
                </div>
                <strong class="mt-1 text-2xl">Success!</strong>
                <p class="mt-1 text-center text-sm leading-tight text-gray-500">
                    Your request is currently being processed, and you will be
                    notified once it has been approved.
                </p>

                <Link
                    :href="route('dashboard')"
                    class="mt-5 flex h-12 w-1/2 items-center justify-center space-x-1 rounded-lg bg-[#458500] text-white hover:bg-[#427E00]"
                >
                    Ok
                </Link>
                <Link
                    :href="route('cashout.history')"
                    class="mt-3 text-sm tracking-widest text-gray-700 underline"
                >
                    Cashout History
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

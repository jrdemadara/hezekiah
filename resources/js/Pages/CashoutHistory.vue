<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { MoveLeft } from 'lucide-vue-next';

const { props } = usePage();

const cashouts = props.auth.cashout_history;

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
    }).format(date);
};
</script>

<template>
    <Head title="Cashout History" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800"
                >
                    Cashout History
                </h2>
                <Link :href="route('dashboard')">
                    <MoveLeft class="text-transparent" />
                </Link>
            </div>
        </template>

        <div class="flex h-[calc(100vh-64px)] select-none flex-col pb-16">
            <div
                v-for="cashout in cashouts"
                :key="cashout.id"
                class="flex w-full flex-col rounded border-b px-4 py-2 text-gray-600"
            >
                <div class="mb-1 flex justify-between text-sm">
                    <small>Cashout Date</small>
                    <small>{{ formatDate(cashout.created_at) }}</small>
                </div>
                <div class="flex justify-between">
                    <small>Amount</small>
                    <small class="font-bold text-black"
                        >₱
                        {{
                            new Intl.NumberFormat('en-US', {
                                maximumFractionDigits: 2,
                            }).format(parseFloat(cashout.gross_amount))
                        }}</small
                    >
                </div>
                <div class="flex justify-between">
                    <small>Transaction Fee</small>
                    <small class="font-bold text-red-600"
                        >-₱{{
                            new Intl.NumberFormat('en-US', {
                                maximumFractionDigits: 2,
                            }).format(parseFloat(cashout.transaction_fee))
                        }}</small
                    >
                </div>
                <div class="flex justify-between">
                    <small>Net Cashout</small>
                    <small class="font-bold text-green-600"
                        >₱{{
                            new Intl.NumberFormat('en-US', {
                                maximumFractionDigits: 2,
                            }).format(parseFloat(cashout.net_amount))
                        }}</small
                    >
                </div>
                <div class="flex justify-between">
                    <small>Status</small>
                    <small
                        class="p-1 text-xs uppercase"
                        :class="{
                            'text-orange-500': cashout.status === 'pending',
                            'text-green-500': cashout.status === 'approved',
                            'text-blue-500': cashout.status === 'done',
                            'text-red-500': cashout.status === 'denied',
                        }"
                    >
                        {{ cashout.status }}
                    </small>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

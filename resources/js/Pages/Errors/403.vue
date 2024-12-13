<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { OctagonAlert } from 'lucide-vue-next';
</script>

<template>
    <Head title="Error 403" />
    <AuthenticatedLayout>
        <div class="flex h-screen flex-col items-center bg-white py-6">
            <div class="rounded-lg p-2 ring-1 ring-slate-200">
                <OctagonAlert class="mr-0.5" :size="26" />
            </div>
            <h2 class="mt-4 text-xl font-bold">Unauthorized</h2>
            <p class="mt-2 text-center font-light text-slate-700">
                Oops! You don't have the rights to view this resource.
            </p>

            <div class="flex w-3/4 flex-col items-center justify-center">
                <Link
                    :href="route('dashboard')"
                    class="mt-8 flex h-12 w-full items-center justify-center rounded-md border bg-[#458500] px-2 text-white"
                >
                    Go to Dashboard
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

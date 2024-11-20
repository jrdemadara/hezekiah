<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import {} from '@inertiajs/vue3';
import { MoveLeft } from 'lucide-vue-next';
const { props } = usePage();
const code = props.auth.user.code;

const shareInvite = () => {
    const shareData = {
        title: 'Invite Friends',
        text: 'Join me on this amazing platform and enjoy great rewards! Sign up now.',
        url: `https://hezekiahhealth.com/invite?code=${code}`,
    };

    if (navigator.share) {
        navigator
            .share(shareData)
            .then(() => console.log('Shared successfully'))
            .catch((error) => console.error('Error sharing:', error));
    } else {
        alert('Sharing is not supported on your browser.');
    }
};
</script>

<template>
    <Head title="Bag" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Profile
                </h2>
            </div>
        </template>

        <div class="flex flex-col items-center space-x-2">
            <img
                :src="props.auth.user.avatar"
                alt="avatar"
                class="w-14 rounded-full"
            />
            <div class="flex flex-col items-center justify-center">
                <h4 class="text-lg font-semibold capitalize">
                    {{ props.auth.user.firstname }}
                    {{ props.auth.user.lastname }}
                </h4>
                <small> {{ props.auth.user.email }}</small>
                <div
                    @click="copy(source)"
                    class="flex cursor-pointer items-center space-x-1 text-sm font-bold uppercase text-[#458500] active:underline"
                >
                    <span> {{ code }}</span>

                    <span v-if="!copied">
                        <Copy class="mr-0.5" :size="16"
                    /></span>
                    <span v-else> <Check class="mr-0.5" :size="16" /></span>
                </div>
            </div>
            <Link :href="route('logout')" method="post" as="button">
                Log Out
            </Link>
        </div>
    </AuthenticatedLayout>
</template>

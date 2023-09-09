<script setup>
import { Head } from '@inertiajs/vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

defineProps({
    policy: String,
});
</script>

<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 dark:text-slate-100 antialiased">
        <div class="pt-4 bg-gray-100 dark:bg-gray-800">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <JetAuthenticationCardLogo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-slate-900 shadow-md overflow-hidden sm:rounded-lg prose" v-html="policy" />
            </div>
        </div>
    </div>
</template>

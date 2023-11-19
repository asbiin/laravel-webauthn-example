<script setup>
import { useAttrs } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import WebauthnKeys from './Webauthn/WebauthnKeys.vue'

defineProps({
    webauthnKeys: Array,
});

const laravelWebauthnVersion = useAttrs().laravelWebauthn.version;
</script>

<template>
    <AppLayout title="Dashboard" :version="laravelWebauthnVersion">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                    <WebauthnKeys :webauthnKeys="webauthnKeys" />

                </div>
            </div>
        </div>
    </AppLayout>
</template>

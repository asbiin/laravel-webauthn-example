<script setup>
import { ref, useAttrs } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import WebauthnKeys from './Webauthn/WebauthnKeys.vue'
import WebauthnTest from './Webauthn/WebauthnTest.vue';
import JetButton from '@/Jetstream/Button.vue';

defineProps({
    webauthnKeys: Array,
});

const laravelWebauthnVersion = useAttrs().laravelWebauthn.version;

const webauthn = ref('webauthn');
const isSuccess = ref(false);
const processing = ref(false);

const success = (event) => {
  isSuccess.value = true;
  processing.value = false;
};

const start = () => {
  processing.value = true;
  isSuccess.value = false;

  webauthn.value.start();
};

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

                    <div class="p-6 sm:px-20 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
                      <div class="mt-8 text-2xl dark:text-slate-100">
                          Test your keys
                        </div>
                        <div class="mt-3">

                          <p v-if="isSuccess" class="mb-4 text-l text-green-600 dark:text-green-400">
                            Your passkey has been successfully identified!
                          </p>

                          <JetButton v-show="!processing" class="block" @click.prevent="start">
                            Test your passkey
                          </JetButton>

                          <WebauthnTest ref="webauthn" @success="success" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

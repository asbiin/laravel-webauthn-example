<script setup>
import { nextTick, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import WebauthnLogin from '@/Pages/Webauthn/WebauthnLogin.vue';

const recovery = ref(false);

const form = useForm({
  code: '',
  recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
  recovery.value ^= true;

  await nextTick();

  if (recovery.value) {
    recoveryCodeInput.value.focus();
    form.code = '';
  } else {
    codeInput.value.focus();
    form.recovery_code = '';
  }
};

const submit = () => {
  form.post(route('two-factor.login'));
};

defineProps({
  two_factor: Boolean,
  remember: Boolean,
  publicKey: Object,
});
</script>

<template>
    <Head title="Two-factor Confirmation" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <div v-if="publicKey">
            <h1 class="mb-4 max-w-xl text-gray-600 dark:text-gray-400">
                Please confirm access to your account by validating your security key.
            </h1>

            <WebauthnLogin :remember="remember" :publicKey="publicKey" :autofill="false" />
        </div>

        <div v-if="two_factor">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                <template v-if="! recovery">
                    Please confirm access to your account by entering the authentication code provided by your authenticator application.
                </template>

                <template v-else>
                    Please confirm access to your account by entering one of your emergency recovery codes.
                </template>
            </div>


            <JetValidationErrors class="mb-4" />

            <form @submit.prevent="submit">
                <div v-if="! recovery">
                    <JetLabel for="code" value="Code" />
                    <JetInput
                        id="code"
                        ref="codeInput"
                        v-model="form.code"
                        type="text"
                        inputmode="numeric"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="one-time-code"
                    />
                </div>

                <div v-else>
                    <JetLabel for="recovery_code" value="Recovery Code" />
                    <JetInput
                        id="recovery_code"
                        ref="recoveryCodeInput"
                        v-model="form.recovery_code"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="one-time-code"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:text-slate-100 underline cursor-pointer" @click.prevent="toggleRecovery">
                        <template v-if="! recovery">
                            Use a recovery code
                        </template>

                        <template v-else>
                            Use an authentication code
                        </template>
                    </button>

                    <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </JetButton>
                </div>
            </form>
        </div>
    </JetAuthenticationCard>
</template>

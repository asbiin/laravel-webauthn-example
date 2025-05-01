<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import WebauthnLogin from '@/Pages/Webauthn/WebauthnLogin.vue';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    publicKey: Object,
    userless: Boolean,
    autologin: Boolean,
});
const webauthn = ref(true);
const publicKeyRef = ref(null);
const useSecurityKey = computed(() => (publicKeyRef.value !== null && props.autologin) || webauthn.value);

watch(
  () => props.publicKey,
  (value) => {
    publicKeyRef.value = value;
  },
);

onMounted(() => {
  publicKeyRef.value = props.publicKey;
});

const form = useForm({
  email: '',
  password: '',
  remember: true,
});

watch(
  () => props.publicKey,
  (value) => {
    publicKeyRef.value = value;
  },
);

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      remember: form.remember ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    });
};

const useWebauthn = () => {
  form.reset();
  webauthn.value = true;
};
</script>

<template>
    <Head title="Log in" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <JetValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    :autocomplete="'email webauthn'"
                    required
                    autofocus
                />
            </div>

            <div class="mt-4">
                <div class="relative flex">
                    <JetLabel for="password" value="Password" />
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-500 hover:underline absolute right-0">
                        Forgot your password?
                    </Link>
                </div>

                <JetInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <JetCheckbox v-model:checked="form.remember" name="remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-200">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </JetButton>
            </div>
        </form>

        <div class="px-6 block" v-if="userless || publicKeyRef">
          <div class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
            <JetButton v-if="!useSecurityKey" class="block" @click.prevent="useWebauthn">
              Sign in with a passkey
            </JetButton>
          </div>

          <WebauthnLogin v-if="useSecurityKey" :public-key="publicKeyRef" :remember="true" :autofill="true" />
        </div>

    </JetAuthenticationCard>
</template>

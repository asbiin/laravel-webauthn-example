<script setup>
import { ref, watch, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import WebauthnLogin from '@/Pages/Webauthn/WebauthnLogin.vue';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    publicKey: Object,
    userName: String,
});
const webauthn = ref(true);
const publicKeyRef = ref(null);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

watch(() => props.publicKey, (value) => {
  publicKeyRef.value = value;
});

onMounted(() => {
  publicKeyRef.value = props.publicKey;
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const reload = () => {
  publicKeyRef.value = null;
  webauthn.value = true;
  Inertia.reload({only: ['publicKey']});
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

        <div v-if="publicKey && webauthn">
            <div class="mb-4 text-lg text-gray-900 text-center">
                {{ userName }}
            </div>
            <div class="mb-4 max-w-xl text-gray-600 dark:text-gray-400">
                Connect with your security key
            </div>

            <WebauthnLogin :remember="true" :public-key="publicKeyRef" />

            <JetSecondaryButton class="mr-2 mt-4" @click.prevent="webauthn = false">
                Use your password
            </JetSecondaryButton>
        </div>

        <form v-else @submit.prevent="submit">
            <div>
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
            </div>

            <div class="mt-4">
                <JetLabel for="password" value="Password" />
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
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </JetButton>
            </div>

            <div v-if="publicKeyRef" class="block mt-4">
                <JetSecondaryButton class="mr-2" @click.prevent="reload">
                    Use your security key
                </JetSecondaryButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>

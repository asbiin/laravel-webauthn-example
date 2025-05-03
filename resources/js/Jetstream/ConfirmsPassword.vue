<script setup>
import { ref, reactive, nextTick, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import JetButton from './Button.vue';
import JetDialogModal from './DialogModal.vue';
import JetInput from './Input.vue';
import JetInputError from './InputError.vue';
import JetSecondaryButton from './SecondaryButton.vue';
import WebauthnTest from '@/Pages/Webauthn/WebauthnTest.vue';

const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: 'Confirm access',
    },
    content: {
        type: String,
        default: 'For your security, please confirm the access to continue.',
    },
    button: {
        type: String,
        default: 'Confirm',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);
const webauthn = ref('webauthn');
const webauthnEnabled = computed(() => usePage().props.hasKey === true);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        form.processing = false;

        confirm();

    }).catch(error => {
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        passwordInput.value.focus();
    });
};

const confirm = () => {
    closeModal();
    nextTick().then(() => emit('confirmed'));
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <JetDialogModal :show="confirmingPassword" @close="closeModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                {{ content }}

                <div v-if="webauthnEnabled" class="mt-4">
                    <p>
                        When you are ready, authenticate using the button below:
                    </p>

                    <JetButton class="mt-2 block" @click.prevent="webauthn.start()">
                        Confirm your passkey
                    </JetButton>

                    <WebauthnTest ref="webauthn" @success="confirm()" />
                </div>

                <fieldset v-if="webauthnEnabled" class="mt-5 border-t border-gray-300 dark:border-gray-700">
                  <legend class="mx-auto px-4 text-l italic text-gray-600 dark:text-gray-200">
                    Or
                  </legend>
                </fieldset>

                <div class="mt-4">
                    Authenticate using your password:
                    <JetInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="confirmPassword"
                    />

                    <JetInputError :message="form.error" class="mt-2" />
                </div>
            </template>

            <template #footer>
                <JetSecondaryButton @click="closeModal">
                    Cancel
                </JetSecondaryButton>

                <JetButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="confirmPassword"
                >
                    {{ button }}
                </JetButton>
            </template>
        </JetDialogModal>
    </span>
</template>

<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Please validate your Webauthn key
        </div>

        <div v-if="!isSupported">
            {{ notSupportedMessage() }}
        </div>
        <div v-else>
            <jet-input-error :message="authForm.errors.data" class="mt-2" />
            <webauthn-wait-for-key
                :error-message="errorMessage"
                :form="authForm"
                @retry="start()"
            />
        </div>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import WebauthnWaitForKey from './Partials/WebauthnWaitForKey.vue'
    import * as WebAuthn from '../../../../vendor/asbiin/laravel-webauthn/resources/js/webauthn.js';
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetInputError,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            WebauthnWaitForKey,
        },

        props: {
            publicKey: {
                type: Object,
                default: null,
            },
        },

        data() {
            return {
                isSupported: true,
                errorMessage: '',
                webauthn: null,

                authForm: useForm(),
            };
        },

        mounted() {
            this.errorMessage = '';
            this.webauthn = new WebAuthn((name, message) => {
                this.errorMessage = this._errorMessage(name, message);
            });

            if (! this.webauthn.webAuthnSupport()) {
                this.isSupported = false;
                this.errorMessage = this.notSupportedMessage();
            }

            this.start();
        },

        methods: {
            _errorMessage(name, message) {
                switch (name) {
                    case 'InvalidStateError':
                        return 'Unexpected error on login.';
                    case 'NotAllowedError':
                        return 'The operation either timed out or was not allowed.';
                    default:
                        return message;
                }
            },

            notSupportedMessage() {
                switch (this.webauthn.notSupportedMessage()) {
                    case 'not_supported':
                        return 'Your browser doesn’t currently support WebAuthn.';
                    case 'not_secured':
                        return 'WebAuthn only supports secure connections. Please load this page with https scheme.';
                    default:
                        return '';
                }
            },

            start() {
                this.loginWaitForKey(this.publicKey);
            },

            loginWaitForKey(publicKey) {
                this.$nextTick(() => this.webauthn.sign(
                    publicKey,
                    (data) => { this.webauthnLoginCallback(data); }
                ));
            },

            webauthnLoginCallback(data) {
                this.authForm.transform(() => ({
                    data: JSON.stringify(data)
                }))
                .post(route('webauthn.auth'), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: (response) => {
                        if (response.data.callback) {
                            this.$nextTick(() => { window.location = response.data.callback; });
                        }
                    },
                    onError: (error) => {
                        this.errorMessage = error.message ? error.message : error.data.errors.data;
                    }
                });
            },
        }
    })
</script>

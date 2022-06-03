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
            <jet-button class="ml-2" @click="start()" v-show="!processing">
                Retry
            </jet-button>
        </div>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import WebauthnWaitForKey from './Partials/WebauthnWaitForKey.vue'
    import * as WebAuthn from '../../../../vendor/asbiin/laravel-webauthn/resources/js/webauthn.js';
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetInputError,
            JetButton,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            WebauthnWaitForKey,
        },

        emits: ['error'],

        props: {
            publicKey: {
                type: Object,
                default: null,
            },
            email: {
                type: String,
                default: null,
            },
            remember: {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                publicKeyObject: null,
                isSupported: true,
                errorMessage: '',
                webauthn: null,
                processing: false,

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

            if (this.publicKey) {
                this.processing = true;
                this.loginWaitForKey(this.publicKey);
            }
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
                this.processing = true;
                this.errorMessage = '';
                this.webauthnLoginPrepare();
            },

            stop() {
                this.processing = false;
            },

            webauthnLoginPrepare() {
                useForm().transform(() => ({
                    email: this.email,
                    remember: this.remember
                }))
                .post(route('webauthn.auth.options'), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: (response) => {
                        if (response.data !== undefined) {
                            this.loginWaitForKey(response.data.publicKey);
                        } else {
                            this.$nextTick(() => this.loginWaitForKey(response.props.publicKey));
                        }
                    },
                    onError: (error) => {
                        this.error = error.response.data.errors;
                        this.$emit('error', error.response.data.errors);
                        this.stop();
                    }
                });
            },

            loginWaitForKey(publicKey) {
                this.$nextTick(() => this.webauthn.sign(
                    publicKey,
                    (data) => { this.webauthnLoginCallback(data); }
                ));
            },

            webauthnLoginCallback(data) {
                this.authForm
                .transform(() => ({
                    ...data,
                    remember: this.remember ? 'on' : ''
                }))
                .post(route('webauthn.auth'), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: (response) => {
                        this.stop();
                        if (response.data.callback !== undefined) {
                            this.$nextTick(() => { window.location = response.data.callback; });
                        }
                    },
                    onError: (error) => {
                        this.errorMessage = error.email ? error.email : error.data.errors[0];
                        this.stop();
                    }
                });
            },
        }
    })
</script>

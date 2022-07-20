<template>
<div>
    <div v-if="!isSupported">
        {{ notSupportedMessage() }}
    </div>
    <div v-else class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <h1>
            Manage your Webauthn Keys
        </h1>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:block">
                            Last use
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="webauthnKeys.length === 0">
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                            <em>No keys registered yet</em>
                        </td>
                    </tr>
                    <tr v-for="key in webauthnKeys" :key="key.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <strong class="text-sm font-medium text-gray-900">
                                {{ key.name }}
                            </strong>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap time hidden md:block">
                            <span v-if="key.updated_at > key.created_at" class="text-sm text-gray-500">
                                {{ new Date(Date.parse(key.updated_at)).toLocaleString('en-GB', { timeZone: 'UTC' }) }}
                            </span>
                            <em v-else class="text-sm text-gray-500">
                                Never used
                            </em>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a class="pointer text-indigo-600 hover:text-indigo-900" href="" @click.prevent="keyBeingUpdated = key.id">
                                Update
                            </a>
                            ⋅
                            <a class="pointer text-indigo-600 hover:text-indigo-900" href="" @click.prevent="keyBeingDeleted = key.id">
                                Delete
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-2xl">
            <!-- <jet-confirms-password @confirmed="showRegisterModal"> -->
                <jet-button type="button" @click="showRegisterModal">
                    Register a new WebAuthn key
                </jet-button>
            <!-- </jet-confirms-password> -->
        </div>
    </div>

    <webauthn-create-modal :errorMessage="errorMessage" :form="registerForm" @start="start" @register="registerWaitForKey" ref="create" />
    <webauthn-delete-modal :keyid="keyBeingDeleted" @close="keyBeingDeleted = null" />
    <webauthn-update-modal :keyid="keyBeingUpdated" :name="nameUpdate" @close="keyBeingUpdated = null" />
</div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import WebauthnCreateModal from './Partials/WebauthnCreateModal.vue'
    import WebauthnDeleteModal from './Partials/WebauthnDeleteModal.vue'
    import WebauthnUpdateModal from './Partials/WebauthnUpdateModal.vue'
    import * as WebAuthn from '../../../../vendor/asbiin/laravel-webauthn/resources/js/webauthn.js';
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetConfirmationModal,
            JetDialogModal,
            JetLabel,
            JetInput,
            JetDangerButton,
            JetSecondaryButton,
            JetConfirmsPassword,
            JetButton,
            WebauthnCreateModal,
            WebauthnDeleteModal,
            WebauthnUpdateModal,
        },

        props: {
            webauthnKeys: {
                type: Array,
                default: () => [],
            },
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

                registerForm: useForm({
                    name: '',
                }),

                keyBeingDeleted: null,
                keyBeingUpdated: null,
            };
        },

        computed: {
            nameUpdate() {
                return this.keyBeingUpdated ? this.webauthnKeys.find(key => key.id === this.keyBeingUpdated).name : '';
            },
        },

        mounted() {
            this.errorMessage = '';
            this.webauthn = new WebAuthn((name, message) => {
                this.$refs.create.stop();
                this.errorMessage = this._errorMessage(name, message);
            });

            if (! this.webauthn.webAuthnSupport()) {
                this.isSupported = false;
                this.errorMessage = this.notSupportedMessage();
            }

            if (this.publicKey) {
                this.showRegisterModal();
                this.registerWaitForKey(this.publicKey);
            }
        },

        methods: {
            _errorMessage(name, message) {
                switch (name) {
                    case 'InvalidStateError':
                        return 'This key is already registered. It’s not necessary to register it again.';
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

            showRegisterModal() {
                this.errorMessage = '';
                this.$refs.create.open();
            },

            start() {
                this.errorMessage = '';
                this.registerForm.clearErrors();
            },

            registerWaitForKey(publicKey) {
                this.$nextTick(() => this.webauthn.register(
                    publicKey,
                    (data) => { this.webauthnRegisterCallback(data); }
                ));
            },

            webauthnRegisterCallback(data) {
                this.$refs.create.stop();

                this.registerForm.transform((form) => ({
                    ...form,
                    ...data
                }))
                .post(route('webauthn.store'), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.$refs.create.close();
                    },
                    onError: (error) => {
                        this.errorMessage = error.email ? error.email : error.data.errors.webauthn;
                    }
                });
            },
        }
    })
</script>

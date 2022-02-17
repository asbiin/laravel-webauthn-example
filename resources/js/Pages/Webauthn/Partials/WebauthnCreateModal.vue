<template>
    <jet-dialog-modal :show="step" @close="close()">
        <template #title>
            Register a new key
        </template>

        <template #content>
            <jet-input-error :message="form.errors.register" class="mt-2" />

            <div class="mt-4" v-show="!processing || form.errors.name">
                <jet-label for="name" value="Key name" />
                <jet-input type="text" class="mt-1 block w-3/4" placeholder="Name"
                    id="name" ref="name"
                    v-model="form.name"
                    required
                    @keyup.enter="start()" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-4" v-show="step === '2'">
                <webauthn-wait-for-key
                    :error-message="error"
                    :form="form"
                    @retry="start()"
                />
            </div>
        </template>

        <template #footer>
            <jet-secondary-button @click="close()">
                Cancel
            </jet-secondary-button>

            <jet-button class="ml-2" @click="start()" :class="{ 'opacity-25': processing }" :disabled="processing">
                Submit
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import WebauthnWaitForKey from './WebauthnWaitForKey.vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetDialogModal,
            JetLabel,
            JetInput,
            JetInputError,
            JetSecondaryButton,
            JetButton,
            WebauthnWaitForKey,
        },

        props: {
            errorMessage: {
                type: String,
                default: '',
            },
            form: {
                type: Object,
                default: null,
            },
        },

        emits: ['start', 'register'],

        data() {
            return {
                step: null,
                pending: false,
                error: '',
            };
        },

        mounted() {
            this.error = this.errorMessage
        },

        watch: {
            errorMessage(value) {
                this.error = value
            },
        },

        computed: {
            processing: function () {
                return this.pending || this.form.processing;
            }
        },

        methods: {
            open() {
                this.step = '1';

                this.stop();
                this.error = '';
                this.form.reset();

                this.$nextTick(() => this.$refs.name.focus(), 250);
            },

            close() {
                this.step = null;
                this.stop();
            },

            start() {
                this.pending = true;
                this.step = '2';
                this.error = '';

                this.$emit('start')
                useForm().post(route('webauthn.store.options'), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess:  (response) => {
                        if (response.data !== undefined) {
                            this.registerWaitForKey(response.data.publicKey);
                        } else {
                            this.$nextTick(() => this.registerWaitForKey(response.props.publicKey));
                        }
                    },
                    onError: (error) => {
                        this.stop();
                        this.error = error.response.data.errors[0];
                    }
                });
            },

            registerWaitForKey(publicKey) {
                if (this.step === '2') {
                    this.$emit('register', publicKey);
                }
            },

            stop() {
                this.pending = false;
            },
        }
    })
</script>

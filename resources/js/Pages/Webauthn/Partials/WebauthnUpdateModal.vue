<template>
    <jet-confirmation-modal :show="keyid" @close="$emit('close')">
        <template #title>
            Update a key
        </template>

        <template #content>
            <div class="mt-4">
                <jet-label for="name2" value="Key name" />
                <jet-input type="text" class="mt-1 block w-3/4" placeholder="Name"
                    id="name2" ref="name"
                    v-model="form.name"
                    required
                    @keyup.enter="updateKey" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template #footer>
            <jet-secondary-button @click="$emit('close')">
                Cancel
            </jet-secondary-button>

            <jet-button class="ml-2" @click="updateKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Update
            </jet-button>
        </template>
    </jet-confirmation-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetLabel,
            JetInput,
            JetInputError,
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
        },

        props: {
            keyid: {
                type: Number,
                default: 0,
            },
            name: {
                type: String,
                default: null,
            },
        },

        emits: ['close'],

        data() {
            return {
                form: useForm({
                    name: '',
                }),
            };
        },

        watch: {
            name(value) {
                this.form.name = value;
            },
        },

        methods: {
            updateKey() {
                this.form.put(route('webauthn.update', this.keyid), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.$emit('close')),
                })
            },
        }
    })
</script>

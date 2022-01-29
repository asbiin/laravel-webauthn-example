<template>
    <jet-confirmation-modal :show="keyid" @close="$emit('close')">
        <template #title>
            Delete a new key
        </template>

        <template #content>
            Are you sure you would like to delete this key?
        </template>

        <template #footer>
            <jet-secondary-button @click="$emit('close')">
                Cancel
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click="deleteKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Delete
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        components: {
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
        },

        props: {
            keyid: {
                type: Number,
                default: 0,
            },
        },

        emits: ['close'],

        data() {
            return {
                form: useForm(),
            };
        },

        methods: {
            deleteKey() {
                this.form.delete(route('webauthn.destroy', this.keyid), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.$emit('close')),
                })
            },
        }
    })
</script>

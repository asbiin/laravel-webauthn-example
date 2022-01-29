<template>
    <div>
        <div v-if="errorMessage !== ''" class="form-error-message mb3">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <p class="font-bold">
                    {{ errorMessage }}
                </p>
                <jet-button class="mt-4" @click="$emit('retry')">
                    Retry
                </jet-button>
            </div>
        </div>
        <template v-else>

            <div v-if="form.processing" class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                <p class="font-bold">
                    Validating key…
                </p>
            </div>
            <template v-else-if="!form.hasErrors">
                <div class="text-center">
                    <img src="https://ssl.gstatic.com/accounts/strongauth/Challenge_2SV-Gnubby_graphic.png"
                        alt="Insert your security key"
                    />
                </div>

                <div class="mb-4 text-sm text-gray-600">
                    <p class="font-bold">
                        Insert your security key.
                    </p>
                    <p>
                        If your security key has a button, press it.
                        <br />
                        If it does not, remove it and insert it again.
                    </p>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetButton from '@/Jetstream/Button.vue'

    export default defineComponent({
        components: {
            JetButton,
        },

        props: {
            errorMessage: {
                type: String,
                default: '',
            },
            form: {
                type: Object,
                default: null,
            }
        },

        emits: [
            'retry'
        ],
    })
</script>

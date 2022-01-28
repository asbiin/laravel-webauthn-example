<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Please validate your Webauthn key
        </div>

        <webauthn :method="'login'" :public-key="publicKey" />
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import Webauthn from './Webauthn.vue'

    export default defineComponent({
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            Webauthn,
        },

        props: {
            publicKey: {
                type: Object,
                default: null,
            },
        },

    })
</script>

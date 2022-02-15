<template>
    <Head title="Log in" />

    <webauthn-login v-if="webauthn"
        :email="form.email" :remember="form.remember"
        ref="webauthn" />
    <jet-authentication-card v-else>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit(false)">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4" v-if="!passwordless">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </jet-button>

                <jet-button class="ml-4" @click.prevent="submit(true)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in without password
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import WebauthnLogin from '../Webauthn/WebauthnLogin.vue';

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Link,
            WebauthnLogin,
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                passwordless: false,
                webauthn: false,
                error: '',
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit(passwordless) {
                this.passwordless = passwordless;
                if (passwordless) {
                    this.webauthn = true;
                    this.$nextTick(() => this.$refs.webauthn.start());
                } else {
                    this.form
                        .transform(data => ({
                            ...data,
                            remember: data.remember ? 'on' : ''
                        }))
                        .post(this.route('login'), {
                            onFinish: () => this.form.reset('password'),
                        });
                }
            },
        }
    })
</script>

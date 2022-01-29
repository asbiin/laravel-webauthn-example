<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <welcome />
                    <webauthn-keys :webauthnKeys="webauthnKeys" />
                </div>
            </div>
        </div>

        <template #footer>
            <div class="text-center text-sm text-gray-600">
                <p>
                    Sources: <a class="underline" href="https://github.com/asbiin/laravel-webauthn-example" rel="noopener" target="_blank">asbiin/laravel-webauthn-example</a>
                </p>
                <p>
                    See also <a class="underline" href="https://github.com/asbiin/laravel-webauthn" rel="noopener" target="_blank">asbiin/laravel-webauthn</a> package
                </p>
            </div>
        </template>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import WebauthnKeys from './Webauthn/WebauthnKeys.vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            AppLayout,
            Welcome,
            Link,
            WebauthnKeys,
        },

        props: {
            webauthnKeys: {
                type: Array,
                default: function () {
                    return [];
                }
            },
        },

    })
</script>

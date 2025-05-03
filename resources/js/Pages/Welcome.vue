<script setup>
import { useAttrs } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';
import Sponsor from '@/Layouts/Sponsor.vue';
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const laravelWebauthnVersion = useAttrs().laravelWebauthn.version;
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-800 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 dark:text-gray-300 underline">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-300 underline">
                    Log in
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-gray-300 underline">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
              <ApplicationLogo />
            </div>

            <div class="mt-8 bg-white dark:bg-slate-900 overflow-hidden shadow-sm dark:shadow-gray-700 sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500 dark:text-slate-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-lg leading-7 font-semibold"><a href="https://github.com/asbiin/laravel-webauthn-example" class="underline text-gray-900 dark:text-slate-100 dark:text-white">laravel-webauthn-example</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                This is a demo application for <a class="underline" href="https://github.com/asbiin/laravel-webauthn" rel="noopener noreferrer" target="_blank">asbiin/laravel-webauthn</a>.<br/>
                                <Link v-if="canRegister" :href="route('register')" class="underline">Register</Link> and add your Webauthn key.<br/>
                            </div>
                        </div>

                        <div class="m-8">
                            <Link :href="route('login')" class="text-lg text-gray-100 dark:text-gray-900 dark:text-slate-100 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-900 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 active:bg-gray-900 dark:active:bg-gray-100 focus:outline-hidden focus:border-gray-900 dark:focus:border-gray-100 dark:border-slate-900 focus:ring-3 focus:ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition mr-4">
                                Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')" class="text-lg text-gray-100 dark:text-gray-900 dark:text-slate-100 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-900 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 active:bg-gray-900 dark:active:bg-gray-100 focus:outline-hidden focus:border-gray-900 dark:focus:border-gray-100 dark:border-slate-900 focus:ring-3 focus:ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition mr-4">
                                Register
                            </Link>
                        </div>

                        <div>
                            <div class="text-teal-600 dark:text-teal-400 text-xl">
                                <strong>Accounts are automatically deleted after 24h.</strong>
                            </div>
                        </div>

                        <div class="text-sm mt-8">
                            <Sponsor />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center bg-gray-100 dark:bg-gray-800 sm:items-center">
        <Footer :version="laravelWebauthnVersion" />
    </div>

</template>

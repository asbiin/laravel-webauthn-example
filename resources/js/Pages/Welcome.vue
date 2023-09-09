<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sponsor from '@/Layouts/Sponsor.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
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
                <!-- https://github.com/webauthn-open-source/webauthn-logos -->
                <svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="140" height="38" viewBox="0 0 140 38" version="1.1" id="svg25" sodipodi:docname="webauthn-color-cutout.svg" inkscape:version="0.92.4 (5da689c313, 2019-01-14)"><metadata id="metadata29"><rdf:RDF><cc:Work rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="1366" inkscape:window-height="705" id="namedview27" showgrid="false" inkscape:zoom="6.1785714" inkscape:cx="70" inkscape:cy="19" inkscape:window-x="-8" inkscape:window-y="-8" inkscape:window-maximized="1" inkscape:current-layer="svg25" /><defs id="defs5"><clipPath id="clip-webauthn-color"><rect width="1418" height="379" id="rect2" x="0" y="0" /></clipPath></defs><circle id="Ellipse_3" data-name="Ellipse 3" cx="578.39301" cy="-107.37801" r="42.307999" style="opacity:0;fill:#820000" /><g id="g1017" transform="matrix(0.09501152,0,0,0.09501152,2.570101,0.65327549)"><g id="g1001"><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path923" d="m 78.2,95.9 85.2,213 52.8,-141.9 52.2,141.9 89.4,-213 h -33 L 270.2,229.7 218,86.3 164,229.7 111.2,95.9 Z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path925" d="m 457.14688,253.4 c -2.70001,4.8 -6.60001,9.9 -11.70001,13.8 -6,4.5 -12,6.3 -20.1,6.3 -7.19999,0 -15.6,-1.2 -22.5,-8.1 -6.3,-6.3 -10.19999,-16.5 -10.8,-25.5 h 90.60001 v -3.6 c 0,-13.2 -1.5,-36.6 -17.70001,-53.4 -7.5,-7.8 -20.4,-16.5 -40.5,-16.5 -18,0 -31.79999,6.6 -42.29999,17.1 -13.20001,13.5 -20.10001,32.4 -20.10001,51.9 0,18.9 6.60001,36.3 18.60001,48.3 12,12 25.79999,16.2 42.59999,16.2 13.5,0 27,-3 37.2,-9.6 8.1,-5.1 15.9,-13.5 21.3,-23.1 z m -63,-37.5 c 1.5,-6.3 4.79999,-12.3 9,-16.2 3.89999,-3.9 10.5,-8.1 20.69999,-8.1 9.6,0 15.9,3.6 19.80001,7.2 4.5,4.2 7.79999,10.2 9,17.1 z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path929" d="m 816.1625,296 h 33 l -94.8,-211.8 -99.6,211.8 h 33 l 21.9,-48.3 h 85.5 z m -93.9,-77.1 30.9,-67.8 29.7,67.8 z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path931" d="m 869.0375,169.7 v 73.2 c 0,16.2 1.8,29.7 13.5,41.4 11.4,11.7 27,15.6 39.9,15.6 14.1,0 29.7,-4.2 41.1,-15.6 11.7,-11.7 13.5,-25.2 13.5,-41.4 v -73.2 h -29.4 V 242 c 0,7.5 -0.3,16.2 -5.4,22.8 -3.9,5.1 -10.8,8.7 -19.2,8.7 -8.4,0 -15.3,-3.6 -19.2,-8.7 -5.1,-6.6 -5.4,-15.3 -5.4,-22.8 v -72.3 z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path933" d="m 1067.15,196.7 v -27 h -22.5 v -46.2 h -29.4 v 46.2 h -12.3 v 27 h 12.3 V 296 h 29.4 v -99.3 z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path935" d="m 1087.0063,296 h 29.4 v -60.9 c 0,-16.2 1.8,-25.8 6,-31.8 3.9,-5.7 11.7,-10.5 21.6,-10.5 7.5,0 13.2,3 16.5,6.6 5.7,6.3 5.7,14.4 5.7,21.9 V 296 h 29.4 v -77.4 c 0,-9 -0.3,-23.7 -8.1,-35.1 -3.6,-5.1 -14.1,-17.1 -37.2,-17.1 -7.5,0 -21.3,1.2 -33.9,14.1 V 77.3 h -29.4 z" /><path style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" id="path937" d="m 1232.6047,296 h 29.4 v -60.6 c 0,-40.8 13.5,-42.6 26.7,-42.6 11.1,0 23.1,0.9 23.1,33 V 296 h 29.4 v -77.4 c 0,-19.5 -3,-28.2 -7.8,-35.1 -3.9,-5.4 -13.8,-17.1 -37.2,-17.1 -7.2,0 -21.6,0.9 -34.2,15 v -11.7 h -29.4 z" /><g id="g989"><path id="path927" d="M 513.37305,77.300781 V 215.91602 c 4.37106,1.83038 7.88717,5.45778 9.49609,10.08398 h 18.57422 c 2.85562,-22.42547 18.42912,-33.19922 35.23047,-33.19922 21.3,0 35.09961,18 35.09961,40.5 0,21.9 -13.79961,40.19922 -35.09961,40.19922 -16.43471,0 -31.69043,-10.54721 -35.02149,-31.21289 h -0.20703 v -1.38281 c -0.16441,-1.24064 -0.27924,-2.52162 -0.35742,-3.83203 h -18.2207 c -1.60923,4.62494 -5.12437,8.25316 -9.49414,10.08398 V 296 h 29.40039 v -13.19922 c 12.9,15 28.80078,17.09961 37.80078,17.09961 39,0 61.19922,-32.40117 61.19922,-67.20117 0,-41.1 -28.2,-66.29883 -61.5,-66.29883 -9.3,0 -25.5,2.4 -37.5,18 V 77.300781 Z" style="font-weight:500;font-size:300px;font-family:FuturaPT-Medium, 'Futura PT';fill:#3423a6" inkscape:connector-curvature="0" /><path id="Path_26" d="M 575.22656,186.80078 A 45.113,45.113 0 0 0 532.15234,226 h 3.0586 a 41.878,41.878 0 0 1 11.78125,-23.73633 31.253,31.253 0 0 0 4.95703,3.72656 A 56.832,56.832 0 0 0 546.30078,226 h 0.67578 a 56.211,56.211 0 0 1 5.61524,-19.63086 44.326,44.326 0 0 0 13.82812,5.04688 149.232,149.232 0 0 0 -1.34375,20.19336 h -2.99609 v 0.72656 h 2.99609 a 149.223,149.223 0 0 0 1.34375,20.19336 44.366,44.366 0 0 0 -13.83008,5.04492 56.206,56.206 0 0 1 -4.99218,-15.28711 h -0.72852 a 56.828,56.828 0 0 0 5.08008,15.66797 31.253,31.253 0 0 0 -4.95899,3.72851 41.878,41.878 0 0 1 -11.89257,-24.61132 h -2.98243 a 45.113,45.113 0 0 0 89.86915,-5.16797 45.064,45.064 0 0 0 -3.68946,-17.88868 45.113,45.113 0 0 0 -43.06836,-27.21484 z m 1.68555,2.9336 c 0.836,0 1.66714,0.0252 2.49414,0.0742 1.956,1.823 3.80039,5.73875 5.27539,11.34375 a 88.635,88.635 0 0 1 1.91406,9.61133 55.186,55.186 0 0 1 -19.36523,0 88.659,88.659 0 0 1 1.91406,-9.61133 c 1.481,-5.604 3.3233,-9.51975 5.2793,-11.34375 0.82572,-0.0479 1.65359,-0.0742 2.48828,-0.0742 z m -3.56641,0.15039 c -1.85,2.107 -3.52839,5.86798 -4.90039,11.08398 a 89.318,89.318 0 0 0 -1.92578,9.66211 43.575,43.575 0 0 1 -13.59961,-4.9707 c 3.682,-6.888 8.77335,-12.15053 14.65235,-14.89454 a 42.3,42.3 0 0 1 5.77343,-0.88085 z m 7.12891,0.002 a 42.252,42.252 0 0 1 5.82617,0.90234 c 5.858,2.751 10.93061,8.0001 14.59961,14.8711 a 43.566,43.566 0 0 1 -13.59961,4.9707 89.068,89.068 0 0 0 -1.92773,-9.66211 c -1.372,-5.215 -3.04844,-8.97503 -4.89844,-11.08203 z m -15.94531,1.6875 a 33.173,33.173 0 0 0 -9.52539,9.2793 44,44 0 0 0 -2.7168,4.42578 30.548,30.548 0 0 1 -4.78125,-3.59961 41.932,41.932 0 0 1 17.02344,-10.10547 z m 24.82226,0.0273 a 42.19,42.19 0 0 1 16.98047,10.07617 30.465,30.465 0 0 1 -4.78906,3.59961 43.63,43.63 0 0 0 -2.7168,-4.42773 33.206,33.206 0 0 0 -9.47461,-9.24805 z m 17.4961,10.6543 a 42.175,42.175 0 0 1 8.78906,12.97852 41.889,41.889 0 0 1 3.44531,16.375 H 607.8418 a 56.844,56.844 0 0 0 -5.95899,-25.61915 31.236,31.236 0 0 0 4.96485,-3.73437 z m -5.60352,4.11328 a 56.224,56.224 0 0 1 5.87109,25.24024 h -18.35546 a 149.391,149.391 0 0 0 -1.34375,-20.19336 44.372,44.372 0 0 0 13.82812,-5.04688 z m -34.11133,5.17969 a 55.783,55.783 0 0 0 19.57031,0 148.6,148.6 0 0 1 1.33204,20.06055 h -4.74805 c -0.12651,0.24581 -0.24942,0.49351 -0.40234,0.72656 h 5.15039 a 148.6,148.6 0 0 1 -1.33594,20.06445 55.783,55.783 0 0 0 -19.57031,0 148.773,148.773 0 0 1 -1.32813,-20.06445 h 5.15625 c -0.12949,-0.23675 -0.24652,-0.47788 -0.34765,-0.72656 h -4.8086 a 148.774,148.774 0 0 1 1.33203,-20.06055 z m 21.62696,20.78711 h 18.35546 a 56.207,56.207 0 0 1 -5.87109,25.23828 44.369,44.369 0 0 0 -13.82812,-5.04492 149.382,149.382 0 0 0 1.34375,-20.19336 z m 19.07617,0 h 11.24218 A 41.887,41.887 0 0 1 606.8418,261.6836 h -0.006 a 31.253,31.253 0 0 0 -4.95899,-3.72851 56.841,56.841 0 0 0 5.95899,-25.61914 z m -29.61914,19.86718 a 55.186,55.186 0 0 1 8.3789,0.8418 88.615,88.615 0 0 1 -1.91406,9.60938 c -1.47,5.605 -3.31353,9.5207 -5.26953,11.3457 l -0.006,-0.002 c -0.828,0.048 -1.65914,0.0742 -2.49414,0.0742 -0.835,0 -1.66519,-0.0262 -2.49219,-0.0742 -1.956,-1.823 -3.80039,-5.73875 -5.27539,-11.34375 a 88.64,88.64 0 0 1 -1.91406,-9.60938 55.186,55.186 0 0 1 10.98633,-0.8418 z m -11.69532,0.97461 a 89.366,89.366 0 0 0 1.92579,9.66211 c 1.373,5.216 3.05139,8.97604 4.90039,11.08204 a 42.14,42.14 0 0 1 -5.76758,-0.87891 h -0.004 c -5.879,-2.744 -10.9723,-8.00753 -14.65429,-14.89453 a 43.517,43.517 0 0 1 13.5996,-4.97071 z m 20.78516,0 a 43.541,43.541 0 0 1 13.59961,4.97071 c -3.682,6.887 -8.7743,12.15053 -14.6543,14.89453 a 42.093,42.093 0 0 1 -5.76757,0.87891 h -0.004 c 1.851,-2.106 3.52939,-5.86604 4.90039,-11.08204 a 89.115,89.115 0 0 0 1.92578,-9.66211 z m -35.01953,5.34766 a 43.9,43.9 0 0 0 2.7168,4.42773 33.155,33.155 0 0 0 9.52929,9.2793 41.924,41.924 0 0 1 -17.02539,-10.10742 h -0.004 a 30.588,30.588 0 0 1 4.7832,-3.59961 z m 49.25,0.002 a 30.59,30.59 0 0 1 4.78516,3.59961 41.924,41.924 0 0 1 -17.02149,10.10743 h -0.008 a 33.154,33.154 0 0 0 9.52734,-9.2793 43.538,43.538 0 0 0 2.7168,-4.42774 z" style="fill:#3423a6" inkscape:connector-curvature="0" /></g></g><g id="g985"><path style="fill:#f18805" inkscape:connector-curvature="0" d="m 556.564,229 h -35.945 a 13.93,13.93 0 1 0 0,5.072 h 23.827 v 5.216 h 5.072 v -5.216 h 4.492 v 7.679 h 5.071 V 229 Z m -49.585,11.362 a 8.794,8.794 0 1 1 8.793,-8.793 8.8,8.8 0 0 1 -8.793,8.795 z" data-name="Path 27" id="Path_27" /><path style="fill:#f18805" inkscape:connector-curvature="0" d="m 581,228.892 a 4.027,4.027 0 1 0 -6,3.511 l -2.056,10.51 H 581 l -2.057,-10.51 A 4.023,4.023 0 0 0 581,228.892 Z" data-name="Path 29" id="Path_29" /></g></g></svg>
            </div>

            <div class="mt-8 bg-white dark:bg-slate-900 overflow-hidden shadow dark:shadow-gray-700 sm:rounded-lg">
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
                            <Link :href="route('login')" class="text-lg text-gray-100 dark:text-gray-900 dark:text-slate-100 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-900 uppercase tracking-widest hover:bg-gray-700 hover:dark:bg-gray-300 active:bg-gray-900 active:dark:bg-gray-100 focus:outline-none focus:border-gray-900 focus:dark:border-gray-100 dark:border-slate-900 focus:ring focus:ring-gray-300 focus:dark:ring-gray-700 disabled:opacity-25 transition mr-4">
                                Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')" class="text-lg text-gray-100 dark:text-gray-900 dark:text-slate-100 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-900 uppercase tracking-widest hover:bg-gray-700 hover:dark:bg-gray-300 active:bg-gray-900 active:dark:bg-gray-100 focus:outline-none focus:border-gray-900 focus:dark:border-gray-100 dark:border-slate-900 focus:ring focus:ring-gray-300 focus:dark:ring-gray-700 disabled:opacity-25 transition mr-4">
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
</template>

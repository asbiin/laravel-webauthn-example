<script setup>
import JetButton from '@/Jetstream/Button.vue';

defineProps({
  errorMessage: String,
  form: Object,
});

defineEmits(['retry']);

</script>

<template>
    <div>
        <div v-if="errorMessage !== ''" class="form-error-message mb3">
            <div class="bg-red-100 border border-red-400 px-4 py-3 rounded relative" role="alert">
                <p class="font-bold text-red-700">
                    {{ errorMessage }}
                </p>
                <JetButton class="mt-4" @click="$emit('retry')">
                    Retry
                </JetButton>
            </div>
        </div>
        <template v-else>

            <div v-if="form.processing" class="bg-teal-100 border-t-4 border-teal-500 rounded-b mb-4 px-4 py-8 shadow-md dark:shadow-gray-700 flex" role="alert">
                <div class="text-teal-800 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        stroke="currentColor" class="w-8 h-8" viewBox="-50 0 700 600">
                        <g transform="matrix(42.857142857142854,0,0,42.857142857142854,0,0)">
                          <g>
                            <polyline points="5.62 7.38 11.5 1.5 13.5 3.5"></polyline>
                            <line x1="9.25" y1="3.75" x2="11" y2="5.5"></line>
                            <circle cx="3.5" cy="9.5" r="3"></circle>
                          </g>
                        </g>
                    </svg>
                </div>
                <p class="font-bold text-2xl text-teal-900">
                    Validating key…
                </p>
            </div>
            <div v-else-if="!form.hasErrors" class="bg-indigo-100 border-t-4 border-indigo-500 rounded-b mb-4 px-4 py-8 shadow-md dark:shadow-gray-700 flex">
                <div class="text-indigo-800 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        stroke="currentColor" class="w-8 h-8" viewBox="-50 0 700 600">
                        <g transform="matrix(42.857142857142854,0,0,42.857142857142854,0,0)">
                          <g>
                            <polyline points="5.62 7.38 11.5 1.5 13.5 3.5"></polyline>
                            <line x1="9.25" y1="3.75" x2="11" y2="5.5"></line>
                            <circle cx="3.5" cy="9.5" r="3"></circle>
                          </g>
                        </g>
                    </svg>
                </div>
                <p class="font-bold text-2xl text-indigo-900">
                    Waiting for key…
                </p>
            </div>
        </template>
    </div>
</template>

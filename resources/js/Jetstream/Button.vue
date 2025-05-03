<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-900 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 active:bg-gray-900 dark:active:bg-slate-100 focus:outline-hidden focus:border-gray-900 dark:focus:border-gray-100 focus:ring-3 focus:ring-gray-300 dark:focus:ring-gray-400 disabled:opacity-25 transition">
        <slot />
    </button>
</template>

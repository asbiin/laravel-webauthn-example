<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-xs hover:text-gray-500 dark:hover:text-slate-500 focus:outline-hidden focus:border-blue-300 dark:focus:border-blue-700 focus:ring-3 focus:ring-blue-200 dark:focus:ring-blue-800 active:text-gray-800 dark:active:text-slate-200 active:bg-gray-50 dark:active:bg-gray-900 disabled:opacity-25 transition">
        <slot />
    </button>
</template>

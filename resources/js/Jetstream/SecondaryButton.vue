<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:dark:text-slate-500 focus:outline-none focus:border-blue-300 focus:dark:border-blue-700 focus:ring focus:ring-blue-200 focus:dark:ring-blue-800 active:text-gray-800 active:dark:text-slate-200 active:bg-gray-50 active:dark:bg-gray-900 disabled:opacity-25 transition">
        <slot />
    </button>
</template>

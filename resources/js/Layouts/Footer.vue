<script setup>
defineProps({
  version: String,
});
</script>

<template>
    <footer v-if="version" class="mt-4 text-center text-xs dark:text-gray-100">
        <span><a class="underline" href="https://github.com/asbiin/laravel-webauthn" rel="noopener noreferrer" target="_blank">asbiin/laravel-webauthn</a> version: {{ version }}</span>
    </footer>
</template>

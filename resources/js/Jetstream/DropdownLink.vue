<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: String,
  as: String,
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 text-left hover:bg-gray-100 hover:dark:bg-gray-900 focus:outline-none focus:bg-gray-100 transition">
            <slot />
        </button>

        <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:dark:bg-gray-900 focus:outline-none focus:bg-gray-100 transition">
            <slot />
        </a>

        <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:dark:bg-gray-900 focus:outline-none focus:bg-gray-100 transition">
            <slot />
        </Link>
    </div>
</template>

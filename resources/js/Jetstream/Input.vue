<script setup>
import { onMounted, ref } from 'vue';

defineProps({
  modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="dark:bg-gray-900 border-gray-300 dark:border-gray-600 focus:border-indigo-300 dark:focus:border-indigo-700 focus:ring-3 focus:ring-indigo-200/50 rounded-md shadow-xs dark:shadow-gray-700"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>

<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});

const close = () => {
  emit('close');
};
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
      <div>
        <div class="bg-white dark:bg-gray-800">
          <div>

            <!-- title -->
            <div class="flex items-center px-4 pt-5 pb-4">
              <div class="mx-auto shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-6 sm:w-6">
                <svg class="h-4 w-4 text-red-600 dark:text-red-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>

              <h3 class="ml-3 text-lg text-gray-900 dark:text-gray-100">
                <slot name="title" />
              </h3>
            </div>

            <!-- modal content -->
            <div class="px-4 pb-4 sm:pb-4 text-center sm:text-left">
              <div class="mt-2 text-gray-600 dark:text-gray-400">
                <slot name="content" />
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-900/70 text-right">
          <slot name="footer" />
        </div>
      </div>
    </Modal>
</template>

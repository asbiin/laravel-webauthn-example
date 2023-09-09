<script setup>
import { ref, nextTick, computed, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import RegisterKey from '@/Pages/Webauthn/Partials/RegisterKey.vue';
import DeleteKeyModal from '@/Pages/Webauthn/Partials/DeleteKeyModal.vue';
import UpdateKey from '@/Pages/Webauthn/Partials/UpdateKey.vue';
import { webAuthnNotSupportedMessage } from '@/methods.js';
import { startRegistration, browserSupportsWebAuthn } from '@simplewebauthn/browser';

const props = defineProps({
  webauthnKeys: Array,
  publicKey: Object,
});

const isSupported = ref(true);
const errorMessage = ref('');

const register = ref(false);
const registerForm = useForm({
  name: '',
});
const keyBeingDeleted = ref(null);
const keyBeingUpdated = ref(null);

const nameUpdate = computed(() =>
  keyBeingUpdated.value > 0 ? props.webauthnKeys.find((key) => key.id === keyBeingUpdated.value).name : '',
);

onMounted(() => {
  if (!browserSupportsWebAuthn()) {
    isSupported.value = false;
    errorMessage.value = webAuthnNotSupportedMessage();
  }

  if (props.publicKey) {
    showRegisterModal();
    nextTick().then(() => registerWaitForKey(props.publicKey));
  }
});

const _errorMessage = (name, message) => {
  switch (name) {
    case 'InvalidStateError':
      return 'This key is already registered. It’s not necessary to register it again.';
    case 'NotAllowedError':
      return 'The operation either timed out or was not allowed.';
    default:
      return message;
  }
};

const showRegisterModal = () => {
  errorMessage.value = '';
  register.value = true;
};

const start = () => {
  errorMessage.value = '';
  registerForm.clearErrors();
};

const registerWaitForKey = (publicKey) => {
  startRegistration(publicKey)
    .then((data) => webauthnRegisterCallback(data))
    .catch((error) => {
      errorMessage.value = _errorMessage(error.name, error.message);
    });
};

const webauthnRegisterCallback = (data) => {
  registerForm
    .transform((form) => ({
      ...form,
      ...data,
    }))
    .post(route('webauthn.store'), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        register.value = false;
        registerForm.reset();
      },
      onError: (error) => {
        errorMessage.value = error.email ?? error.data.errors.webauthn;
      },
    });
};
</script>

<template>
    <div>

      <div v-if="!isSupported">
        {{ webAuthnNotSupportedMessage() }}
      </div>

      <div v-else-if="register" class="p-6 sm:px-20 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
        <RegisterKey
          :error-message="errorMessage"
          :form="registerForm"
          :name="registerForm.name"
          @update:name="registerForm.name = $event"
          @start="start"
          @stop="register = false"
          @register="registerWaitForKey" />
      </div>

      <div v-else-if="keyBeingUpdated > 0" class="p-6 sm:px-20 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
        <UpdateKey :keyid="keyBeingUpdated" :name-update="nameUpdate" @close="keyBeingUpdated = null" />
      </div>

      <div v-else class="p-6 sm:px-20 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <h1 class="font-semibold text-xl text-gray-900 dark:text-slate-100 leading-tight mb-8">
                Manage your Webauthn Keys
            </h1>

            <p v-if="webauthnKeys.length > 0" class="dark:text-gray-100 text-lg bg-teal-50 dark:bg-teal-800 border-t-2 border-teal-200 dark:border-teal-500 rounded-b mb-4 px-4 py-8 shadow-md">
                Try <Link :href="route('logout')" method="post" class="underline">logging out</Link> and logging back in without password, just using your registered key!
            </p>

            <div class="shadow dark:shadow-gray-700 overflow-hidden border-b border-gray-200 dark:border-gray-800 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider hidden md:block">
                                Last use
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-200 dark:divide-slate-800">
                        <tr v-if="webauthnKeys.length === 0">
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                                <em>No keys registered yet</em>
                            </td>
                        </tr>
                        <tr v-for="key in webauthnKeys" :key="key.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <strong class="text-sm font-medium text-gray-900 dark:text-slate-100">
                                    {{ key.name }}
                                </strong>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap time hidden md:block">
                                <span class="text-sm text-gray-500 dark:text-gray-300">
                                    {{ key.last_active }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <JetSecondaryButton class="pointer text-indigo-400 dark:text-indigo-600 hover:text-indigo-600 hover:dark:text-indigo-400" href="" @click="keyBeingUpdated = key.id">
                                    Update
                                </JetSecondaryButton>
                                <!-- <JetConfirmsPassword @confirmed="keyBeingDeleted = key.id">
                                    <JetSecondaryButton class="ml-2 pointer text-indigo-400 dark:text-indigo-600 hover:text-indigo-600 hover:dark:text-indigo-400">
                                        Delete
                                    </JetSecondaryButton>
                                </JetConfirmsPassword> -->
                                <JetSecondaryButton class="ml-2 pointer text-indigo-400 dark:text-indigo-600 hover:text-indigo-600 hover:dark:text-indigo-400" @click="keyBeingDeleted = key.id">
                                    Delete
                                </JetSecondaryButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-2xl">
                <!-- <JetConfirmsPassword @confirmed="showRegisterModal">
                    <JetButton type="button">
                        Register a new key
                    </JetButton>
                </JetConfirmsPassword> -->
                <JetButton type="button" @click="showRegisterModal">
                    Register a new key
                </JetButton>
            </div>
        </div>
      <DeleteKeyModal :keyid="keyBeingDeleted" @close="keyBeingDeleted = null" />
    </div>
</template>

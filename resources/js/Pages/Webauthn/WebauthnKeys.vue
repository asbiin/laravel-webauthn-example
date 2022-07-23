
<script setup>
import { ref, nextTick, computed, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/inertia-vue3';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import RegisterKey from './Partials/RegisterKey.vue';
import DeleteKeyModal from './Partials/DeleteKeyModal.vue';
import UpdateKey from './Partials/UpdateKey.vue';
import WebAuthn from '../../webauthn.js';

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

const nameUpdate = computed(() => keyBeingUpdated.value > 0 ? props.webauthnKeys.find(key => key.id === keyBeingUpdated.value).name : '');

onMounted(() => {
  errorMessage.value = '';

  if (! webauthn.webAuthnSupport()) {
    isSupported.value = false;
    errorMessage.value = notSupportedMessage();
  }

  if (props.publicKey) {
    showRegisterModal();
    registerWaitForKey(props.publicKey);
  }
});

const webauthn = new WebAuthn((name, message) => {
  errorMessage.value = _errorMessage(name, message);
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

const notSupportedMessage = () => {
  switch (webauthn.notSupportedMessage()) {
  case 'not_supported':
    return 'Your browser doesn’t currently support WebAuthn.';
  case 'not_secured':
    return 'WebAuthn only supports secure connections. Please load this page with https scheme.';
  default:
    return '';
  }
};

const showRegisterModal = () => {
  errorMessage.value = '';
  register.value  = true;
};

const start = () => {
  errorMessage.value = '';
  registerForm.clearErrors();
};

const registerWaitForKey = (publicKey) => {
  nextTick().then(() => webauthn.register(
    publicKey,
    (data) => { webauthnRegisterCallback(data); }
  ));
};

const webauthnRegisterCallback = (data) => {
  registerForm.transform((form) => ({
    ...form,
    ...data
  }))
    .post(route('webauthn.store'), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        register.value = false;
        registerForm.reset();
      },
      onError: (error) => {
        errorMessage.value = error.email ? error.email : error.data.errors.webauthn;
      }
    });
};
</script>

<template>
    <div>
        <div v-if="!isSupported">
            {{ notSupportedMessage() }}
        </div>

        <div v-else-if="register" class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <RegisterKey :errorMessage="errorMessage" :form="registerForm"
                :name="registerForm.name" @update:name="registerForm.name = $event"
                @start="start" @stop="register = false" @register="registerWaitForKey"
            />
        </div>

        <div v-else-if="keyBeingUpdated > 0" class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <UpdateKey :keyid="keyBeingUpdated" :name-update="nameUpdate" @close="keyBeingUpdated = null" />
        </div>

        <div v-else class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <h1 class="font-semibold text-xl text-gray-900 leading-tight mb-8">
                Manage your Webauthn Keys
            </h1>

            <p v-if="webauthnKeys.length > 0" class="text-lg bg-teal-50 border-t-2 border-teal-200 rounded-b mb-4 px-4 py-8 shadow-md">
                Try <Link :href="route('logout')" method="post" class="underline">logging out</Link> and logging back in without password, just using your registered key!
            </p>

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:block">
                                Last use
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="webauthnKeys.length === 0">
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                                <em>No keys registered yet</em>
                            </td>
                        </tr>
                        <tr v-for="key in webauthnKeys" :key="key.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <strong class="text-sm font-medium text-gray-900">
                                    {{ key.name }}
                                </strong>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap time hidden md:block">
                                <span class="text-sm text-gray-500">
                                    {{ key.last_active }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <JetSecondaryButton class="pointer text-indigo-400 hover:text-indigo-600" href="" @click="keyBeingUpdated = key.id">
                                Update
                            </JetSecondaryButton>
                            <!-- <JetConfirmsPassword @confirmed="keyBeingDeleted = key.id"> -->
                                <JetSecondaryButton class="ml-2 pointer text-indigo-400 hover:text-indigo-600" @click="keyBeingDeleted = key.id">
                                    Delete
                                </JetSecondaryButton>
                            <!-- </JetConfirmsPassword> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-2xl">
                <!-- <JetConfirmsPassword @confirmed="showRegisterModal"> -->
                    <JetButton type="button" @click="showRegisterModal">
                        Register a new key
                    </JetButton>
                <!-- </JetConfirmsPassword> -->
            </div>
        </div>

        <DeleteKeyModal :keyid="keyBeingDeleted" @close="keyBeingDeleted = null" />
    </div>
</template>

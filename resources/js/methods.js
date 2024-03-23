import { computed } from 'vue';

/**
 * Get the message in case WebAuthn is not supported.
 *
 * @return {string}
 */
export const webAuthnNotSupportedMessage = computed(() =>
  !window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1'
    ? 'WebAuthn only supports secure connections. Please load this page with https scheme.'
    : 'Your browser doesn’t currently support WebAuthn.'
);

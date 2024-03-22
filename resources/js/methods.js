import { computed } from 'vue';
import emitter from 'tiny-emitter/instance';

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

/**
 * Flash a message.
 *
 * @param {string} message
 * @param {string} level
 */
export const flash = (message, level = 'success') => {
  emitter.emit('flash', { message, level });
};

export default {
  flash,
  $on: (...args) => emitter.on(...args),
  $once: (...args) => emitter.once(...args),
  $off: (...args) => emitter.off(...args),
};

import * as Sentry from '@sentry/vue';
import { BrowserTracing } from '@sentry/browser';
import { createTransport } from '@sentry/core';
import { router } from '@inertiajs/vue3';

let activated = false;

const myTransport = (options) => {
  const makeRequest = async (request) => {
    const requestOptions = {
      data: request.body,
      url: options.url,
      method: 'POST',
      referrerPolicy: 'origin',
      headers: options.headers,
      ...options.fetchOptions,
    };
    return axios(requestOptions).then((response) => ({
      statusCode: response.status,
      headers: response.headers,
    }));
  };
  return createTransport({ bufferSize: options.bufferSize }, makeRequest);
};

const install = (app, options) => {
  if (options.dsn !== undefined) {
    Sentry.init({
      app,
      dsn: options.dsn,
      tunnel: '/sentry/tunnel',
      environment: options.environment || null,
      release: options.release || '',
      sendDefaultPii: options.sendDefaultPii || false,
      tracesSampleRate: options.tracesSampleRate || 0.0,
      integrations: options.tracesSampleRate > 0 ? [new BrowserTracing()] : [],
      transport: myTransport,
    });
    app.mixin(Sentry.createTracingMixins({ trackComponents: true }));
    activated = true;
  }
};

const setContext = (vm) => {
  if (activated && typeof vm.$page !== 'undefined') {
    if (vm.$page.props.auth.user) {
      Sentry.setUser({ id: vm.$page.props.auth.user.id });
    }
    Sentry.setTag('page.component', vm.$page.component);
    vm.$once(
      'hook:destroyed',
      router.on('success', (event) => {
        Sentry.setTag('page.component', event.detail.page.component);
      }),
    );
  }
};

export const sentry = {
  install,
  setContext,
};

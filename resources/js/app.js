import './bootstrap';
import '../css/app.css';

import { createSSRApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import { sentry } from './sentry';
import methods from './methods';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  progress: {
    color: '#4B5563',
  },
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createSSRApp({
      render: () => h(App, props),
      mounted() {
        this.$nextTick(() => {
          sentry.setContext(this);
        });
      },
    })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(sentry, props.initialPage.props.sentry)
      .mixin({ methods: Object.assign({ route }, methods) })
      .mount(el);
  },
});

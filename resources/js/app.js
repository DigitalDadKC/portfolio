import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { MotionPlugin } from '@vueuse/motion';
import '@mdi/font/css/materialdesignicons.css'
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const appName = import.meta.env.VITE_APP_NAME || 'Portfolio';

const vuetify = createVuetify({
    components,
    directives,
  })

createInertiaApp({
    title: (title) => `${appName} - ` + title,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) })
        VueApp.config.globalProperties.$filters = {
            formatCurrency(value) {
                return (value) ? value.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }) : ''
            }
        }
        VueApp
            .component('Link', Link)
            .use(plugin)
            .use(ZiggyVue)
            .use(MotionPlugin)
            .use(vuetify)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

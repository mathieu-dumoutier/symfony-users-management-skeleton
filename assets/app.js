import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import './styles/app.css';
import { createI18n } from 'vue-i18n';
import fr from './locales/messages.fr.json';
import en from './locales/messages.en.json';

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

document.addEventListener('vue:before-mount', (event) => {
  const {
    componentName, // The Vue component's name
    component, // The resolved Vue component
    props, // The props that will be injected to the component
    app, // The Vue application instance
  } = event.detail;

  const i18n = createI18n({
    legacy: false,
    locale: 'fr',
    fallbackLocale: 'fr',
    messages: { fr, en },
  });

  app.use(i18n);
});

document.addEventListener('DOMContentLoaded', () => {
  if ('dark' === localStorage['ea/colorScheme']) {
    document.body.classList.add('dark');
    return;
  }

  if ('auto' === localStorage['ea/colorScheme']) {
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (prefersDarkScheme) {
      document.body.classList.add('dark');
    }
  }
});

document.addEventListener('click', (event) => {
  if (event.target.matches('[data-ea-color-scheme]')) {
    const colorScheme = event.target.getAttribute('data-ea-color-scheme');
    document.body.classList[colorScheme === 'dark' ? 'add' : (colorScheme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'add' :'remove')]('dark');
  }
});

import { defineNuxtPlugin, navigateTo, useAuthStore } from '#imports';
import { ofetch } from 'ofetch';

export default defineNuxtPlugin((nuxtApp) => {
  console.log('Hello from my plugin 1!');

  globalThis.$fetch = ofetch.create({
    async onRequest({ request, options }) {
    },

    async onResponseError({ request, response, options }) {
      if (response.status === 401) {
        await useAuthStore().loggedOut();
        navigateTo('/login');
      }
    },
  });
});

import { defineNuxtPlugin, navigateTo, useAuthStore } from '#imports';
import { ofetch } from 'ofetch';

export default defineNuxtPlugin(() => {

  globalThis.$fetch = ofetch.create({

    async onRequest({ request, options }) {
      if (request.toString() !== 'login') {
        options.credentials = 'include';
      }
    },

    async onResponseError({ response }) {
      if (response.status === 401) {
        await useAuthStore().setLoggedOut();
        navigateTo('/login');
      }
    },
  });
});

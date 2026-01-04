import { defineNuxtPlugin, navigateTo, useAuthStore } from '#imports';
import { ofetch } from 'ofetch';

export default defineNuxtPlugin(() => {

  globalThis.$fetch = ofetch.create({

    async onRequest({ request, options }) {
      // Ensure the response is in JSON-LD format
      options.headers = new Headers(options.headers || {});
      options.headers.set('Accept', 'application/ld+json');

      // Include credentials for all requests except login
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

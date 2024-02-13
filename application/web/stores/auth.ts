import { defineStore, ref, useApiAuth } from '#imports';

export const useAuthStore = defineStore('auth', () => {

  const authenticated = ref(null);

  async function isAuthenticated() {
    console.log('isAuthenticated', authenticated.value);
    if (authenticated.value === null ) {
      authenticated.value = await useApiAuth().isAuthenticated();
    }

    return authenticated;
  }

  function authenticate() {
    authenticated.value = true;
  }

  return { isAuthenticated, authenticate };
});

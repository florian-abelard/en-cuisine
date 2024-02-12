import { computed, defineStore, ref, type Ref, useApiAuth } from '#imports';

export const useAuthStore = defineStore('auth', () => {

  const authenticated: Ref<boolean> = ref(null);

  const isAuthenticated = computed(async () => {
    if (authenticated.value === null) {
      authenticated.value = await useApiAuth().isAuthenticated();
    }

    return authenticated;
  });

  function authenticate() {
    authenticated.value = true;
  }

  return { isAuthenticated, authenticate };
});

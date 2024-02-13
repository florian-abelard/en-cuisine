import { computed, defineStore, ref, useApiAuth, type Ref } from '#imports';

export const useAuthStore = defineStore('auth', () => {

  const authenticated: Ref<boolean | null> = ref(null);

  const isAuthenticated = computed(async (): Promise<boolean> => {
    if (authenticated.value === null) {
      authenticated.value = await useApiAuth().isAuthenticated();
    }

    return authenticated.value;
  });

  function authenticate() {
    authenticated.value = true;
  }

  return { isAuthenticated, authenticate };
});

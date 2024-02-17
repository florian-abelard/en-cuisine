import { computed, defineStore, ref, useApiAuth, type Ref } from '#imports';

export const useAuthStore = defineStore('auth', () => {

  const authenticated: Ref<boolean | null> = ref(null);

  const isAuthenticated = computed(async (): Promise<Ref<boolean>> => {
    if (authenticated.value === null) {
      authenticated.value = await useApiAuth().isAuthenticated();
    }

    return authenticated;
  });

  function setLoggedIn() {
    authenticated.value = true;
  }

  function setLoggedOut() {
    authenticated.value = false;
  }

  return { authenticated, isAuthenticated, setLoggedIn, setLoggedOut };
});

import { computed, defineStore, ref, type Ref } from '#imports';

export const useAuthStore = defineStore('parking', () => {

  const _authenticated: Ref<boolean> = ref(null);

  const authenticated = computed(() => {
    if (_authenticated.value === null) {
      // return await AuthService.login(values.username, values.password);
    }

    return _authenticated;
  });

  function authenticate() {
    _authenticated.value = true;
  }

  return { authenticated, authenticate };
});

import { useAuthStore, useRuntimeConfig } from "#imports";

export const useApiAuth = () => {

  const config = useRuntimeConfig();
  const authStore = useAuthStore();

  return {
    login: async (username: string, password: string): Promise<void> => {
      await $fetch('/login', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        headers: {
          'Content-Type': 'application/json',
        },
        body: {
          username,
          password,
        },
      });

      authStore.setLoggedIn();
    },

    logout: async (): Promise<void> => {
      try {
        await $fetch('/logout', {
          method: 'POST',
          baseURL: config.public.apiBaseUrl,
        });
      } catch (e) {
        authStore.setLoggedOut();
      }
    },

    isAuthenticated: async (): Promise<boolean> => {
      try {
        const response = await $fetch('/is-authenticated', {
          method: 'GET',
          baseURL: config.public.apiBaseUrl,
        });

        const authenticated = !!response['authenticated'];
        authStore.setAuthenticated(authenticated);

        return authenticated;
      } catch (e) {
        authStore.setLoggedOut();

        return false;
      }
    },
  };
};

import { useAuthStore, useRuntimeConfig } from "#imports";

export const useApiAuth = () => {

  const config = useRuntimeConfig();
  const useAuth = useAuthStore();

  return {
    login: async (username: string, password: string): Promise<void> => {
      await $fetch('/login', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: {
          username,
          password,
        },
      });

      useAuth.loggedIn();
    },

    logout: async (): Promise<void> => {
      try {
        await $fetch('/logout', {
          method: 'POST',
          baseURL: config.public.apiBaseUrl,
          credentials: 'include',
        });
      } catch (e) {
        useAuth.loggedOut();
      }
    },

    isAuthenticated: async (): Promise<boolean> => {
      try {
        const response = await $fetch('/is-authenticated', {
          method: 'GET',
          baseURL: config.public.apiBaseUrl,
          credentials: 'include',
        });

        return !!response['authenticated'];
      } catch (e) {
        return false;
      }
    },
  };
};

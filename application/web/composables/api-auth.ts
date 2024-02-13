import { useRuntimeConfig } from "#imports";

export const useApiAuth = () => {

  const config = useRuntimeConfig();

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
    },

    logout: async (): Promise<void> => {
      await $fetch('/logout', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
      });
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

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
        await $fetch('/authenticated', {
          method: 'GET',
          baseURL: config.public.apiBaseUrl,
        });

        return true;
      } catch (e) {
        return false;
      }
    },
  };
};

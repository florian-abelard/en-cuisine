import { useRuntimeConfig } from "#imports";
import { PaginatedResult } from "~/models/paginated-result";
import type { Categorie } from "~/models/categorie";

export const useApiCategorie = () => {

  const config = useRuntimeConfig();

  return {
    findAll: async (): Promise<Categorie[]> => {
      const response = await $fetch('/categories', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return response['hydra:member'];
    },

    findById: async (id: string): Promise<Categorie> => {
      return await $fetch(`/categories/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });
    },

    create: async (payload: Categorie): Promise<void> => {
      await $fetch('/categories', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    update: async (id: string, payload: Categorie): Promise<void> => {
      await $fetch(`/categories/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },
  };
};

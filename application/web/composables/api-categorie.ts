import { useRuntimeConfig } from "#imports";
import { PaginatedResult } from "~/models/paginated-result";
import type { Categorie } from "~/models/categorie";

export const useApiCategorie = () => {

  const config = useRuntimeConfig();

  return {
    findByPaginated: async (page: number): Promise<PaginatedResult<Categorie>> => {
      const response = await $fetch('/categories', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
        params: { page },
      });

      return new PaginatedResult(
        response['hydra:member'],
        response['hydra:totalItems'],
      );
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

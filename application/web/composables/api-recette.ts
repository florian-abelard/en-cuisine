import { useRuntimeConfig } from "#imports";
import { PaginatedResult } from "~/models/paginated-result";
import type { Recette } from "~/models/recette";

export const useApiRecette = () => {

  const config = useRuntimeConfig();

  return {
    findByPaginated: async (page: number): Promise<PaginatedResult<Recette>> => {
      const response = await $fetch('/recettes', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
        params: { page },
      });

      return new PaginatedResult(
        response['hydra:member'],
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Recette> => {
      const response: Recette = await $fetch(`/recettes/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return response;
    },

    create: async (payload: Recette): Promise<void> => {
      await $fetch('/recettes', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    update: async (id: string, payload: Recette): Promise<void> => {
      await $fetch(`/recettes/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },
  };
};

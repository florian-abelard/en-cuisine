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
  };
};

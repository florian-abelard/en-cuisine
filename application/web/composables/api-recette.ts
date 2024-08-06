import { useApiMedia, useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";
import { PaginatedResult } from "~/models/paginated-result";
import type { Recette } from "~/models/recette";

export const useApiRecette = () => {

  const config = useRuntimeConfig();

  const normalizer = (recette: Recette): Recette => {
    recette.image = recette.image ? useApiMedia().normalize(recette.image as Media) : null;

    return recette;
  };

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
      const recette: Recette = await $fetch(`/recettes/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return normalizer(recette);
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

import { useApiMedia, useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";
import { PaginatedResult } from "~/models/paginated-result";
import type { Recette } from "~/models/recette";
import { formatQueryParams } from "~/utils/api-utils";

interface RecetteFilters {
  [key: string]: unknown;
}

export const useApiRecette = () => {

  const config = useRuntimeConfig();

  const normalizer = (recette: Recette): Recette => {
    recette.image = recette.image ? useApiMedia().normalize(recette.image as Media) : null;

    return recette;
  };

  return {
    findByPaginated: async (page: number, filters: RecetteFilters): Promise<PaginatedResult<Recette>> => {
      const params = formatQueryParams(filters);
      params.append('page', page.toString());

      const response = await $fetch('/recettes', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
        params: Object.fromEntries(params.entries()),
      });

      return new PaginatedResult(
        response['hydra:member'].map(normalizer),
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

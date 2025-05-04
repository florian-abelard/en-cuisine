import { useApiMedia, useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";
import { PaginatedResult } from "~/models/paginated-result";
import type { Recette } from "~/models/recette";
import type { Shape } from "~/models/types/shape.type";
import { defaultNormalizer, formatQueryParams } from "~/utils/api-utils";

interface RecetteFilters {
  [key: string]: unknown;
}

export const useApiRecette = () => {

  const config = useRuntimeConfig();
  const denormalize = (recette: Recette): Recette => {
    recette.image = recette.image ? useApiMedia().denormalize(recette.image as Media) : null;

    return recette;
  };
  const normalize = (recette: Recette): Shape<Recette> => {

    return Object.keys(recette).reduce(
      (normalized, key) => {
        return Object.assign(normalized, { [key]: defaultNormalizer(recette[key]) });
      },
      {} as Shape<Recette>,
    );
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
        response['hydra:member'].map(denormalize),
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Recette> => {
      const recette: Recette = await $fetch(`/recettes/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return denormalize(recette);
    },

    create: async (payload: Recette): Promise<void> => {
      await $fetch('/recettes', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: normalize(payload),
      });
    },

    update: async (id: string, payload: Recette): Promise<void> => {
      await $fetch(`/recettes/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: normalize(payload),
      });
    },
  };
};

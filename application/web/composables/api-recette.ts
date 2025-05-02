import { useApiMedia, useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";
import { PaginatedResult } from "~/models/paginated-result";
import type { Recette } from "~/models/recette";
import type { Shape } from "~/models/types/shape.type";
import { formatQueryParams } from "~/utils/api-utils";

interface RecetteFilters {
  [key: string]: unknown;
}

export const useApiRecette = () => {

  const config = useRuntimeConfig();

  const denormalizer = (recette: Recette): Recette => {
    recette.image = recette.image ? useApiMedia().denormalize(recette.image as Media) : null;

    return recette;
  };

  const normalizer = (recette: Recette): Shape<Recette> => {

    return Object.keys(recette).reduce(
      (normalized, key) => {
        const value = recette[key];
        normalized[key] = value;

        if (key === 'image') {
          normalized[key] = value ? value['@id'] : null;
        }
        if (key === 'categorie') {
          normalized[key] = value ? value['@id'] : null;
        }
        if (key === 'etiquettes') {
          normalized[key] = value.map((etiquette) => etiquette['@id']);
        }
        if (key === 'ingredients') {
          normalized[key] = value.map((ingredient) => ingredient['@id']);
        }

        return normalized;
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
        response['hydra:member'].map(denormalizer),
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Recette> => {
      const recette: Recette = await $fetch(`/recettes/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return denormalizer(recette);
    },

    create: async (payload: Recette): Promise<void> => {
      await $fetch('/recettes', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: normalizer(payload),
      });
    },

    update: async (id: string, payload: Recette): Promise<void> => {
      await $fetch(`/recettes/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: normalizer(payload),
      });
    },
  };
};

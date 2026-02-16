import { useRuntimeConfig } from "#imports";
import type { RealisationFilters } from "~/models/filters/realisation-filters";
import { PaginatedResult } from "~/models/paginated-result";
import type { Realisation } from "~/models/realisation";
import type { Shape } from "~/models/types/shape.type";
import { defaultNormalizer, formatQueryParams } from "~/utils/api-utils";

export const useApiRealisation = () => {

  const config = useRuntimeConfig();

  const denormalize = (realisation: Realisation): Realisation => {
    realisation.date = realisation.date ? realisation.date.substring(0, 10) : null;

    return realisation;
  };

  const normalize = (realisation: Realisation): Shape<Realisation> => {
    return Object.keys(realisation).reduce(
      (normalized, key) => {
        return Object.assign(normalized, { [key]: defaultNormalizer(realisation[key]) });
      },
      {} as Shape<Realisation>,
    );
  };

  return {
    findByPaginated: async (page: number, filters: RealisationFilters): Promise<PaginatedResult<Realisation>> => {
      const params = formatQueryParams(filters);
      params.append('page', page.toString());

      const response = await $fetch(`/realisations?${params}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return new PaginatedResult(
        response['hydra:member'].map(denormalize),
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Realisation> => {
      const realisation: Realisation = await $fetch(`/realisations/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return denormalize(realisation);
    },

    create: async (payload: Realisation): Promise<void> => {
      await $fetch('/realisations', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: normalize(payload),
      });
    },

    update: async (id: string, payload: Realisation): Promise<void> => {
      await $fetch(`/realisations/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: normalize(payload),
      });
    },

    delete: async (id: string): Promise<void> => {
      await $fetch(`/realisations/${id}`, {
        method: 'DELETE',
        baseURL: config.public.apiBaseUrl,
      });
    },
  };
};

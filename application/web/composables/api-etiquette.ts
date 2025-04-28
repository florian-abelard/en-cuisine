import { useRuntimeConfig } from "#imports";
import type { Etiquette } from "~/models/etiquette";
import { PaginatedResult } from "~/models/paginated-result";
import { formatQueryParams } from "~/utils/api-utils";

interface EtiquetteFilters {
  [key: string]: unknown;
  libelle?: string;
}

export const useApiEtiquette = () => {

  const config = useRuntimeConfig();

  return {
    findAll: async (): Promise<Etiquette[]> => {
      const response = await $fetch('/etiquettes', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return response['hydra:member'];
    },

    findByPaginated: async (page: number, filters: EtiquetteFilters): Promise<PaginatedResult<Etiquette>> => {
      const params = formatQueryParams(filters);
      params.append('page', page.toString());

      const response = await $fetch('/etiquettes', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
        params: Object.fromEntries(params.entries()),
      });

      return new PaginatedResult(
        response['hydra:member'],
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Etiquette> => {
      return await $fetch(`/etiquettes/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });
    },

    create: async (payload: Etiquette): Promise<Etiquette> => {
      return await $fetch('/etiquettes', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    update: async (id: string, payload: Etiquette): Promise<void> => {
      await $fetch(`/etiquettes/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    delete: async (id: string): Promise<void> => {
      await $fetch(`/etiquettes/${id}`, {
        method: 'DELETE',
        baseURL: config.public.apiBaseUrl,
      });
    },
  };
};

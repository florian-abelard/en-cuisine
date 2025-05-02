import { useRuntimeConfig } from "#imports";
import type { Ingredient } from "~/models/ingredient";
import { PaginatedResult } from "~/models/paginated-result";
import { formatQueryParams } from "~/utils/api-utils";

interface IngredientFilters {
  [key: string]: unknown;
  libelle?: string;
}

export const useApiIngredient = () => {

  const config = useRuntimeConfig();

  return {
    findAll: async (): Promise<Ingredient[]> => {
      const response = await $fetch('/ingredients', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });

      return response['hydra:member'];
    },

    findByPaginated: async (page: number, filters: IngredientFilters): Promise<PaginatedResult<Ingredient>> => {
      const params = formatQueryParams(filters);
      params.append('page', page.toString());

      const response = await $fetch('/ingredients', {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
        params: Object.fromEntries(params.entries()),
      });

      return new PaginatedResult(
        response['hydra:member'],
        response['hydra:totalItems'],
      );
    },

    findById: async (id: string): Promise<Ingredient> => {
      return await $fetch(`/ingredients/${id}`, {
        method: 'GET',
        baseURL: config.public.apiBaseUrl,
      });
    },

    create: async (payload: Ingredient): Promise<Ingredient> => {
      return await $fetch('/ingredients', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    update: async (id: string, payload: Ingredient): Promise<void> => {
      await $fetch(`/ingredients/${id}`, {
        method: 'PUT',
        baseURL: config.public.apiBaseUrl,
        body: payload,
      });
    },

    delete: async (id: string): Promise<void> => {
      await $fetch(`/ingredients/${id}`, {
        method: 'DELETE',
        baseURL: config.public.apiBaseUrl,
      });
    },
  };
};

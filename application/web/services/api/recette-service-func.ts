import { PaginatedResult } from "~/models/paginated-result";
import { Recette } from "~/models/recette";
import { useRuntimeConfig } from "#imports";

const config = useRuntimeConfig();

export async function findByPaginated(page: number): Promise<PaginatedResult<Recette>> {
  const response = await $fetch('/recettes', {
    method: 'GET',
    baseURL: config.public.apiBaseUrl,
    params: { page },
  });

  return new PaginatedResult(
    response['hydra:member'],
    response['hydra:totalItems'],
  );
}

import { PaginatedResult } from "~/models/paginated-result";
import { Recette } from "~/models/recette";
import { useRuntimeConfig } from "#imports";

export default new class RecetteService {

  private config = useRuntimeConfig();

  public async findByPaginated(page: number): Promise<PaginatedResult<Recette>> {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: this.config.public.apiBaseUrl,
      credentials: 'include',
      params: { page },
    });

    return new PaginatedResult(
      response['hydra:member'],
      response['hydra:totalItems'],
    );
  }
};

import { PaginatedResult } from "~/models/paginated-result";
import { Recette } from "~/models/recette";

export class RecetteService {

  private config = useRuntimeConfig();

  async findAll(): Promise<Recette[]> {
    return await Recette.find();
  }

  async findByPaginated(page: number): Promise<PaginatedResult<Recette>> {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: this.config.public.apiBaseUrl,
      params: { page },
    });

    return new PaginatedResult(
      response['hydra:member'],
      response['hydra:totalItems'],
    );
  }
}

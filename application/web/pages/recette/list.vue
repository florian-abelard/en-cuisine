<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in data?.items" :key="index">
        {{ recette.id }} - {{ recette.libelle }}
      </li>
    </ul>

    <Pagination
      v-if="data?.items?.length"
      :items-count="data?.itemsCount"
      @page-changed="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">

  import { useRuntimeConfig } from '#imports';
  import { useQuery } from '#imports';
  import { ref } from '#imports';
  import { PaginatedResult } from '~/models/paginated-result';
  import { Recette } from '~/models/recette';

  const config = useRuntimeConfig();
  const page = ref(1);

  const { data } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value),
  });

  const fetchRecettes = async (page: number): Promise<PaginatedResult<Recette>> => {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: config.public.apiBaseUrl,
      params: { page },
    });

    return new PaginatedResult(
      response['hydra:member'],
      response['hydra:totalItems'],
    );
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

</script>

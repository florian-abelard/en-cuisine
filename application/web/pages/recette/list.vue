<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in data?.recettes" :key="index">
        {{ recette.id }} - {{ recette.libelle }}
      </li>
    </ul>

    <Pagination
      v-if="data?.recettes?.length"
      :total-items="data?.totalItems"
      @page-changed="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">

  import { useRuntimeConfig } from '#imports';
  import { useQuery } from '#imports';
  import { ref } from '#imports';

  const config = useRuntimeConfig();
  const page = ref(1);

  const { data } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value),
  });

  const fetchRecettes = async (page: number): Promise<{recettes: any[], totalItems: number}> => {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: config.public.apiBaseUrl,
      params: { page },
    });

    return {
      recettes: response['hydra:member'],
      totalItems: response['hydra:totalItems'],
    };
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

</script>

<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in data?.recettes" v-bind:key="index">
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

<script setup>

  import { useRuntimeConfig } from '#imports';
  import { useQuery } from '#imports';
  import { ref } from '#imports';

  const config = useRuntimeConfig();
  const page = ref(1);

  const { data } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value)
  });

  const fetchRecettes = async (page) => {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: config.public.apiBaseUrl,
      params: { page: page }
    });

    return {
      recettes: response['hydra:member'],
      totalItems: response['hydra:totalItems'],
    };
  }

  const onPageChange = (newPage) => page.value = newPage;

</script>

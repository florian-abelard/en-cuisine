<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in recettes" :key="index">
        {{ recette.id }} - {{ recette.libelle }}
      </li>
    </ul>

    <Pagination
      v-if="recettes?.length"
      :items-count="itemsCount"
      @page-changed="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">

  import { useQuery } from '#imports';
  import { ref } from '#imports';
  import { Recette } from '~/models/recette';
  import RecetteService from '~/services/api/recette-service';

  const page = ref(1);
  const itemsCount = ref(null);

  const { data: recettes } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value),
  });

  const fetchRecettes = async (page: number): Promise<Recette[]> => {
    const result = await RecetteService.findByPaginated(page);

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

</script>

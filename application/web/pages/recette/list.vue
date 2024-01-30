<template>
  <div>
    <Head>
      <Title>En cuisine ! Recettes</Title>
    </Head>

    <h2 class="text-4xl font-normal leading-normal mt-0 mb-2 text-primary">Liste des recettes</h2>

    <table class="table">
      <tbody>
        <tr
          v-for="(recette, index) in recettes"
          :key="index"
          class="hover:bg-primary/30"
        >
          <th>{{ recette.id }}</th>
          <td>{{ recette.libelle }}</td>
        </tr>
      </tbody>
    </table>

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

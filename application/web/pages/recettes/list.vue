<template>
  <div>
    <Head>
      <Title>En cuisine ! Recettes</Title>
    </Head>

    <div class="flex justify-between">
      <h2 class="text-4xl font-normal leading-normal mt-0 mb-2 ml-2 text-primary">
        Recettes
      </h2>
      <NuxtLink
        to="/recettes/create"
        class="btn btn-primary btn-circle mx-4 fixed top-20 right-4 z-[1]"
      >
        <Plus :size="22" />
      </NuxtLink>
    </div>

    <div v-if="isFetching" class="w-full flex justify-center my-8">
      <span class="loading loading-spinner loading-sm" />
    </div>

    <table class="table" v-if="!isFetching">
      <tbody>
        <tr
          v-for="(recette, index) in recettes"
          :key="index"
          class="hover:bg-primary/30 cursor-pointer"
          :data-href="`/recettes/${recette.id}`"
          @click="() => navigateTo(`/recettes/${recette.id}`)"
        >
          <th>{{ recette.id }}</th>
          <td>{{ recette.libelle }}</td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :items-count="itemsCount"
      @page-changed="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">

  import { useQuery, ref, navigateTo, useApiRecette } from '#imports';
  import { Recette } from '~/models/recette';
  import { Plus } from 'lucide-vue-next';

  const page = ref(1);
  const itemsCount = ref(null);

  const { data: recettes, isFetching } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value),
  });

  const fetchRecettes = async (page: number): Promise<Recette[]> => {
    const result = await useApiRecette().findByPaginated(page);

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

</script>

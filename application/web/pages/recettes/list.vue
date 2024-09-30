<template>
  <div>
    <Head>
      <Title>En cuisine ! Recettes</Title>
    </Head>

    <div class="flex justify-between">
      <h2 class="text-3xl font-normal leading-normal mt-0 mb-2 ml-2 text-primary">
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

    <div v-if="!isFetching" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
      <div
        class="card card-side card-compact bg-base-100 shadow h-32 bg-gray-100 hover:bg-yellow-50"
        v-for="(recette, index) in recettes"
        :key="index"
        :data-href="`/recettes/${recette.id}`"
        @click="() => navigateTo(`/recettes/${recette.id}`)"
      >
        <figure class="w-1/3 h-full">
          <img
            :src="(recette?.image as Media).url"
            :alt="`Illustration de la recette ${recette.libelle}`"
            class="object-cover w-full h-full"
          />
        </figure>
        <div class="card-body w-2/3">
          <h2 class="card-title text-lg line-clamp-2">{{ recette.libelle }}</h2>
        </div>
      </div>
    </div>

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
  import type { Media } from '~/models/media';

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

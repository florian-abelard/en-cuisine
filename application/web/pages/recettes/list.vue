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

    <div v-if="!isFetching" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <div
        class="card card-side bg-base-100 shadow-xl"
        v-for="(recette, index) in recettes"
        :key="index"
        :data-href="`/recettes/${recette.id}`"
        @click="() => navigateTo(`/recettes/${recette.id}`)"
      >
        <figure>
          <img
            :src="(recette?.image as Media).url"
            :alt="`Illustration de la recette ${recette.libelle}`"
          />
        </figure>
        <div class="card-body">
          <h2 class="card-title">{{ recette.libelle }}</h2>
          <p>Description</p>
          <div class="card-actions justify-end">
            <button class="btn btn-primary">Watch</button>
          </div>
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

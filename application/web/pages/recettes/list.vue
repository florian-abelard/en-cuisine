<template>
  <div>
    <Head>
      <Title>En cuisine ! Recettes</Title>
    </Head>

    <div class="drawer drawer-end">
      <input
        ref="filter-drawer"
        id="filter-drawer"
        type="checkbox"
        class="hidden drawer-toggle"
        :checked="filterStore.visible"
        @change="syncFilterVisibility"
      />

      <div class="drawer-content">
        <div class="flex justify-between">
          <NuxtLink
            to="/recettes/create"
            class="btn btn-primary btn-circle btn-lg fixed bottom-10 right-12 z-[1]"
          >
            <Plus :size="24" />
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
              <h2 class="card-title text-lg line-clamp-2">
                {{ recette.libelle }}
              </h2>
            </div>
          </div>
        </div>

        <Pagination
          :items-count="itemsCount"
          @page-changed="onPageChange"
        />
      </div>

      <div class="drawer-side">
        <label
          for="filter-drawer"
          aria-label="close sidebar"
          class="drawer-overlay"
        />
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4 mt-16">
          <!-- Sidebar content here -->
          <li><a>Sidebar Item 1</a></li>
          <li><a>Sidebar Item 2</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

  import { useQuery, ref, navigateTo, useApiRecette, definePageMeta, useFilterStore } from '#imports';
  import { Recette } from '~/models/recette';
  import { Plus } from 'lucide-vue-next';
  import type { Media } from '~/models/media';

  definePageMeta({
    pageType: 'list',
    pageName: 'Recettes',
  });

  const page = ref(1);
  const itemsCount = ref(0);
  const filterStore = useFilterStore();

  const { data: recettes, isFetching } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value),
  });

  const fetchRecettes = async (page: number): Promise<Recette[]> => {
    const result = await useApiRecette().findByPaginated(page, {});

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

  const syncFilterVisibility = (event: Event) => {
    const target = event.target as HTMLInputElement;
    filterStore.setVisibility(target.checked);
  };

</script>

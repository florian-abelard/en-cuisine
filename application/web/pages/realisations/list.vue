<template>
  <div>
    <Head>
      <Title>En cuisine ! Réalisations</Title>
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
            to="/realisations/create"
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
            class="card card-compact bg-base-100 shadow bg-gray-100 hover:bg-yellow-50"
            v-for="(realisation, index) in realisations"
            :key="index"
            :data-href="`/realisations/${realisation.id}`"
            @click="() => navigateTo(`/realisations/${realisation.id}`)"
          >
            <div class="card-body">
              <h2 class="card-title text-lg line-clamp-2">
                {{ realisation.recette?.libelle }}
              </h2>
              <p class="text-sm text-gray-500">
                {{ formatDate(realisation.date) }}
              </p>
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
        <div class="bg-base-200 text-base-content min-h-full w-80 p-4 mt-16">
          <h2 class="text-xl font-bold mb-4 text-right">
            Affiner la liste
          </h2>
          <form
            @submit.prevent="onSubmit"
            class="flex flex-col"
          >
            <label class="input input-bordered input-primary flex items-center gap-2 my-2">
              <span class="font-semibold">Recette</span>
              <select
                class="select select-ghost focus:outline-none focus:bg-opacity-0 w-full max-w-xs text-base"
                v-model="recetteFilter"
                v-bind="recetteFilterAttrs"
              >
                <option value="" />
                <option
                  v-for="item in recettes"
                  :key="item.id"
                  :value="item['@id']"
                >
                  {{ item.libelle }}
                </option>
              </select>
            </label>

            <label class="input input-bordered input-primary flex items-center gap-2 my-2">
              <span class="font-semibold mr-4">Depuis</span>
              <input
                class="grow placeholder-gray-400"
                type="date"
                v-model="dateAfter"
                v-bind="dateAfterAttrs"
              />
            </label>

            <label class="input input-bordered input-primary flex items-center gap-2 my-2">
              <span class="font-semibold mr-4">Jusqu'à</span>
              <input
                class="grow placeholder-gray-400"
                type="date"
                v-model="dateBefore"
                v-bind="dateBeforeAttrs"
              />
            </label>

            <div class="flex justify-end mt-4">
              <button
                type="button"
                class="btn btn-ghost text-base mx-2"
                @click="resetFilters"
                :disabled="isSubmitting"
              >
                Réinitialiser
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

  import { useQuery, ref, navigateTo, useApiRealisation, definePageMeta, useFilterStore, useForm, useApiRecette } from '#imports';
  import type { Realisation } from '~/models/realisation';
  import { Plus } from 'lucide-vue-next';
  import { toTypedSchema } from '@vee-validate/yup';
  import { watchDebounced } from '@vueuse/core';
  import { object, string } from 'yup';
  import type { RealisationFilters } from '~/models/filters/realisation-filters';

  definePageMeta({
    pageType: 'list',
    pageName: 'Réalisations',
  });

  const page = ref(1);
  const itemsCount = ref(0);
  const filterStore = useFilterStore();
  const debouncedFilters = ref<RealisationFilters>({});

  const { values: filters, resetForm, handleSubmit, defineField, isSubmitting } = useForm<RealisationFilters>({
    validationSchema: toTypedSchema(
      object({
        'recette': string().nullable(),
        'date[after]': string().nullable(),
        'date[before]': string().nullable(),
      }),
    ),
  });

  const { data: realisations, isFetching } = useQuery({
    queryKey: ['realisations', page, debouncedFilters],
    queryFn: () => fetchRealisations(page.value),
  });

  const [recetteFilter, recetteFilterAttrs] = defineField('recette');
  const [dateAfter, dateAfterAttrs] = defineField('date[after]');
  const [dateBefore, dateBeforeAttrs] = defineField('date[before]');

  const fetchRealisations = async (page: number): Promise<Realisation[]> => {
    const result = await useApiRealisation().findByPaginated(page, filters);

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const { data: recettes } = useQuery({
    queryKey: ['recettes-all'],
    queryFn: () => useApiRecette().findByPaginated(1, {}).then(r => r.items),
  });

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

  const syncFilterVisibility = (event: Event) => {
    const target = event.target as HTMLInputElement;
    filterStore.setVisibility(target.checked);
  };

  const onSubmit = handleSubmit(async () => {
    page.value = 1;
    await fetchRealisations(page.value);
  });

  watchDebounced(
    () => ({ ...filters }),
    (newFilters) => {
      debouncedFilters.value = newFilters;
      page.value = 1;
    },
    { debounce: 500, deep: true },
  );

  const resetFilters = () => {
    resetForm();
  };

</script>

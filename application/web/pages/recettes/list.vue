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
        <div class="bg-base-200 text-base-content min-h-full w-80 p-4 mt-16">
          <h2 class="text-xl font-bold mb-4 text-right">
            Affiner la liste
          </h2>
          <form
            @submit.prevent="onSubmit"
            class="flex flex-col"
          >
            <label class="input input-bordered input-primary flex items-center gap-2 my-2">
              <span class="font-semibold">Catégorie</span>
              <select
                class="select select-ghost focus:outline-none focus:bg-opacity-0 w-full max-w-xs  text-base"
                v-model="categorie"
                v-bind="categorieAttrs"
                placeholder="Pâtes au ketchup"
              >
                >
                <option value="" />
                <option
                  v-for="item in categories"
                  :key="item.id"
                  :value="item['@id']"
                >
                  {{ item.libelle }}
                </option>
              </select>
            </label>

            <label class="input input-bordered input-primary flex items-center gap-2 my-2">
              <span class="font-semibold mr-4">Prêt dans</span>
              <input
                class="grow placeholder-gray-400"
                type="text"
                v-model="pretDans"
                v-bind="pretDansAttrs"
                placeholder="30 minutes"
              />
            </label>

            <AutoComplete
              class="my-2"
              v-model="ingredients"
              :label="'Ingrédients'"
              :display-item-fn="(item: Ingredient) => item.libelle"
              :query-fn="fetchFilteredIngredients"
              placeholder="Rechercher un ingrédient"
            />

            <AutoComplete
              class="my-2"
              v-model="etiquettes"
              :label="'Etiquettes'"
              :display-item-fn="(item: Etiquette) => item.libelle"
              :query-fn="fetchFilteredEtiquettes"
              placeholder="Rechercher une étiquette"
            />

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

  import { useQuery, ref, navigateTo, useApiRecette, definePageMeta, useFilterStore, useForm, useApiCategorie, useApiIngredient, useApiEtiquette } from '#imports';
  import { Recette } from '~/models/recette';
  import { Plus } from 'lucide-vue-next';
  import { toTypedSchema } from '@vee-validate/yup';
  import { watchDebounced } from '@vueuse/core';
  import { object, string, array } from 'yup';
  import type { Media } from '~/models/media';
  import type { RecetteFilters } from '~/models/filters/recette-filters';
  import type { Ingredient } from '~/models/ingredient';
import type { Etiquette } from '~/models/etiquette';

  definePageMeta({
    pageType: 'list',
    pageName: 'Recettes',
  });

  const page = ref(1);
  const itemsCount = ref(0);
  const filterStore = useFilterStore();
  const debouncedFilters = ref<RecetteFilters>({});

  const { values: filters, resetForm, handleSubmit, defineField, isSubmitting } = useForm<RecetteFilters>({
    validationSchema: toTypedSchema(
      object({
        categorie: string().nullable(),
        pretDans: string().nullable(),
        ingredients: array().nullable().default([]),
        etiquettes: array().nullable().default([]),
      }),
    ),
  });

  const { data: recettes, isFetching } = useQuery({
    queryKey: ['recettes', page, debouncedFilters],
    queryFn: () => fetchRecettes(page.value),
  });

  const [categorie, categorieAttrs] = defineField('categorie');
  const [pretDans, pretDansAttrs] = defineField('pretDans');
  const [ingredients] = defineField('ingredients');
  const [etiquettes] = defineField('etiquettes');

  const fetchRecettes = async (page: number): Promise<Recette[]> => {
    const result = await useApiRecette().findByPaginated(page, filters);

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const { data: categories } = useQuery({
    queryKey: ['categories'],
    queryFn: () => useApiCategorie().findAll(),
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
    await fetchRecettes(page.value);
  });

  watchDebounced(
    () => ({ ...filters }),
    (newFilters) => {
      console.log('Filters changed:', newFilters);
      debouncedFilters.value = newFilters;
      page.value = 1;
    },
    { debounce: 500, deep: true },
  );

  const resetFilters = () => {
    resetForm();
  };

  const fetchFilteredIngredients = async (search: string): Promise<Ingredient[]> => {
    const result = await useApiIngredient().findByPaginated(1, { libelle: search });
    return result.items;
  };

  const fetchFilteredEtiquettes = async (search: string): Promise<Etiquette[]> => {
    const result = await useApiEtiquette().findByPaginated(1, { libelle: search });
    return result.items;
  };

</script>

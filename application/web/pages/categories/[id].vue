<template>
  <div class="px-4">
    <form
      v-if="!isFetching"
      @submit="onSubmit"
      class="flex flex-col"
    >
      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Libellé</span>
        <input
          class="grow"
          type="text"
          v-model="libelle"
          v-bind="libelleAttrs"
          placeholder="Pâtes au ketchup"
        />
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Ordre d'affichage</span>
        <input
          class="grow"
          type="text"
          v-model="order"
          v-bind="orderAttrs"
          placeholder="Pâtes au ketchup"
        />
      </label>

      <div class="flex justify-end mt-4">
        <button
          type="button"
          class="btn mx-2 text-base"
          @click="resetForm()"
          :disabled="isSubmitting"
        >
          Annuler
        </button>

        <button
          type="submit"
          class="btn btn-primary mx-2 text-base"
          :disabled="isSubmitting || !meta.valid"
        >
          Valider
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm, useRoute, ref, useApiCategorie, navigateTo, useQuery, watch, definePageMeta } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string, number } from 'yup';
  import type { Categorie } from '~/models/categorie';

  interface CategorieForm {
    libelle?: string | null;
    order?: number | null;
  }

  definePageMeta({
    pageType: 'detail',
    pageName: 'Catégories',
  });

  const route = useRoute();
  const mode = ref<'create' | 'update'>('create');

  if (route.params.id !== 'create') {
    mode.value = 'update';
  }

  const { data: categorie, isFetching } = useQuery({
    queryKey: ['categorie', route.params.id],
    queryFn: () => useApiCategorie().findById(route.params.id as string),
    enabled: mode.value === 'update',
  });

  const { meta, resetForm, handleSubmit, defineField, isSubmitting } = useForm<CategorieForm>({
    validationSchema: toTypedSchema(
      object({
        libelle: string().required().default(''),
        order: number().required().default(null),
      }),
    ),
  });

  const [libelle, libelleAttrs] = defineField('libelle');
  const [order, orderAttrs] = defineField('order');

  watch([categorie], () => {
    if (categorie.value) {
      resetForm({ values: categorie.value as Partial<Categorie> });
    }
  }, { immediate: true });

  const onSubmit = handleSubmit(async (values) => {
    try {
      const categorie = values as Categorie;

      mode.value === 'create'
        ? await useApiCategorie().create(categorie)
        : await useApiCategorie().update(route.params.id as string, categorie);

      navigateTo('/categories/list');
    } catch (e) {
      console.error(e);
    }
  });
</script>

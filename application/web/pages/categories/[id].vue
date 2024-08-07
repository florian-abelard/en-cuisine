<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">
      Categorie
    </h1>

    <form
      v-if="!isFetching"
      @submit="onSubmit"
      class="flex flex-col"
    >
      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold">Libellé :</span>
        <input
          class="grow"
          type="text"
          v-model="libelle"
          v-bind="libelleAttrs"
          placeholder="Pâtes au ketchup"
        >
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold">Ordre d'affichage :</span>
        <input
          class="grow"
          type="text"
          v-model="order"
          v-bind="orderAttrs"
          placeholder="Pâtes au ketchup"
        >
      </label>

      <div class="flex justify-end mt-4">
        <button
          type="reset"
          class="btn btn-sm mx-2"
          @click="resetForm()"
          :disabled="isSubmitting"
        >
          Annuler
        </button>

        <button
          type="submit"
          class="btn btn-primary btn-sm mx-2"
          :disabled="isSubmitting || !meta.valid"
        >
          Valider
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm, useRoute, ref, useApiCategorie, navigateTo, useQuery, watch } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string, number } from 'yup';
  import type { Categorie } from '~/models/categorie';

  interface CategorieForm {
    libelle?: string | null;
    order?: number | null;
  }

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

<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">
      Recette
    </h1>

    <form @submit="onSubmit" class="flex flex-col">
      <label class="input input-bordered input-primary flex items-center gap-2">
        <span class="font-semibold">Libellé :</span>
        <input
          class="grow"
          type="text"
          v-model="libelle"
          v-bind="libelleAttrs"
          placeholder="Pâtes au ketchup"
        >
      </label>

      <div class="flex justify-end mt-4">
        <button
          type="reset"
          class="btn btn-sm mx-2"
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

  import { useForm, useRoute, ref } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';

  interface RecetteForm {
    libelle?: string | null;
  }

  const route = useRoute();
  const mode = ref<'create' | 'update'>('create');

  const { meta, handleSubmit, defineField, isSubmitting } = useForm<RecetteForm>({
    validationSchema: toTypedSchema(
      object({
        libelle: string().required(),
      }),
    ),
  });

  const [libelle, libelleAttrs] = defineField('libelle');

  // When accessing /posts/1, route.params.id will be 1
  console.log(route.params.id);

const onSubmit = handleSubmit(async (values) => {
  try {
    console.log('values', values);
    // await useApiRecette().update(route.params.id, values);
    // navigateTo('/recettes/list');
  } catch (e) {
    console.error(e);
  }
});
</script>

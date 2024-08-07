<template>
  <div class="p-4">
    <h1 class="text-xl font-bold mb-4">
      Recette
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

      <label class="flex items-center justify-between gap-2 my-2">
        <span class="font-semibold">Image :</span>
        <input
          class="grow file-input file-input-warning w-full max-w-md"
          type="file"
          accept="image/png, image/jpeg"
          @change="onImageChange"
        >
        <img
          v-if="image"
          :src="image.url"
          class="h-24 object-contain"
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

  import { useForm, useRoute, ref, useApiRecette, useApiMedia, navigateTo, useQuery, watch } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';
  import type { Recette } from '~/models/recette';
  import type { Media } from '~/models/media';

  interface RecetteForm {
    libelle?: string | null;
    image?: Media | string | null;
  }

  const route = useRoute();
  const mode = ref<'create' | 'update'>('create');
  const image = ref<Media | null>(null);

  const { meta, resetForm, setValues, handleSubmit, defineField, isSubmitting } = useForm<RecetteForm>({
    validationSchema: toTypedSchema(
      object({
        libelle: string().required().default(''),
        image: object().nullable().default(null),
      }),
    ),
  });

  const [libelle, libelleAttrs] = defineField('libelle');

  if (route.params.id !== 'create') {
    mode.value = 'update';
  }

  const { data: recette, isFetching } = useQuery({
    queryKey: ['recette', route.params.id],
    queryFn: () => useApiRecette().findById(route.params.id as string),
    enabled: mode.value === 'update',
  });

  watch(recette, (recette: Recette) => {
    if (recette) {
      resetForm({ values: recette });
      image.value = recette.image as Media;
    }
  }, { immediate: true });

  const onSubmit = handleSubmit(async (values) => {
    try {
      const recette = values as Recette;
      recette.image = image.value ? image.value['@id'] as string : null;

      mode.value === 'create'
        ? await useApiRecette().create(recette)
        : await useApiRecette().update(route.params.id as string, recette);

      navigateTo('/recettes/list');
    } catch (e) {
      console.error(e);
    }
  });

  const onImageChange = async (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (file) {
      const media = await useApiMedia().upload(file);
      image.value = media;
      setValues({ image: media });
    }
  };
</script>

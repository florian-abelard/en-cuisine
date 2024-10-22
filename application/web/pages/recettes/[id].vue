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
        <span class="font-semibold mr-4">Libellé</span>
        <input
          class="grow placeholder-gray-400"
          type="text"
          v-model="libelle"
          v-bind="libelleAttrs"
          placeholder="Pâtes au ketchup"
        >
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold">Catégorie</span>
        <select
          class="select select-ghost focus:outline-none focus:bg-opacity-0 w-full max-w-xs  text-base"
          v-model="categorie"
          v-bind="categorieAttrs"
          placeholder="Pêtes au ketchup"
        >
          >
          <option value="" disabled />
          <option
            v-for="item in categories"
            :key="item.id"
            :value="item['@id']"
          >
            {{ item.libelle }}
          </option>
        </select>
      </label>

      <label class="form-control my-2">
        <div class="label">
          <span class="label-text font-semibold text-base">Description</span>
        </div>
        <textarea
          class="grow textarea textarea-bordered textarea-primary"
          v-model="description"
          v-bind="descriptionAttrs"
          rows="3"
          placeholder="Sauce ketchup artisanale, pâtes fraîches cuites à point, filet de crème fraîche et herbes fraîches pour un plat authentique et raffiné."
        />
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Source</span>
        <input
          class="grow placeholder-gray-400"
          type="text"
          v-model="source"
          v-bind="sourceAttrs"
          placeholder="https://www.chefcolin.fr/pates-au-ketchup/"
        >
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Prêt dans</span>
        <input
          class="grow placeholder-gray-400"
          type="text"
          v-model="pretDans"
          v-bind="pretDansAttrs"
          placeholder="30 minutes"
        >
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Préparation</span>
        <input
          class="grow placeholder-gray-400"
          type="text"
          v-model="tempsDePreparation"
          v-bind="tempsDePreparationAttrs"
          placeholder="30 minutes"
        >
      </label>

      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold mr-4">Cuisson</span>
        <input
          class="grow placeholder-gray-400"
          type="text"
          v-model="tempsDeCuisson"
          v-bind="tempsDeCuissonAttrs"
          placeholder="30 minutes"
        >
      </label>

      <label class="form-control my-2">
        <div class="label">
          <span class="label-text font-semibold text-base">Notes</span>
        </div>
        <textarea
          class="grow textarea textarea-bordered textarea-primary"
          v-model="notes"
          v-bind="notesAttrs"
          rows="3"
          placeholder=""
        />
      </label>

      <label class="form-control">
        <div class="label">
          <span class="label-text font-semibold text-base">Vignette</span>
        </div>
        <div class="flex items-start gap-2">
          <input
            class="grow file-input file-input-primary w-full"
            type="file"
            accept="image/png, image/jpeg"
            @change="onImageChange"
          >
          <img
            v-if="image"
            :src="image.url"
            class="h-24 object-contain"
          >
        </div>
      </label>

      <div class="flex justify-end mt-4">
        <button
          type="button"
          class="btn text-base x-2"
          @click="resetForm()"
          :disabled="isSubmitting"
        >
          Annuler
        </button>

        <button
          type="submit"
          class="btn btn-primary text-base mx-2"
          :disabled="isSubmitting || !meta.valid"
        >
          Valider
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm, useRoute, ref, useApiRecette, useApiMedia, navigateTo, useQuery, watch, useApiCategorie } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';
  import type { Recette } from '~/models/recette';
  import type { Media } from '~/models/media';
  import type { Categorie } from '~/models/categorie';

  interface RecetteForm {
    libelle?: string | null;
    categorie?: Categorie | string | null;
    image?: Media | string | null;
    description?: string | null;
    source?: string | null;
    tempsDePreparation?: string | null;
    tempsDeCuisson?: string | null;
    pretDans?: string | null;
    notes?: string | null;
  }

  const route = useRoute();
  const mode = ref<'create' | 'update'>('create');
  const image = ref<Media | null>(null);

  const { meta, resetForm, setValues, handleSubmit, defineField, isSubmitting } = useForm<RecetteForm>({
    validationSchema: toTypedSchema(
      object({
        libelle: string().required().default(''),
        categorie: string().required().default(''),
        image: object().nullable().default(null),
        tempsDePreparation: string().nullable().default(null),
        tempsDeCuisson: string().nullable().default(null),
        pretDans: string().nullable().default(null),
        notes: string().nullable().default(null),
      }),
    ),
  });

  const [libelle, libelleAttrs] = defineField('libelle');
  const [categorie, categorieAttrs] = defineField('categorie');
  const [description, descriptionAttrs] = defineField('description');
  const [source, sourceAttrs] = defineField('source');
  const [tempsDePreparation, tempsDePreparationAttrs] = defineField('tempsDePreparation');
  const [tempsDeCuisson, tempsDeCuissonAttrs] = defineField('tempsDeCuisson');
  const [pretDans, pretDansAttrs] = defineField('pretDans');
  const [notes, notesAttrs] = defineField('notes');

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

  const { data: categories } = useQuery({
    queryKey: ['categories'],
    queryFn: () => useApiCategorie().findAll(),
  });

  const onSubmit = handleSubmit(async (values) => {
    try {
      const recette = values as Recette;
      recette.image = image.value ? image.value['@id'] as string : null;
      console.log(recette);


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

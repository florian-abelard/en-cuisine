<template>
  <div class="px-4 py-6">
    <form
      v-if="!isFetching"
      @submit="onSubmit"
      class="flex flex-col"
    >
      <label class="input input-bordered input-primary flex items-center gap-2 my-2">
        <span class="font-semibold whitespace-nowrap">Recette *</span>
        <select
          class="select select-ghost focus:outline-none focus:bg-opacity-0 w-full max-w-xs text-base"
          v-model="recette"
          v-bind="recetteAttrs"
        >
          <option value="" disabled />
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
        <span class="font-semibold mr-4 whitespace-nowrap">Date *</span>
        <input
          class="grow placeholder-gray-400"
          type="date"
          v-model="date"
          v-bind="dateAttrs"
        />
      </label>

      <label class="form-control my-2">
        <div class="label">
          <span class="label-text font-semibold text-base">Notes</span>
        </div>
        <textarea
          class="grow textarea textarea-bordered textarea-primary text-base"
          v-model="notes"
          v-bind="notesAttrs"
          rows="3"
          placeholder=""
        />
      </label>

      <div class="flex justify-between mt-4">
        <button
          v-if="mode === 'update'"
          type="button"
          class="btn btn-error text-base x-2"
          @click="remove()"
          :disabled="isSubmitting"
        >
          <Trash2Icon class="w-5 h-5" />
        </button>

        <div>
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
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">

  import { useForm, useRoute, ref, useApiRealisation, useApiRecette, navigateTo, useQuery, watch, definePageMeta } from '#imports';
  import { toTypedSchema } from '@vee-validate/yup';
  import { object, string } from 'yup';
  import { Trash2Icon } from 'lucide-vue-next';
  import type { Realisation } from '~/models/realisation';
  import type { Recette } from '~/models/recette';

  interface RealisationForm {
    recette?: Recette | string | null;
    date?: string | null;
    notes?: string | null;
  }

  definePageMeta({
    pageType: 'detail',
    pageName: '',
  });

  const route = useRoute();
  const mode = ref<'create' | 'update'>('create');
  const isRemoving = ref(false);

  const { meta, resetForm, handleSubmit, defineField, isSubmitting } = useForm<RealisationForm>({
    validationSchema: toTypedSchema(
      object({
        recette: string().required().default(''),
        date: string().required().default(''),
        notes: string().nullable().default(null),
      }),
    ),
  });

  const [recette, recetteAttrs] = defineField('recette');
  const [date, dateAttrs] = defineField('date');
  const [notes, notesAttrs] = defineField('notes');

  if (route.params.id !== 'create') {
    mode.value = 'update';
  }

  const { data: realisation, isFetching } = useQuery({
    queryKey: ['realisation', route.params.id],
    queryFn: () => useApiRealisation().findById(route.params.id as string),
    enabled: mode.value === 'update',
  });

  watch(realisation, (realisation: Realisation) => {
    if (realisation) {
      route.meta.pageName = realisation.recette?.libelle ?? 'Réalisation';
      resetForm({ values: {
        ...realisation,
        recette: realisation.recette?.['@id'] ?? realisation.recette,
      }});
    }
  }, { immediate: true });

  const { data: recettes } = useQuery({
    queryKey: ['recettes-all'],
    queryFn: () => useApiRecette().findByPaginated(1, {}).then(r => r.items),
  });

  const onSubmit = handleSubmit(async (values) => {
    try {
      const payload = values as Realisation;

      mode.value === 'create'
        ? await useApiRealisation().create(payload)
        : await useApiRealisation().update(route.params.id as string, payload);

      navigateTo('/realisations/list');
    } catch (e) {
      console.error(e);
    }
  });

  const remove = async () => {
    if (!realisation.value) {
      return;
    }

    isRemoving.value = true;
    await useApiRealisation().delete(realisation.value.id);
    isRemoving.value = false;

    navigateTo('/realisations/list', { replace: true });
  };
</script>

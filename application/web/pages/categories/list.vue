<template>
  <div>
    <Head>
      <Title>En cuisine ! Catégories</Title>
    </Head>

    <div class="flex justify-between">
      <h2 class="text-4xl font-normal leading-normal mt-0 mb-2 ml-2 text-primary">
        Catégories
      </h2>
      <NuxtLink
        to="/categories/create"
        class="btn btn-primary btn-circle mx-4 fixed top-20 right-4 z-[1]"
      >
        <Plus :size="22" />
      </NuxtLink>
    </div>

    <div v-if="isFetching" class="w-full flex justify-center my-8">
      <span class="loading loading-spinner loading-sm" />
    </div>

    <table class="table" v-if="!isFetching">
      <thead>
        <tr>
          <th>Libellé</th>
          <th>Ordre d'affichage</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(categorie, index) in categories"
          :key="index"
          class="hover:bg-primary/30 cursor-pointer"
          :data-href="`/categories/${categorie.id}`"
          @click="() => navigateTo(`/categories/${categorie.id}`)"
        >
          <td>{{ categorie.libelle }}</td>
          <td>{{ categorie.order }}</td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :items-count="itemsCount"
      @page-changed="onPageChange"
    />
  </div>
</template>

<script setup lang="ts">

  import { useQuery, ref, navigateTo, useApiCategorie } from '#imports';
  import { Categorie } from '~/models/categorie';
  import { Plus } from 'lucide-vue-next';

  const page = ref(1);
  const itemsCount = ref(null);

  const { data: categories, isFetching } = useQuery({
    queryKey: ['categories', page],
    queryFn: () => fetchCategories(page.value),
  });

const fetchCategories = async (page: number): Promise<Categorie[]> => {
    const result = await useApiCategorie().findByPaginated(page);

    itemsCount.value = result.itemsCount;

    return result.items;
  };

  const onPageChange = (newPage: number): void => {
    page.value = newPage;
  };

</script>

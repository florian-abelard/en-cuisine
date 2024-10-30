<template>
  <div>
    <Head>
      <Title>En cuisine ! Catégories</Title>
    </Head>

    <div class="flex justify-between">
      <NuxtLink
        to="/categories/create"
        class="btn btn-primary btn-circle mx-4 fixed bottom-10 right-4 z-[1]"
      >
        <Plus :size="22" />
      </NuxtLink>
    </div>

    <div v-if="isFetching" class="w-full flex justify-center my-8">
      <span class="loading loading-spinner loading-sm" />
    </div>

    <table class="table text-base" v-if="!isFetching">
      <thead class="text-sm">
        <tr>
          <th>Libellé</th>
          <th>Ordre d'affichage</th>
          <th />
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
          <td class="text-right">
            <Trash2 :size="20" @click.stop="remove(categorie.id)" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">

  import { useQuery, navigateTo, useApiCategorie, definePageMeta } from '#imports';
  import { Plus, Trash2 } from 'lucide-vue-next';

  definePageMeta({
    pageType: 'list',
    pageName: 'Catégories',
  });

  const { data: categories, isFetching, refetch } = useQuery({
    queryKey: ['categories'],
    queryFn: () => useApiCategorie().findAll(),
  });

  const remove = async (id: string): Promise<void> => {
    await useApiCategorie().delete(id);
    await refetch();
  };

</script>

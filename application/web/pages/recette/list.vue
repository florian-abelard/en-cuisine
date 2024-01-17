<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in recettes" v-bind:key="index">
        {{ index }} - {{ recette.libelle }}
      </li>
    </ul>

    <div class="join">
      <button class="join-item btn" @click="previous">Précendente</button>
      <button class="join-item btn">Page {{ page }}</button>
      <button class="join-item btn" @click="next">Suivante</button>
    </div>
  </div>
</template>

<script setup>

  import { useRuntimeConfig } from '#imports';
  import { useQuery } from '#imports';
  import { ref } from '#imports';

  const config = useRuntimeConfig();
  const page = ref(1);

  const next = () => {
    page.value++;
  }

  const previous = () => {
    page.value--;
  }

  const { data: recettes } = useQuery({
    queryKey: ['recettes', page],
    queryFn: () => fetchRecettes(page.value)
  })

  async function fetchRecettes(page) {
    const response = await $fetch('/recettes', {
      method: 'GET',
      baseURL: config.public.apiBaseUrl,
      params: { page: page }
    });

    return response['hydra:member'];
  }

</script>

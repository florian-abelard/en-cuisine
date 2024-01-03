<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in recettes" v-bind:key="index">
        {{ index }} - {{ recette.libelle }}
      </li>
    </ul>
  </div>
</template>

<script setup>

  import { useFetch } from '#imports';
  import { useRuntimeConfig } from '#imports';
  import { ref } from '#imports';

  const config = useRuntimeConfig();

  const recettes = ref();

  fetchRecettes();

  async function fetchRecettes () {
    const { data } = await useFetch('/recettes', {
      method: 'get',
      baseURL: config.public.apiBaseUrl,
    });

    recettes.value = data.value['hydra:member'];
  }

</script>

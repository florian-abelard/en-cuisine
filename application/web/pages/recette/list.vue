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
  import { useQuery } from '#imports';

  const config = useRuntimeConfig();

  const { data: recettes }= useQuery({
    queryKey: ['recettes'],
    queryFn: fetchRecettes,
  })

  async function fetchRecettes () {
    return await useFetch('/recettes', {
      method: 'get',
      baseURL: config.public.apiBaseUrl,
    })
    .then((response) => response.data.value['hydra:member'])
    .catch((error) => console.log(error));
  }

</script>

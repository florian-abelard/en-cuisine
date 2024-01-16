<template>
  <div>
    <h1>Liste des recettes</h1>

    <ul>
      <li v-for="(recette, index) in recettes" v-bind:key="index">
        {{ index }} - {{ recette.libelle }}
      </li>
    </ul>

    <button
      @click="previous"
      class="btn mx-2"
    >
      Précendente
    </button>

    Page : {{ page }}

    <button
      @click="next"
      class="btn mx-2"
    >
      Suivante
    </button>
  </div>
</template>

<script setup>

  import { useFetch } from '#imports';
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

  const { data: recettes }= useQuery({
    queryKey: ['recettes', page],
    queryFn: fetchRecettes,
  })

  async function fetchRecettes() {
    console.log(page.value);
    return await useFetch('/recettes', {
      method: 'get',
      baseURL: config.public.apiBaseUrl,
      query: { page }
    })
    .then((response) => response.data.value['hydra:member'])
    .catch((error) => console.log(error));
  }

</script>

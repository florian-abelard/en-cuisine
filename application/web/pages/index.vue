<script setup  lang="ts">

  import { defineNuxtRouteMiddleware, definePageMeta, navigateTo } from '#imports';

  definePageMeta({
    middleware: defineNuxtRouteMiddleware(() => {
      return navigateTo('/recette/list');
    }),
  });
</script>

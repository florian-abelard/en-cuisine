<template>
  <div class="p-4">
    <h1>Index page</h1>

    <h1 class="text-3xl font-bold">
      Hello world!
    </h1>

    <div>{{ count.toFixed(2) }}</div>
    <br>

    <button
      @click="decrement"
      class="btn mx-2"
    >
      Decrement
    </button>
    <button
      @click="increment"
      class="btn btn-primary mx-2"
    >
      Increment
    </button>
  </div>
</template>

<script setup lang="ts">

  import { ref } from '#imports';

  const count = ref(0);
  const increment = () => count.value++;
  const decrement = () => count.value--;
</script>

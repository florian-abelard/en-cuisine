<template>
  <h1>Index page</h1>

  <div>{{ count }}</div>
  <button @click="increment">Increment</button>
  <button @click="decrement">Decrement</button>
</template>

<script setup lang="ts">
  const count = ref(0);
  const increment = () => count.value++;
  const decrement = () => count.value--;
</script>

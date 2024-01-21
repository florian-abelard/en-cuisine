<template>
  <div class="join">
    <button
      v-for="pageNumber in maxPage"
      :key="pageNumber"
      class="join-item btn"
      :class="{ 'btn-active': pageNumber === currentPage }"
      @click="goTo(pageNumber)"
    >
      {{ pageNumber }}
    </button>
  </div>
</template>

<script setup>
  import { ref } from '#imports';
  import { computed } from '#imports';

  const props = defineProps({
    totalItems: {
      type: Number,
      default: null,
    },
    pageSize: {
      type: Number,
      default: 10,
    },
  });

  const emit = defineEmits({
    // eslint-disable-next-line no-unused-vars
    pageChanged: (page) => true,
  });

  const currentPage = ref(1);

  const maxPage = computed(() => {
    return Math.ceil(props.totalItems / props.pageSize);
  })

  const goTo = (page) => {
    currentPage.value = page;
    emit('pageChanged', currentPage.value);
  }
</script>

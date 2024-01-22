<template>
  <div class="join">
    <button
      v-for="page in maxPage"
      :key="page"
      class="join-item btn"
      :class="{ 'btn-active': page === currentPage }"
      @click="goTo(page)"
    >
      {{ page }}
    </button>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from '#imports';

  export interface Props {
    totalItems: number,
    pageSize?: number
  }

  const props = withDefaults(defineProps<Props>(), {
    pageSize: 10,
  });

  const emit = defineEmits<{
    pageChanged: [page: number],
  }>();

  const currentPage = ref(1);

  const maxPage = computed((): number => {
    return Math.ceil(props.totalItems / props.pageSize);
  });

  const goTo = (page: number) => {
    currentPage.value = page;
    emit('pageChanged', currentPage.value);
  };
</script>

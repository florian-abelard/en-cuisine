<template>
  <div v-if="maxPage > 1" class="join flex justify-center pt-2">
    <button
      v-for="page in maxPage"
      :key="page"
      class="btn btn-primary mx-1 btn-sm lg:btn-md"
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
    itemsCount: number,
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
    return Math.ceil(props.itemsCount / props.pageSize);
  });

  const goTo = (page: number) => {
    currentPage.value = page;
    emit('pageChanged', currentPage.value);
  };
</script>

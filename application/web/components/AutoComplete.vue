<template>
  <div class="relative">
    <div @click.self="toggleFilteredItems()" class="flex flex-row items-center gap-8 grow placeholder-gray-400 border border-primary rounded-md py-2 px-4">
      <label class="font-semibold">{{ label }}</label>
      <div class="flex flex-wrap gap-2 mt-1">
        <span
          v-for="(item, index) in items"
          :key="index"
          class="inline-flex items-center px-3 py-0.5 rounded-full text-white"
          :style="{ backgroundColor: item.color || defaultItemColor }"
        >
          {{ props.displayItemFn(item) }}
          <button
            type="button"
            class="ml-2 text-white"
            @click.stop="removeItem(index)"
          >
            &times;
          </button>
        </span>
      </div>
      <SquareArrowDown @click="toggleFilteredItems()" class="w-6 h-6 ml-auto cursor-pointer text-gray-600" />
    </div>
    <div v-if="showFilteredItems">
      <ul class="absolute z-10 w-full bg-white border border-primary rounded-md mt-0 shadow-lg">
        <li>
          <label class="input input-ghost flex items-center gap-2">
            <Search class="w-6 h-6" />
            <input
              v-model="query"
              type="search"
              placeholder="Search..."
              @input="fetchFilteredItems"
              @focus="showFilteredItems = true"
              @blur="hideFilteredItems"
            />
          </label>
        </li>
        <li
          v-for="(suggestion, index) in filteredItems"
          :key="index"
          @mousedown.prevent="selectSuggestion(suggestion)"
          class="cursor-pointer hover:bg-gray-200 p-2"
        >
          {{ props.displaySuggestionFn(suggestion) }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, defineModel } from 'vue';
  import { Etiquette } from '~/models/etiquette';
  import { Search, SquareArrowDown } from 'lucide-vue-next';

  export interface Props {
    label: string,
    attrs: object,
    queryFn: (query: string) => Promise<Etiquette[]>,
    displayItemFn: (item: Etiquette) => string,
    displaySuggestionFn?: (item: Etiquette) => string,
  }

  const props = withDefaults(defineProps<Props>(), {
    label: 'Libellé',
    attrs: () => ({}),
    queryFn: async () => [],
    displayItemFn: (item: Etiquette) => item.libelle,
    displaySuggestionFn: (item: Etiquette) => item.libelle,
  });

  const items = defineModel<Etiquette[]>({ default: [] });
  const query = ref('');
  const filteredItems = ref<Etiquette[]>([]);
  const showFilteredItems = ref(false);
  const defaultItemColor = '#3B82F6';

  const fetchFilteredItems = async () => {
    if (query.value.length < 2) {
      filteredItems.value = [];
      return;
    }

    filteredItems.value = await props.queryFn(query.value);
  };

  const selectSuggestion = (suggestion: Etiquette) => {
    console.log('Selected suggestion:', suggestion);
    console.log('Items before:', items.value);
    if (!items.value.includes(suggestion)) {
      items.value.push(suggestion);
    }
    query.value = '';
    filteredItems.value = [];
  };

  const hideFilteredItems = () => {
    showFilteredItems.value = false;
  };

  const removeItem = (index: number) => {
    items.value.splice(index, 1);
  };

  const toggleFilteredItems = () => {
    showFilteredItems.value = !showFilteredItems.value;
  };
</script>

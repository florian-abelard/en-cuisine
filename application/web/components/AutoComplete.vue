<template>
  <div class="relative">
    <div @click.self="toggleFilteredItems()" class="flex flex-row items-center gap-8 grow placeholder-gray-400 border border-primary rounded-md bg-white py-2 px-4">
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
    <div
      v-if="showFilteredItems"
      class="absolute z-10 w-full bg-white border border-primary rounded-md mt-1 shadow-lg max-w-md"
    >
      <ul>
        <li>
          <label class="flex items-center gap-2 my-3 px-2">
            <Search class="w-6 h-6" />
            <input
              ref="searchRef"
              v-model="search"
              type="search"
              :placeholder="props.placeholder"
              class="placeholder-gray-400 border-none focus:outline-none"
              @input="fetchFilteredItems"
              @focus="showFilteredItems = true"
            />
          </label>
        </li>
      </ul>
      <ul class="max-h-40 overflow-y-auto">
        <li
          v-for="(option, index) in filteredItems"
          :key="index"
          @mousedown.prevent="selectOption(option)"
          :class="['cursor-pointer hover:bg-gray-200 p-2 pl-10 flex items-center', { 'border-t': index === 0 }]"
        >
          <span
            v-if="withColor"
            class="inline-block w-4 h-2  mr-2 rounded-sm"
            :style="{ backgroundColor: option.color || defaultItemColor }"
          />
          {{ props.displayOptionFn(option) }}
        </li>
        <li v-if="search.length > 1 && filteredItems.length === 0" class="p-2 pl-10 text-gray-500 border-t">
          Aucun résultat trouvé
        </li>
      </ul>
      <ul>
        <li v-if="search.length > 1" class="border-t">
          <button
            type="button"
            class="w-full text-left p-2 hover:bg-gray-200"
            @click="create"
          >
            Créer un nouvel élément
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts" generic="T extends { id: string, libelle?: string, color?: string }">
  import { ref, defineModel, nextTick } from 'vue';
  import type { Ref } from 'vue';
  import { Search, SquareArrowDown } from 'lucide-vue-next';

  export interface Props {
    label: string,
    queryFn: (query: string) => Promise<T[]>,
    displayItemFn: (item: T) => string,
    displayOptionFn?: (item: T) => string,
    createFn?: (item: T) => Promise<T>,
    withColor?: boolean,
    placeholder?: string,
  }

  const props = withDefaults(defineProps<Props>(), {
    label: 'Libellé',
    attrs: () => ({}),
    queryFn: async () => [],
    displayItemFn: (item: T) => item.libelle,
    displayOptionFn: (item: T) => item.libelle,
    createFn: async () => {},
    withColor: false,
    placeholder: 'Rechercher...',
  });

  const items = defineModel<T[]>({ default: [] });
  const filteredItems = ref<T[]>([]) as Ref<T[]>;
  const search = ref('');
  const showFilteredItems = ref(false);
  const defaultItemColor = '#3B82F6';
  const searchRef = ref<HTMLInputElement | null>(null);

  const fetchFilteredItems = async () => {
    filteredItems.value = await props.queryFn(search.value);
  };

  const selectOption = (option: T) => {
    if (!items.value.some(item => item.id === option.id)) {
      items.value.push(option);
    }
    clearSearch();
  };

  const hideFilteredItems = () => {
    showFilteredItems.value = false;
  };

  const removeItem = (index: number) => {
    items.value.splice(index, 1);
  };

  const toggleFilteredItems = async (): Promise<void> => {
    if (showFilteredItems.value) {
      hideFilteredItems();
      return;
    }

    showFilteredItems.value = true;

    await nextTick();
    searchRef.value?.focus();
    searchRef.value?.select();

    if (search.value.length === 0) {
      await fetchFilteredItems();
    }
  };

  const clearSearch = (): void => {
    search.value = '';
    filteredItems.value = [];
    showFilteredItems.value = false;
  };

  const create = async (): Promise<void> => {
    const element = await props.createFn({
      libelle: search.value,
      color: getRandomColor(),
    } as T);

    selectOption(element);
    hideFilteredItems();
  };

  const getRandomColor = (): string => {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  };
</script>

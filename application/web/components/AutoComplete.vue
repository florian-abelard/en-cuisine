<template>
  <div class="relative">
    <div v-if="selectedItems.length" class="mb-2">
      <label class="font-semibold">Selected Items:</label>
      <div class="flex flex-wrap gap-2 mt-1">
        <span
          v-for="(item, index) in selectedItems"
          :key="index"
          class="inline-flex items-center px-3 py-1 rounded-full text-white bg-blue-500"
        >
          {{ item }}
          <button
            type="button"
            class="ml-2 text-white"
            @click="removeItem(index)"
          >
            &times;
          </button>
        </span>
      </div>
    </div>
    <input
      v-model="query"
      type="text"
      placeholder="Search..."
      class="input input-bordered input-primary w-full"
      @input="fetchSuggestions"
      @focus="showSuggestions = true"
      @blur="hideSuggestions"
    />
    <ul v-if="showSuggestions && suggestions.length" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1">
      <li
        v-for="(suggestion, index) in suggestions"
        :key="index"
        @mousedown.prevent="selectSuggestion(suggestion)"
        class="cursor-pointer hover:bg-gray-200 p-2"
      >
        {{ suggestion }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { useForm } from 'vee-validate';

  export interface Props {
    items: object[],
    attrs: object,
  }

  const props = withDefaults(defineProps<Props>(), {
    items: [],
    attrs: {},
  });

  const { handleSubmit } = useForm();
  const query = ref('');
  const suggestions = ref<string[]>([]);
  const showSuggestions = ref(false);
  const selectedItems = defineModel([]);

  const fetchSuggestions = async () => {
    if (query.value.length < 2) {
      suggestions.value = [];
      return;
    }
    // Simulate an API call to fetch suggestions
    suggestions.value = ['Apple', 'Banana', 'Cherry', 'Date', 'Grape', 'Kiwi', 'Lemon', 'Mango', 'Orange', 'Peach', 'Pear', 'Plum', 'Raspberry', 'Strawberry', 'Watermelon'];
  };

  const selectSuggestion = (suggestion: string) => {
    if (!selectedItems.value.includes(suggestion)) {
      selectedItems.value.push(suggestion);
    }
    query.value = '';
    suggestions.value = [];
  };

  const removeItem = (index: number) => {
    selectedItems.value.splice(index, 1);
  };

  const onSubmit = handleSubmit(() => {
    // Handle the search submission
    console.log('Searching for:', query.value);
  });
</script>

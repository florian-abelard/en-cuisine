import { defineStore } from '#imports';

interface State {
  visible: boolean;
}

export const useFilterStore = defineStore('filter', {
  state: (): State => {
    return {
      visible: false,
    };
  },
  actions: {
    toggleVisibility() {
      this.visible = !this.visible;
    },
    setVisibility: function(visible: boolean) {
      this.visible = visible;
    },
  },
});

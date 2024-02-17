import { defineStore } from '#imports';

interface State {
  authenticated: boolean;
}

export const useAuthStore = defineStore('auth', {
  state: (): State => {
    return {
      authenticated: undefined,
    };
  },
  actions: {
    setLoggedOut() {
      this.authenticated = false;
    },
    setLoggedIn() {
      this.authenticated = true;
    },
    setAuthenticated(authenticated: boolean) {
      this.authenticated = authenticated;
    },
  },
});

import { defineNuxtRouteMiddleware, useApiAuth, navigateTo, useAuthStore } from '#imports';

export default defineNuxtRouteMiddleware( async (to) => {

  if (to.path === '/login') {
    return;
  }

  const authStore = useAuthStore();

  if (authStore.authenticated) {
    return;
  }

  const isAuthenticated = await useApiAuth().isAuthenticated();

  if (!isAuthenticated) {
    return navigateTo('/login');
  }
});

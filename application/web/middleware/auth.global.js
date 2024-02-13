import { defineNuxtRouteMiddleware, navigateTo, useAuthStore } from '#imports';

export default defineNuxtRouteMiddleware(async (to) => {

  if (to.path === '/login') {
    return;
  }

  const { isAuthenticated } = useAuthStore();

  const authenticated = await isAuthenticated();

  if (authenticated.value === false) {
    return navigateTo('/login');
  }
});

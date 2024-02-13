import { defineNuxtRouteMiddleware, navigateTo, useAuthStore } from '#imports';

export default defineNuxtRouteMiddleware( async (to) => {

  if (to.path === '/login') {
    return;
  }

  const { isAuthenticated } = useAuthStore();

  if (await isAuthenticated === false) {
    return navigateTo('/login');
  }
});

import { defineNuxtRouteMiddleware, navigateTo, useAuthStore } from '#imports';

export default defineNuxtRouteMiddleware((to, from) => {

  if (to.path === '/login') {
    return;
  }

  const { isAuthenticated } = useAuthStore();

  if (isAuthenticated.value === false) {
    return navigateTo('/login');
  }
});

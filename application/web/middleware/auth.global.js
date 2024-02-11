import { defineNuxtRouteMiddleware, navigateTo, useAuthStore } from '#imports';

export default defineNuxtRouteMiddleware((to, from) => {

  console.log(to.path, from.path);

  if (to.path === '/login') {
    return;
  }

  if (!useAuthStore().authenticated) {
    return navigateTo('/login');
  }
});

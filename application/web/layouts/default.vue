<template>
  <header class="navbar bg-primary fixed top-0 w-full z-[1]">
    <div class="navbar-start">
      <div v-if="pageType === 'list'">
        <details class="dropdown" ref="menu-dropdown">
          <summary class="btn btn-ghost" @blur="handleBlurButton">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h8m-8 6h16"
              />
            </svg>
          </summary>
          <ul
            tabindex="0"
            class="menu dropdown-content mt-3 z-[1] p-2 pb-4 shadow bg-base-100 rounded-box w-52 text-base"
          >
            <li class="md:hidden">
              <NuxtLink to="/recettes/list">
                <CookingPot class="w-5 h-5" /> Recettes
              </NuxtLink>
            </li>
            <li class="md:hidden">
              <a>
                <ChefHat class="w-5 h-5" /> Réalisations
              </a>
            </li>
            <li class="h-px bg-100" />
            <li>
              <NuxtLink to="/categories/list">
                <FolderUp class="w-5 h-5" /> Catégories
              </NuxtLink>
            </li>
            <li class="h-px bg-100" />
            <li>
              <a
                v-if="authenticated"
                @click="logout"
              >
                <LogOut class="w-5 h-5" /> Déconnexion
              </a>
            </li>
          </ul>
        </details>
      </div>
      <NuxtLink
        v-if="pageType === 'detail'"
        @click="goBack()"
        class="hover:cursor-pointer btn btn-ghost"
      >
        <ArrowLeft />
      </NuxtLink>
      <div class="dropdown" v-if="pageType === 'detail'" />
      <a class="btn btn-ghost text-xl cursor-default">{{ pageName }}</a>
    </div>

    <div class="navbar-center hidden md:flex">
      <ul class="menu menu-horizontal px-1">
        <li>
          <NuxtLink to="/recettes/list">
            <CookingPot class="w-7 h-7" />
            <span class="text-base font-semibold">Recettes</span>
          </NuxtLink>
        </li>
        <li>
          <a>
            <ChefHat class="w-7 h-7" />
            <span class="text-base font-semibold">Réalisations</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="navbar-end" />
  </header>

  <main class="md:container md:mx-auto mx-2 md:max-w-[800px] mt-20 mb-4">
    <slot />
  </main>
</template>

<script setup lang="ts">

import { useApiAuth, navigateTo, useAuthStore, computed, useRoute, useRouter, useTemplateRef } from '#imports';
import { LogOut, CookingPot, ChefHat, FolderUp, ArrowLeft } from 'lucide-vue-next';
import { watch } from 'vue';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const authenticated = computed(() => authStore.authenticated);
const pageType = computed(() => useRoute().meta.pageType);
const pageName = computed(() => useRoute().meta.pageName);
const menuElement = useTemplateRef<HTMLDivElement>('menu-dropdown');

watch(
  () => route.path,
  () => {
    closeMenu();
  },
);

const logout = async () => {
  await useApiAuth().logout();

  return navigateTo('/login');
};

const goBack = () => {
  window.history.length > 1 ? router.go(-1) : router.push('/');
};

const closeMenu = () => {
  if (menuElement.value) {
    menuElement.value.removeAttribute('open');
  }
};

const handleBlurButton = (event) => {
  if (!menuElement.value.contains(event.relatedTarget)) {
    closeMenu();
  }
};

</script>

<template>
  <header class="navbar bg-primary fixed top-0 w-full">
    <div class="navbar-start">
      <div class="dropdown">
        <div
          tabindex="0"
          role="button"
          class="btn btn-ghost lg:hidden"
        >
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
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
          <li>
            <NuxtLink to="/recettes/list">
              <CookingPot class="w-5 h-5" /> Recettes
            </NuxtLink>
          </li>
          <li>
              <a>
                <ChefHat class="w-5 h-5" /> Réalisations
              </a>
            </li>
        </ul>
      </div>
      <a class="btn btn-ghost text-xl cursor-default">En Cuisine !</a>
    </div>

    <div class="navbar-center hidden lg:flex">
      <ul class="menu menu-horizontal px-1">
        <li>
          <NuxtLink to="/recettes/list">
            <CookingPot /> Recettes
          </NuxtLink>
        </li>
        <li>
          <a><ChefHat /> Réalisations</a>
        </li>
      </ul>
    </div>

    <div class="navbar-end">
      <button
        v-if="authenticated"
        class="btn btn-ghost"
        @click="logout"
      >
        <LogOut />
      </button>
    </div>
  </header>

  <main class="md:container md:mx-auto mx-auto md:max-w-[800px] my-4">
    <slot />
  </main>
</template>

<script setup lang="ts">

  import { useApiAuth, navigateTo, useAuthStore, computed } from '#imports';
  import { LogOut, CookingPot, ChefHat } from 'lucide-vue-next';

  const authStore = useAuthStore();
  const authenticated = computed(() => authStore.authenticated);

  const logout = async () => {
    await useApiAuth().logout();

    return navigateTo('/login');
  };
</script>

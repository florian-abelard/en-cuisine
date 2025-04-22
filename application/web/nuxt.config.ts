// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config';

export default defineNuxtConfig({
  imports: {
    autoImport: false,
  },
  typescript: {
    strict: false,
  },
  ssr: false,
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  modules: [
    '@hebilicious/vue-query-nuxt',
    '@vee-validate/nuxt',
    '@pinia/nuxt',
  ],
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.API_BASE_URL?.replace(/\/$/, '') || 'http://localhost:8080/api',
    },
  },
  vueQuery: {
    queryClientOptions: {
      defaultOptions: {
        queries: {
          refetchOnWindowFocus: false,
          placeholderData: {
            isLoading: true,
          },
        },
      },
    },
  },
});

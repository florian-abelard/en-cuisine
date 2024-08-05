import { useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";

export const useApiMedia = () => {

  const config = useRuntimeConfig();

  return {

    upload: async (file: File): Promise<Media> => {
      const formData = new FormData();
      formData.append('file', file);

      const response: Media = await $fetch('/media', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: formData,
      });

      response.contentUrl = `${config.public.apiBaseUrl}${response.contentUrl}`;

      return response;
    },
  };
};

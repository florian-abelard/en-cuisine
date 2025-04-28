import { useRuntimeConfig } from "#imports";
import type { Media } from "~/models/media";

export const useApiMedia = () => {

  const config = useRuntimeConfig();
  const mediaBaseUrl = config.public.apiBaseUrl.replace(/\/api$/, '');

  const denormalizer = (media: Media): Media => {
    media.url = `${mediaBaseUrl}${media.contentUrl}`;

    return media;
  };

  return {

    upload: async (file: File): Promise<Media> => {
      const formData = new FormData();
      formData.append('file', file);

      const media: Media = await $fetch('/media', {
        method: 'POST',
        baseURL: config.public.apiBaseUrl,
        body: formData,
      });

      return denormalizer(media);
    },

    denormalize: (media: Media): Media => denormalizer(media),
  };
};

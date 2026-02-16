export const formatDate = (date?: string): string => {
  if (!date) return '';

  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
};

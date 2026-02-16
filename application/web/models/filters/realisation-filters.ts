export interface RealisationFilters {
  [key: string]: string | null | undefined;
  recette?: string | null;
  'date[after]'?: string | null;
  'date[before]'?: string | null;
}

export interface RecetteFilters {
  [key: string]: string | null | undefined | string[];
  categorie?: string | null;
  pretDans?: string | null;
  ingredients?: string[];
  etiquettes?: string[];
}

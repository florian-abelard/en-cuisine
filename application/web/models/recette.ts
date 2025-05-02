import type { Categorie } from "./categorie";
import type { Etiquette } from "./etiquette";
import type { Ingredient } from "./ingredient";
import type { Media } from "./media";

export class Recette {
  public readonly '@context'?: string;
  public readonly '@id': string;
  public readonly '@type' = 'Recette';
  public readonly id: string;

  public libelle: string;
  public image?: Media;
  public categorie?: Categorie;
  public description?: string;
  public source?: string;
  public tempsPreparation?: string;
  public tempsCuisson?: string;
  public pretDans?: string;
  public ingredients?: Ingredient[];
  public etiquettes?: Etiquette[];
  public notes?: string;
}

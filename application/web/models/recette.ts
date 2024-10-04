import type { Categorie } from "./categorie";
import type { Media } from "./media";

export class Recette {
  public readonly '@context'?: string;
  public readonly '@id': string;
  public readonly '@type' = 'Recette';
  public readonly id: string;

  public libelle: string;
  public image?: Media | string;
  public categorie?: Categorie | string;
  public tempsPreparation?: string;
  public tempsCuisson?: string;
  public pretDans?: string;
}

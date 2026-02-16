import type { Recette } from "./recette";

export class Realisation {
  public readonly '@context'?: string;
  public readonly '@id': string;
  public readonly '@type' = 'Realisation';
  public readonly id: string;

  public recette?: Recette;
  public date?: string;
  public notes?: string;
}

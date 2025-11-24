export class Ingredient {
  public readonly '@context'?: string;
  public readonly '@id': string;
  public readonly '@type' = 'Ingredient';
  public readonly id: string;

  public libelle: string;
  public color?: string;
}

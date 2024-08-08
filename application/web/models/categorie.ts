export class Categorie {
  public readonly '@context'?: string;
  public readonly '@id': string;
  public readonly '@type' = 'Categorie';
  public readonly id: string;

  public libelle: string;
  public order?: number;
}

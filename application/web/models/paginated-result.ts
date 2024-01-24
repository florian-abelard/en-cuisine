export class PaginatedResult<T> {
  items: T[];
  itemsCount: number;

  constructor(items: T[], itemsCount: number) {
    this.items = items;
    this.itemsCount = itemsCount;
  }
}

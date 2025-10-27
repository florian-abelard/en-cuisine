
export const formatQueryParams = (filters: Record<string, null | unknown>): URLSearchParams => {
  const params = new URLSearchParams();

  for (const [filter, value] of Object.entries(filters)) {
    if (Array.isArray(value) && value.length !== 0) {
      for (const item of value) {
        params.append(filter, item);
      }
    } else if (typeof value === 'string' && value !== undefined && value !== null) {
      params.append(filter, value);
    }
  }

  return params;
};

export const defaultNormalizer = (data: unknown): unknown => {
  if (Array.isArray(data)) {
    return data.map((item) => defaultNormalizer(item));
  }

  if (data instanceof Object && typeof data['@id'] === 'string') {
    return data['@id'];
  }

  return data;
};

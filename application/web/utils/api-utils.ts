
export const formatQueryParams = (filters: Record<string, null | unknown>): URLSearchParams => {
  const params = new URLSearchParams();

  for (const [filter, value] of Object.entries(filters)) {
    if (value === null || value === undefined || Array.isArray(value) && value.length === 0) {
      continue;
    }

    const sanitizedValue = paramValueSanitizer(value);

    if (Array.isArray(sanitizedValue)) {
      for (const item of sanitizedValue) {
        params.append(filter + '[]', item);
      }
    } else {
      params.append(filter, sanitizedValue);
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

const paramValueSanitizer = (value: unknown): string | string[] => {
    if (Array.isArray(value) && value.length !== 0) {
      value = value.map((v) => paramValueSanitizer(v));
    } else if (value instanceof Object) {
      value = typeof value['@id'] === 'string' ? value['@id'] : JSON.stringify(value);
    } else if (typeof value === 'string') {
      value = value.trim();
    }

    return value as string | string[];
};

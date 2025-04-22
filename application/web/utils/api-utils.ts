
export const formatQueryParams = (filters: Record<string, unknown>): URLSearchParams => {
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

import { useRuntimeConfig } from "#imports";

export default new class AuthenticationService {

  private config = useRuntimeConfig();

  public async login(username: string, password: string): Promise<void> {
    await $fetch('/login', {
      method: 'POST',
      baseURL: this.config.public.apiBaseUrl,
      headers: {
        'Content-Type': 'application/json',
      },
      body: {
        username,
        password,
      },
    });
  }
};

import { useRuntimeConfig } from "#imports";

export default new class AuthService {

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

  public async logout(): Promise<void> {
    await $fetch('/logout', {
      method: 'POST',
      baseURL: this.config.public.apiBaseUrl,
    });
  }

  public async isAuthenticated(): Promise<boolean> {
    try {
      await $fetch('/authenticated', {
        method: 'GET',
        baseURL: this.config.public.apiBaseUrl,
      });

      return true;
    } catch (e) {
      return false;
    }
  }
};

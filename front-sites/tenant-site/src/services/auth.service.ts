import { api } from '@/config/api';
import type { AuthUser } from '@/types/auth.types';
import type { UserRole } from '@/types/auth.types';

export interface LoginPayload {
  identifiant: string;
  mot_de_passe: string;
  role: UserRole;
}

export interface LoginResponse {
  user: AuthUser;
  token: string;
}

export const authService = {
  login: (data: LoginPayload) =>
    api.post<LoginResponse>('/auth/login', data).then((r) => r.data),

  logout: () => api.post('/auth/logout').then((r) => r.data),

  me: () => api.get<{ user: AuthUser }>('/auth/me').then((r) => r.data),
};

const API_BASE = import.meta.env.VITE_API_URL || '';

function getToken(): string | null {
  return localStorage.getItem('admin_token');
}

export async function request<T>(
  path: string,
  options: RequestInit = {}
): Promise<T> {
  const token = getToken();
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    ...(options.headers as Record<string, string>),
  };
  if (token) (headers as Record<string, string>)['Authorization'] = `Bearer ${token}`;

  const res = await fetch(`${API_BASE}${path}`, { ...options, headers });
  const data = await res.json().catch(() => ({}));

  if (!res.ok) {
    const msg = data?.message ?? data?.errors?.detail ?? `Request failed (${res.status})`;
    throw new Error(Array.isArray(msg) ? msg[0] : msg);
  }
  return data as T;
}

// Auth
export interface Admin {
  id: string;
  name: string;
  email: string;
  state: string;
  created_at: string;
  updated_at: string;
}

export interface LoginResponse {
  success: boolean;
  access_token: string;
  token_type: string;
  expires_in: number;
  admin: Admin;
}

export function login(email: string, password: string) {
  return request<LoginResponse>('/api/v1/admin/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
}

export function logout() {
  return request<{ success: boolean }>('/api/v1/admin/logout', { method: 'POST' });
}

export function refreshToken() {
  return request<LoginResponse>('/api/v1/admin/refresh', { method: 'POST' });
}

export function getMe() {
  return request<{ success: boolean; data: Admin }>('/api/v1/admin/me');
}

// Tenants
export interface Domain {
  id: number;
  domain: string;
  tenant_id: string;
}

export interface TenantStats {
  users_count: number;
  database_size_bytes: number;
  database_size_mb: number;
}

export interface Tenant {
  id: string;
  data: Record<string, unknown> | null;
  created_at: string;
  updated_at: string;
  domains?: Domain[];
  locked?: boolean;
  stats?: TenantStats;
}

export interface TenantsResponse {
  success: boolean;
  data: {
    data: Tenant[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export function fetchTenants(page = 1, perPage = 15, withStats = false) {
  const params = new URLSearchParams({ page: String(page), per_page: String(perPage) });
  if (withStats) params.set('with_stats', '1');
  return request<TenantsResponse>(`/api/v1/tenant?${params}`);
}

export function fetchTenant(id: string, withStats = false) {
  const q = withStats ? '?with_stats=1' : '';
  return request<{ success: boolean; data: Tenant }>(`/api/v1/tenant/${id}${q}`);
}

export function createTenant(body: { id: string; data?: Record<string, unknown>; domains: string[] }) {
  return request<{ success: boolean; data: Tenant }>('/api/v1/tenant', {
    method: 'POST',
    body: JSON.stringify(body),
  });
}

export function updateTenant(id: string, body: { data?: Record<string, unknown>; domains?: string[] }) {
  return request<{ success: boolean; data: Tenant }>(`/api/v1/tenant/${id}`, {
    method: 'PUT',
    body: JSON.stringify(body),
  });
}

export function deleteTenant(id: string) {
  return request<{ success: boolean }>(`/api/v1/tenant/${id}`, { method: 'DELETE' });
}

export function cleanTenant(id: string) {
  return request<{ success: boolean; data: Tenant }>(`/api/v1/tenant/${id}/clean`, {
    method: 'POST',
  });
}

export function lockTenant(id: string) {
  return request<{ success: boolean; data: Tenant }>(`/api/v1/tenant/${id}/lock`, {
    method: 'POST',
  });
}

export function unlockTenant(id: string) {
  return request<{ success: boolean; data: Tenant }>(`/api/v1/tenant/${id}/unlock`, {
    method: 'POST',
  });
}

export interface DashboardStats {
  total_tenants: number;
  total_users: number;
  total_database_size_mb: number;
  by_tenant: Array<{
    id: string;
    locked: boolean;
    users_count: number;
    database_size_mb: number;
  }>;
}

export function fetchDashboardStats() {
  return request<{ success: boolean; data: DashboardStats }>('/api/v1/tenant/stats/dashboard');
}

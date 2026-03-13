import axios from 'axios';
import { useAuthStore } from '@/store/useAuthStore';

/**
 * Extracts tenant id from the current hostname.
 * - foo.onlineschoolmultitenant.com → "foo"
 * - foo.localhost → "foo"
 * - localhost (no subdomain) → use VITE_TENANT_ID for dev, or ""
 */
export function getTenantFromHost(): string {
  if (typeof window === 'undefined') return '';
  const hostname = window.location.hostname;
  const parts = hostname.split('.');
  if (parts.length >= 2) return parts[0];
  return '';
}

/**
 * Builds the API base URL for the current tenant.
 * If tenant is present (e.g. "foo"), prefixes it to the API host:
 * - Base https://api.onlineschoolmultitenant.com/api → https://foo.api.onlineschoolmultitenant.com/api
 * - Base http://localhost:8000/api → http://foo.localhost:8000/api (when host is foo.localhost)
 *
 * When no tenant is in the app URL (e.g. plain localhost), uses VITE_API_BASE_URL as-is
 * (optionally set VITE_TENANT_ID in .env for dev to force a tenant prefix).
 */
export function getApiBaseUrl(): string {
  const base = import.meta.env.VITE_API_BASE_URL || '/api';
  const tenant = getTenantFromHost() || import.meta.env.VITE_TENANT_ID || '';

  if (!tenant) return base;

  try {
    const url = new URL(base);
    // Prepend tenant to hostname: api.example.com → foo.api.example.com
    url.hostname = `${tenant}.${url.hostname}`;
    return url.origin + url.pathname.replace(/\/+$/, '') || url.origin + '/api';
  } catch {
    return base;
  }
}

const baseURL = getApiBaseUrl();

export const api = axios.create({
  baseURL,
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token;
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401) {
      useAuthStore.getState().logout();
      window.location.href = '/login';
    }
    if (err.response?.status === 403) {
      import('react-hot-toast').then(({ default: toast }) =>
        toast.error('Accès non autorisé')
      );
    }
    return Promise.reject(err);
  }
);

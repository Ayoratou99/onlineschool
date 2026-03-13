import { api } from '@/config/api';
import type { PortailActualite } from '@/types/actualite.types';

export interface ActualiteFilters {
  statut?: string;
  categorie?: string;
}

export const actualiteService = {
  list: (params?: ActualiteFilters) =>
    api.get<{ data: PortailActualite[] }>('/backoffice/actualites', { params }).then((r) => r.data),

  get: (id: string) => api.get<PortailActualite>(`/backoffice/actualites/${id}`).then((r) => r.data),

  create: (data: Partial<PortailActualite>) =>
    api.post<PortailActualite>('/backoffice/actualites', data).then((r) => r.data),

  update: (id: string, data: Partial<PortailActualite>) =>
    api.put<PortailActualite>(`/backoffice/actualites/${id}`, data).then((r) => r.data),

  delete: (id: string) => api.delete(`/backoffice/actualites/${id}`).then((r) => r.data),

  toggleEpingle: (id: string) =>
    api.put(`/backoffice/actualites/${id}/epingler`).then((r) => r.data),
};

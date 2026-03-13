import { api } from '@/config/api';
import type {
  PortailPublicData,
  PortailConfig,
  PortailHero,
  PortailStatsItem,
  PortailMenuItem,
  PortailContact,
  PortailGalerieItem,
  PortailSection,
} from '@/types/tenant.types';

export const portailService = {
  getPortailPublic: () => api.get<PortailPublicData>('/portail').then((r) => r.data),

  getConfig: () => api.get<PortailConfig>('/backoffice/parametrage/config').then((r) => r.data),
  updateConfig: (data: Partial<PortailConfig>) =>
    api.put('/backoffice/parametrage/config', data).then((r) => r.data),

  getHero: () => api.get<PortailHero>('/backoffice/parametrage/hero').then((r) => r.data),
  updateHero: (data: Partial<PortailHero>) =>
    api.put('/backoffice/parametrage/hero', data).then((r) => r.data),

  getMenu: () => api.get<PortailMenuItem[]>('/backoffice/parametrage/menu').then((r) => r.data),
  createMenuItem: (data: Partial<PortailMenuItem>) =>
    api.post('/backoffice/parametrage/menu', data).then((r) => r.data),
  updateMenuItem: (id: string, data: Partial<PortailMenuItem>) =>
    api.put(`/backoffice/parametrage/menu/${id}`, data).then((r) => r.data),
  deleteMenuItem: (id: string) => api.delete(`/backoffice/parametrage/menu/${id}`).then((r) => r.data),
  reorderMenu: (ids: string[]) =>
    api.put('/backoffice/parametrage/menu/reorder', { ids }).then((r) => r.data),

  getStats: () => api.get<PortailStatsItem[]>('/backoffice/parametrage/stats').then((r) => r.data),
  createStat: (data: Partial<PortailStatsItem>) =>
    api.post('/backoffice/parametrage/stats', data).then((r) => r.data),
  updateStat: (id: string, data: Partial<PortailStatsItem>) =>
    api.put(`/backoffice/parametrage/stats/${id}`, data).then((r) => r.data),
  deleteStat: (id: string) => api.delete(`/backoffice/parametrage/stats/${id}`).then((r) => r.data),
  reorderStats: (ids: string[]) =>
    api.put('/backoffice/parametrage/stats/reorder', { ids }).then((r) => r.data),

  getGalerie: () => api.get<PortailGalerieItem[]>('/backoffice/parametrage/galerie').then((r) => r.data),
  createGalerieItem: (data: Partial<PortailGalerieItem>) =>
    api.post('/backoffice/parametrage/galerie', data).then((r) => r.data),
  updateGalerieItem: (id: string, data: Partial<PortailGalerieItem>) =>
    api.put(`/backoffice/parametrage/galerie/${id}`, data).then((r) => r.data),
  deleteGalerieItem: (id: string) => api.delete(`/backoffice/parametrage/galerie/${id}`).then((r) => r.data),
  reorderGalerie: (ids: string[]) =>
    api.put('/backoffice/parametrage/galerie/reorder', { ids }).then((r) => r.data),

  getSections: () => api.get<PortailSection[]>('/backoffice/parametrage/sections').then((r) => r.data),
  createSection: (data: Partial<PortailSection>) =>
    api.post('/backoffice/parametrage/sections', data).then((r) => r.data),
  updateSection: (id: string, data: Partial<PortailSection>) =>
    api.put(`/backoffice/parametrage/sections/${id}`, data).then((r) => r.data),
  deleteSection: (id: string) => api.delete(`/backoffice/parametrage/sections/${id}`).then((r) => r.data),
  reorderSections: (ids: string[]) =>
    api.put('/backoffice/parametrage/sections/reorder', { ids }).then((r) => r.data),

  getContact: () => api.get<PortailContact>('/backoffice/parametrage/contact').then((r) => r.data),
  updateContact: (data: Partial<PortailContact>) =>
    api.put('/backoffice/parametrage/contact', data).then((r) => r.data),

  uploadImage: (file: File) => {
    const fd = new FormData();
    fd.append('file', file);
    return api.post<{ url: string }>('/backoffice/upload', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }).then((r) => r.data);
  },
};

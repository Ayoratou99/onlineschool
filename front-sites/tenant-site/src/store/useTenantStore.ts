import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import type {
  PortailConfig,
  PortailHero,
  PortailStatsItem,
  PortailMenuItem,
  PortailSection,
  PortailContact,
  PortailGalerieItem,
  PortailActualite,
} from '@/types/tenant.types';

export function applyTenantTheme(config: PortailConfig): void {
  const root = document.documentElement;
  if (config.couleur_primaire) root.style.setProperty('--tp', config.couleur_primaire);
  if (config.couleur_secondaire) root.style.setProperty('--ts', config.couleur_secondaire);
  if (config.couleur_texte) root.style.setProperty('--ink', config.couleur_texte);
}

interface TenantState {
  config: PortailConfig | null;
  hero: PortailHero | null;
  stats: PortailStatsItem[];
  menu: PortailMenuItem[];
  sections: PortailSection[];
  contact: PortailContact | null;
  galerie: PortailGalerieItem[];
  actualites: PortailActualite[];
  setPortailData: (data: {
    config?: PortailConfig | null;
    hero?: PortailHero | null;
    stats?: PortailStatsItem[];
    menu?: PortailMenuItem[];
    sections?: PortailSection[];
    contact?: PortailContact | null;
    galerie?: PortailGalerieItem[];
    actualites?: PortailActualite[];
  }) => void;
  clear: () => void;
}

const emptyState = {
  config: null,
  hero: null,
  stats: [],
  menu: [],
  sections: [],
  contact: null,
  galerie: [],
  actualites: [],
};

export const useTenantStore = create<TenantState>()(
  persist(
    (set) => ({
      ...emptyState,
      setPortailData: (data) =>
        set((s) => ({
          ...s,
          ...data,
        })),
      clear: () => set(emptyState),
    }),
    { name: 'tenant-portail' }
  )
);

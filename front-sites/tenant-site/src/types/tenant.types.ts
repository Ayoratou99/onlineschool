export interface PortailConfig {
  nom_etablissement?: string;
  slogan?: string;
  couleur_primaire?: string;
  couleur_secondaire?: string;
  couleur_texte?: string;
  logo_url?: string;
  favicon_url?: string;
}

export interface PortailHero {
  image_url?: string;
  badge_texte?: string;
  titre?: string;
  sous_titre?: string;
  bouton_principal?: string;
  bouton_secondaire?: string;
}

export interface PortailStatsItem {
  id: string;
  valeur: string;
  libelle: string;
  ordre: number;
}

export interface PortailMenuItem {
  id: string;
  libelle: string;
  url: string;
  is_active: boolean;
  ordre: number;
}

export interface PortailGalerieItem {
  id: string;
  image_url: string;
  legende?: string;
  alt_text?: string;
  ordre: number;
}

export interface PortailSection {
  id: string;
  type: string;
  titre?: string;
  config?: Record<string, unknown>;
  is_visible: boolean;
  ordre: number;
}

export interface PortailContact {
  adresse?: string;
  telephone?: string;
  email?: string;
  horaires_semaine?: string;
  horaires_samedi?: string;
  facebook_url?: string;
  twitter_url?: string;
  linkedin_url?: string;
  instagram_url?: string;
}

export interface PortailPublicData {
  config: PortailConfig;
  hero: PortailHero;
  stats: PortailStatsItem[];
  menu: PortailMenuItem[];
  sections: PortailSection[];
  contact: PortailContact;
  galerie: PortailGalerieItem[];
  actualites: PortailActualite[];
}

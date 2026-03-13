export type ActualiteStatut = 'brouillon' | 'publie';
export type ActualiteCategorie = 'info' | 'urgent' | 'evenement' | 'resultat';
export type ActualiteCiblage = 'tous' | 'etudiants' | 'staff';

export interface PortailActualite {
  id: string;
  titre: string;
  contenu: string;
  image_url?: string;
  categorie: ActualiteCategorie;
  ciblage: ActualiteCiblage;
  is_epingle: boolean;
  publie_le: string | null;
  auteur?: { nom: string; prenom: string };
  created_at: string;
}

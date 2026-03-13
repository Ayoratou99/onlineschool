export type UserRole =
  | 'ADMIN'
  | 'DIRECTION'
  | 'CHEF_DEPARTEMENT'
  | 'SCOLARITE'
  | 'ENSEIGNANT'
  | 'COMPTABLE'
  | 'ETUDIANT'
  | 'BIBLIOTHEQUE';

export interface AuthUser {
  id: string;
  nom: string;
  prenom: string;
  email: string;
  role: UserRole;
  avatar_url?: string;
  token: string;
}

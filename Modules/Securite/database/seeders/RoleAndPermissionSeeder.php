<?php

namespace Modules\Securite\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Securite\Models\Role;
use Modules\Securite\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            // Sécurité - Utilisateurs
            'VOIR_UTILISATEURS' => 'Voir la liste des utilisateurs',
            'VOIR_UTILISATEUR' => 'Voir un utilisateur',
            'CREER_UTILISATEUR' => 'Créer un utilisateur',
            'MODIFIER_UTILISATEUR' => 'Modifier un utilisateur',
            'SUPPRIMER_UTILISATEUR' => 'Supprimer un utilisateur',
            'REINITIALISER_2FA' => 'Réinitialiser l\'authentification à deux facteurs',
            // Sécurité - Rôles
            'VOIR_ROLES' => 'Voir la liste des rôles',
            'VOIR_ROLE' => 'Voir un rôle',
            'CREER_ROLE' => 'Créer un rôle',
            'MODIFIER_ROLE' => 'Modifier un rôle',
            'SUPPRIMER_ROLE' => 'Supprimer un rôle',
            // Sécurité - Permissions
            'VOIR_PERMISSIONS' => 'Voir la liste des permissions',
            'VOIR_PERMISSION' => 'Voir une permission',
            'CREER_PERMISSION' => 'Créer une permission',
            'MODIFIER_PERMISSION' => 'Modifier une permission',
            'SUPPRIMER_PERMISSION' => 'Supprimer une permission',
            // Académique - Bâtiments
            'VOIR_BATIMENTS' => 'Voir la liste des bâtiments',
            'VOIR_BATIMENT' => 'Voir un bâtiment',
            'CREER_BATIMENT' => 'Créer un bâtiment',
            'MODIFIER_BATIMENT' => 'Modifier un bâtiment',
            'SUPPRIMER_BATIMENT' => 'Supprimer un bâtiment',
            // Académique - Cycles
            'VOIR_CYCLES' => 'Voir la liste des cycles',
            'VOIR_CYCLE' => 'Voir un cycle',
            'CREER_CYCLE' => 'Créer un cycle',
            'MODIFIER_CYCLE' => 'Modifier un cycle',
            'SUPPRIMER_CYCLE' => 'Supprimer un cycle',
            // Académique - Établissements
            'VOIR_ETABLISSEMENTS' => 'Voir la liste des établissements',
            'VOIR_ETABLISSEMENT' => 'Voir un établissement',
            'CREER_ETABLISSEMENT' => 'Créer un établissement',
            'MODIFIER_ETABLISSEMENT' => 'Modifier un établissement',
            'SUPPRIMER_ETABLISSEMENT' => 'Supprimer un établissement',
            // Académique - Étages
            'VOIR_ETAGES' => 'Voir la liste des étages',
            'VOIR_ETAGE' => 'Voir un étage',
            'CREER_ETAGE' => 'Créer un étage',
            'MODIFIER_ETAGE' => 'Modifier un étage',
            'SUPPRIMER_ETAGE' => 'Supprimer un étage',
            // Académique - Filières
            'VOIR_FILIERES' => 'Voir la liste des filières',
            'VOIR_FILIERE' => 'Voir une filière',
            'CREER_FILIERE' => 'Créer une filière',
            'MODIFIER_FILIERE' => 'Modifier une filière',
            'SUPPRIMER_FILIERE' => 'Supprimer une filière',
            // Académique - Groupes
            'VOIR_GROUPES' => 'Voir la liste des groupes',
            'VOIR_GROUPE' => 'Voir un groupe',
            'CREER_GROUPE' => 'Créer un groupe',
            'MODIFIER_GROUPE' => 'Modifier un groupe',
            'SUPPRIMER_GROUPE' => 'Supprimer un groupe',
            // Académique - Matières
            'VOIR_MATIERES' => 'Voir la liste des matières',
            'VOIR_MATIERE' => 'Voir une matière',
            'CREER_MATIERE' => 'Créer une matière',
            'MODIFIER_MATIERE' => 'Modifier une matière',
            'SUPPRIMER_MATIERE' => 'Supprimer une matière',
            // Académique - Matière-Enseignant
            'VOIR_MATIERE_ENSEIGNANTS' => 'Voir la liste des affectations matière-enseignant',
            'VOIR_MATIERE_ENSEIGNANT' => 'Voir une affectation matière-enseignant',
            'CREER_MATIERE_ENSEIGNANT' => 'Créer une affectation matière-enseignant',
            'MODIFIER_MATIERE_ENSEIGNANT' => 'Modifier une affectation matière-enseignant',
            'SUPPRIMER_MATIERE_ENSEIGNANT' => 'Supprimer une affectation matière-enseignant',
            // Académique - Niveaux
            'VOIR_NIVEAUX' => 'Voir la liste des niveaux',
            'VOIR_NIVEAU' => 'Voir un niveau',
            'CREER_NIVEAU' => 'Créer un niveau',
            'MODIFIER_NIVEAU' => 'Modifier un niveau',
            'SUPPRIMER_NIVEAU' => 'Supprimer un niveau',
            // Académique - Parcours
            'VOIR_PARCOURS' => 'Voir la liste / un parcours',
            'CREER_PARCOURS' => 'Créer un parcours',
            'MODIFIER_PARCOURS' => 'Modifier un parcours',
            'SUPPRIMER_PARCOURS' => 'Supprimer un parcours',
            // Académique - Programmes
            'VOIR_PROGRAMMES' => 'Voir la liste des programmes',
            'VOIR_PROGRAMME' => 'Voir un programme',
            'CREER_PROGRAMME' => 'Créer un programme',
            'MODIFIER_PROGRAMME' => 'Modifier un programme',
            'SUPPRIMER_PROGRAMME' => 'Supprimer un programme',
            // Académique - Détails de programme
            'VOIR_PROGRAMME_DETAILS' => 'Voir la liste des détails de programme',
            'VOIR_PROGRAMME_DETAIL' => 'Voir un détail de programme',
            'CREER_PROGRAMME_DETAIL' => 'Créer un détail de programme',
            'MODIFIER_PROGRAMME_DETAIL' => 'Modifier un détail de programme',
            'SUPPRIMER_PROGRAMME_DETAIL' => 'Supprimer un détail de programme',
            // Académique - Salles
            'VOIR_SALLES' => 'Voir la liste des salles',
            'VOIR_SALLE' => 'Voir une salle',
            'CREER_SALLE' => 'Créer une salle',
            'MODIFIER_SALLE' => 'Modifier une salle',
            'SUPPRIMER_SALLE' => 'Supprimer une salle',
            // Académique - Indisponibilités de salle
            'VOIR_SALLE_INDISPOS' => 'Voir la liste des indisponibilités de salle',
            'VOIR_SALLE_INDISPO' => 'Voir une indisponibilité de salle',
            'CREER_SALLE_INDISPO' => 'Créer une indisponibilité de salle',
            'MODIFIER_SALLE_INDISPO' => 'Modifier une indisponibilité de salle',
            'SUPPRIMER_SALLE_INDISPO' => 'Supprimer une indisponibilité de salle',
            // Académique - Semestres
            'VOIR_SEMESTRES' => 'Voir la liste des semestres',
            'VOIR_SEMESTRE' => 'Voir un semestre',
            'CREER_SEMESTRE' => 'Créer un semestre',
            'MODIFIER_SEMESTRE' => 'Modifier un semestre',
            'SUPPRIMER_SEMESTRE' => 'Supprimer un semestre',
            // Académique - Unités d'enseignement
            'VOIR_UE' => 'Voir les unités d\'enseignement',
            'CREER_UE' => 'Créer une unité d\'enseignement',
            'MODIFIER_UE' => 'Modifier une unité d\'enseignement',
            'SUPPRIMER_UE' => 'Supprimer une unité d\'enseignement',
            // Académique - Emplois du temps
            'VOIR_EMPLOIS_DU_TEMPS' => 'Voir la liste des emplois du temps',
            'VOIR_EMPLOI_DU_TEMPS' => 'Voir un emploi du temps',
            'CREER_EMPLOI_DU_TEMPS' => 'Créer un emploi du temps',
            'MODIFIER_EMPLOI_DU_TEMPS' => 'Modifier un emploi du temps',
            'SUPPRIMER_EMPLOI_DU_TEMPS' => 'Supprimer un emploi du temps',
            // Académique - Exceptions emploi du temps
            'VOIR_EDT_EXCEPTIONS' => 'Voir la liste des exceptions d\'emploi du temps',
            'VOIR_EDT_EXCEPTION' => 'Voir une exception d\'emploi du temps',
            'CREER_EDT_EXCEPTION' => 'Créer une exception d\'emploi du temps',
            'MODIFIER_EDT_EXCEPTION' => 'Modifier une exception d\'emploi du temps',
            'SUPPRIMER_EDT_EXCEPTION' => 'Supprimer une exception d\'emploi du temps',
            // Académique - Domaines
            'VOIR_DOMAINES' => 'Voir la liste des domaines',
            'VOIR_DOMAINE' => 'Voir un domaine',
            'CREER_DOMAINE' => 'Créer un domaine',
            'MODIFIER_DOMAINE' => 'Modifier un domaine',
            'SUPPRIMER_DOMAINE' => 'Supprimer un domaine',
            // Paramétrage - Barème mention
            'VOIR_BAREMES_MENTION' => 'Voir la liste des barèmes de mention',
            'VOIR_BAREME_MENTION' => 'Voir un barème de mention',
            'CREER_BAREME_MENTION' => 'Créer un barème de mention',
            'MODIFIER_BAREME_MENTION' => 'Modifier un barème de mention',
            'SUPPRIMER_BAREME_MENTION' => 'Supprimer un barème de mention',
            // Paramétrage - Années académiques
            'VOIR_ANNEES_ACADEMIQUES' => 'Voir la liste des années académiques',
            'VOIR_ANNEE_ACADEMIQUE' => 'Voir une année académique',
            'CREER_ANNEE_ACADEMIQUE' => 'Créer une année académique',
            'MODIFIER_ANNEE_ACADEMIQUE' => 'Modifier une année académique',
            'SUPPRIMER_ANNEE_ACADEMIQUE' => 'Supprimer une année académique',
            // Document - Modèles de document
            'VOIR_TEMPLATES_DOCUMENTS' => 'Voir la liste des modèles de documents',
            'VOIR_TEMPLATE_DOCUMENT' => 'Voir un modèle de document',
            'CREER_TEMPLATE_DOCUMENT' => 'Créer un modèle de document',
            'MODIFIER_TEMPLATE_DOCUMENT' => 'Modifier un modèle de document',
            'SUPPRIMER_TEMPLATE_DOCUMENT' => 'Supprimer un modèle de document',
            // Document - Documents générés
            'VOIR_DOCUMENT_GENERE' => 'Voir les documents générés',
            // ActivityLog - Journaux d'activité
            'VOIR_JOURNAUX_ACTIVITE' => 'Voir la liste des journaux d\'activité',
            'VOIR_JOURNAL_ACTIVITE' => 'Voir un journal d\'activité',
        ];


        $permissionIds = [];
        foreach ($perms as $name => $desc) {
            $p = Permission::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $desc,
                    'state' => 'ACTIVE'
                ]
            );
            $permissionIds[] = $p->id;
        }

        $adminRole = Role::firstOrCreate(
            ['name' => 'ADMIN'],
            [
                'description' => 'Super Administrator',
                'state' => 'ACTIVE'
            ]
        );

        $adminRole->permissions()->sync($permissionIds);
    }
}
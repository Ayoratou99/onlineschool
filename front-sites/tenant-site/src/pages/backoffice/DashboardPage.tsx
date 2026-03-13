import { Link } from 'react-router-dom';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission } from '@/router/permissions';
import type { UserRole } from '@/types/auth.types';
import Badge from '@/components/ui/Badge';
import Card from '@/components/ui/Card';
import { Settings, Newspaper, ExternalLink } from 'lucide-react';

export default function DashboardPage() {
  const user = useAuthStore((s) => s.user);
  const role = (user?.role || 'ETUDIANT') as UserRole;

  const hasParametrage = hasPermission(role, 'PARAMETRAGE_IDENTITE');
  const hasActualites = hasPermission(role, 'ACTUALITES_VIEW');

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-2">
        Bonjour, {user?.prenom || 'Utilisateur'}
      </h1>
      <Badge variant="primary" className="mb-6">
        {role}
      </Badge>
      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {hasParametrage && (
          <Link to="/backoffice/parametrage/identite">
            <Card className="hover:shadow-md transition h-full">
              <div className="flex items-center gap-3">
                <div className="p-2 rounded-lg bg-primary/10">
                  <Settings className="w-6 h-6 text-primary" />
                </div>
                <div>
                  <h2 className="font-semibold text-ink">Paramétrage du portail</h2>
                  <p className="text-sm text-mute">Identité, hero, menu, etc.</p>
                </div>
              </div>
            </Card>
          </Link>
        )}
        {hasActualites && (
          <Link to="/backoffice/actualites">
            <Card className="hover:shadow-md transition h-full">
              <div className="flex items-center gap-3">
                <div className="p-2 rounded-lg bg-secondary/20">
                  <Newspaper className="w-6 h-6 text-ink" />
                </div>
                <div>
                  <h2 className="font-semibold text-ink">Actualités</h2>
                  <p className="text-sm text-mute">Gérer les actualités</p>
                </div>
              </div>
            </Card>
          </Link>
        )}
        <a href="/" target="_blank" rel="noopener noreferrer">
          <Card className="hover:shadow-md transition h-full">
            <div className="flex items-center gap-3">
              <div className="p-2 rounded-lg bg-black/5">
                <ExternalLink className="w-6 h-6 text-ink" />
              </div>
              <div>
                <h2 className="font-semibold text-ink">Voir le site public</h2>
                <p className="text-sm text-mute">Ouvrir dans un nouvel onglet</p>
              </div>
            </div>
          </Card>
        </a>
      </div>
    </div>
  );
}

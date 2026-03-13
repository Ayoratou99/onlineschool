import { Link } from 'react-router-dom';
import { useAuthStore } from '@/store/useAuthStore';
import { useTenantStore } from '@/store/useTenantStore';

export default function Topbar() {
  const user = useAuthStore((s) => s.user);
  const config = useTenantStore((s) => s.config);

  return (
    <header className="h-14 border-b border-border bg-white flex items-center justify-between px-4">
      <span className="font-medium text-ink">{config?.nom_etablissement || 'Backoffice'}</span>
      <div className="flex items-center gap-4">
        <a
          href="/"
          target="_blank"
          rel="noopener noreferrer"
          className="text-sm text-primary hover:underline"
        >
          Voir le site public
        </a>
        {user && (
          <span className="text-sm text-mute">
            {user.prenom} {user.nom}
          </span>
        )}
      </div>
    </header>
  );
}

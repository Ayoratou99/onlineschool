import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import toast from 'react-hot-toast';
import { actualiteService } from '@/services/actualite.service';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission } from '@/router/permissions';
import type { UserRole } from '@/types/auth.types';
import type { PortailActualite } from '@/types/actualite.types';
import Button from '@/components/ui/Button';
import ActualiteCard from '@/components/backoffice/ActualiteCard';

export default function ActualitesListPage() {
  const navigate = useNavigate();
  const user = useAuthStore((s) => s.user);
  const role = (user?.role || 'ETUDIANT') as UserRole;
  const [items, setItems] = useState<PortailActualite[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<'all' | 'publie' | 'brouillon'>('all');

  const load = () => {
    const params = filter === 'publie' ? { statut: 'publie' } : filter === 'brouillon' ? { statut: 'brouillon' } : {};
    actualiteService.list(params).then((r) => setItems(r.data || [])).catch(() => setItems([])).finally(() => setLoading(false));
  };

  useEffect(() => {
    setLoading(true);
    load();
  }, [filter]);

  const handleDelete = async (id: string) => {
    if (!confirm('Supprimer cette actualité ?')) return;
    try {
      await actualiteService.delete(id);
      load();
      toast.success('Supprimée');
    } catch {
      toast.error('Erreur');
    }
  };

  const handleEpingle = async (id: string) => {
    try {
      await actualiteService.toggleEpingle(id);
      load();
      toast.success('Modifié');
    } catch {
      toast.error('Erreur');
    }
  };

  const canCreate = hasPermission(role, 'ACTUALITES_CREATE');
  const canEdit = hasPermission(role, 'ACTUALITES_EDIT');
  const canDelete = hasPermission(role, 'ACTUALITES_DELETE');
  const canEpingle = hasPermission(role, 'ACTUALITES_EPINGLER');

  const sorted = [...items].sort((a, b) => {
    if (a.is_epingle && !b.is_epingle) return -1;
    if (!a.is_epingle && b.is_epingle) return 1;
    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime();
  });

  return (
    <div>
      <div className="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 className="text-2xl font-bold text-ink">Actualités</h1>
        {canCreate && (
          <Button onClick={() => navigate('/backoffice/actualites/nouvelle')}>+ Nouvelle actualité</Button>
        )}
      </div>
      <div className="flex gap-2 mb-4">
        {(['all', 'publie', 'brouillon'] as const).map((f) => (
          <button
            key={f}
            type="button"
            onClick={() => setFilter(f)}
            className={`px-3 py-1.5 rounded-lg text-sm font-medium ${
              filter === f ? 'bg-primary text-white' : 'bg-black/5 text-ink hover:bg-black/10'
            }`}
          >
            {f === 'all' ? 'Tout' : f === 'publie' ? 'Publiées' : 'Brouillons'}
          </button>
        ))}
      </div>
      {loading ? (
        <div className="text-mute">Chargement...</div>
      ) : (
        <ul className="space-y-4">
          {sorted.map((item) => (
            <li key={item.id}>
              <ActualiteCard
                item={item}
                onEdit={(id) => navigate(`/backoffice/actualites/${id}/modifier`)}
                onDelete={handleDelete}
                onEpingle={handleEpingle}
                canEdit={canEdit}
                canDelete={canDelete}
                canEpingle={canEpingle}
              />
            </li>
          ))}
        </ul>
      )}
      {!loading && items.length === 0 && (
        <p className="text-mute">Aucune actualité.</p>
      )}
    </div>
  );
}

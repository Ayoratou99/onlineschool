import type { PortailActualite } from '@/types/actualite.types';
import Badge from '@/components/ui/Badge';
import Button from '@/components/ui/Button';

const catLabels: Record<string, string> = {
  info: 'Info',
  urgent: 'Urgent',
  evenement: 'Événement',
  resultat: 'Résultat',
};

interface ActualiteCardProps {
  item: PortailActualite;
  onEdit?: (id: string) => void;
  onDelete?: (id: string) => void;
  onEpingle?: (id: string) => void;
  canEdit?: boolean;
  canDelete?: boolean;
  canEpingle?: boolean;
}

export default function ActualiteCard({
  item,
  onEdit,
  onDelete,
  onEpingle,
  canEdit,
  canDelete,
  canEpingle,
}: ActualiteCardProps) {
  return (
    <div className="border border-border rounded-lg p-4 bg-white">
      <div className="flex items-start justify-between gap-2">
        <div className="min-w-0 flex-1">
          <div className="flex items-center gap-2 flex-wrap">
            <Badge variant="primary">{catLabels[item.categorie] || item.categorie}</Badge>
            {item.is_epingle && <Badge variant="warning">📌 Épinglée</Badge>}
            {!item.publie_le && <Badge variant="mute">Brouillon</Badge>}
          </div>
          <h3 className="font-semibold text-ink mt-2">{item.titre}</h3>
          <p className="text-sm text-mute mt-1 line-clamp-2">{item.contenu}</p>
          {item.auteur && (
            <p className="text-xs text-mute mt-2">
              {item.auteur.prenom} {item.auteur.nom}
            </p>
          )}
        </div>
        <div className="flex gap-1 flex-shrink-0">
          {canEpingle && onEpingle && (
            <Button size="sm" variant="ghost" onClick={() => onEpingle(item.id)}>
              📌
            </Button>
          )}
          {canEdit && onEdit && (
            <Button size="sm" variant="ghost" onClick={() => onEdit(item.id)}>
              Éditer
            </Button>
          )}
          {canDelete && onDelete && (
            <Button size="sm" variant="ghost" onClick={() => onDelete(item.id)} className="text-red-600">
              Suppr.
            </Button>
          )}
        </div>
      </div>
    </div>
  );
}

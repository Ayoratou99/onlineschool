import { useEffect, useState } from 'react';
import { portailService } from '@/services/portail.service';
import type { PortailSection } from '@/types/tenant.types';
import Badge from '@/components/ui/Badge';

const typeLabels: Record<string, string> = {
  texte: 'Texte',
  image: 'Image',
  galerie: 'Galerie',
  stats: 'Stats',
  colonnes: 'Colonnes',
  actualites: 'Actualités',
  contact: 'Contact',
};

export default function SectionsPage() {
  const [sections, setSections] = useState<PortailSection[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    portailService.getSections().then(setSections).catch(() => {}).finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="text-mute">Chargement...</div>;

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Sections</h1>
      <ul className="space-y-2">
        {sections.map((s) => (
          <li key={s.id} className="flex items-center gap-4 p-3 border border-border rounded-lg bg-white">
            <Badge variant="primary">{typeLabels[s.type] || s.type}</Badge>
            <span className="flex-1 font-medium">{s.titre || 'Sans titre'}</span>
            <span className="text-sm text-mute">{s.is_visible ? 'Visible' : 'Masquée'}</span>
            <button type="button" className="text-primary text-sm">Éditer</button>
            <button type="button" className="text-mute text-sm">Masquer</button>
            <button type="button" className="text-red-600 text-sm">Supprimer</button>
          </li>
        ))}
      </ul>
      <p className="mt-4 text-sm text-mute">+ Ajouter une section (modal choix du type)</p>
    </div>
  );
}

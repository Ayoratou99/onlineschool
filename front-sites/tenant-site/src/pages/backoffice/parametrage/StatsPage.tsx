import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import type { PortailStatsItem } from '@/types/tenant.types';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';

export default function StatsPage() {
  const [items, setItems] = useState<PortailStatsItem[]>([]);
  const [loading, setLoading] = useState(true);

  const load = () => {
    portailService.getStats().then(setItems).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => load(), []);

  const add = async () => {
    try {
      await portailService.createStat({ valeur: '', libelle: '', ordre: items.length });
      load();
      toast.success('Statistique ajoutée');
    } catch {
      toast.error('Erreur');
    }
  };

  const remove = async (id: string) => {
    try {
      await portailService.deleteStat(id);
      load();
      toast.success('Supprimée');
    } catch {
      toast.error('Erreur');
    }
  };

  const update = async (id: string, data: Partial<PortailStatsItem>) => {
    try {
      await portailService.updateStat(id, data);
      load();
    } catch {
      toast.error('Erreur');
    }
  };

  if (loading) return <div className="text-mute">Chargement...</div>;

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Stats (barre hero)</h1>
      <ul className="space-y-2 mb-6">
        {items.map((item) => (
          <li key={item.id} className="flex items-center gap-4 p-3 border border-border rounded-lg bg-white">
            <span className="text-mute cursor-move">⠿</span>
            <Input
              className="w-20"
              value={item.valeur}
              onChange={(e) => update(item.id, { valeur: e.target.value })}
            />
            <Input
              className="flex-1"
              value={item.libelle}
              onChange={(e) => update(item.id, { libelle: e.target.value })}
            />
            <Button size="sm" variant="ghost" onClick={() => remove(item.id)} className="text-red-600">
              Supprimer
            </Button>
          </li>
        ))}
      </ul>
      <Button onClick={add}>Ajouter une stat</Button>
      {items.length > 0 && (
        <div className="mt-8 p-4 bg-black/5 rounded-lg">
          <p className="text-sm text-mute mb-2">Aperçu</p>
          <div className="flex gap-4 flex-wrap">
            {items.map((s) => (
              <div key={s.id} className="text-center">
                <span className="font-bold text-primary">{s.valeur || '—'}</span>
                <span className="text-sm text-mute ml-1">{s.libelle || 'Libellé'}</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

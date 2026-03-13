import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import type { PortailMenuItem } from '@/types/tenant.types';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';

export default function MenuPage() {
  const [items, setItems] = useState<PortailMenuItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [newLibelle, setNewLibelle] = useState('');
  const [newUrl, setNewUrl] = useState('');

  const load = () => {
    portailService.getMenu().then(setItems).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => load(), []);

  const add = async () => {
    if (!newLibelle.trim() || !newUrl.trim()) return;
    try {
      await portailService.createMenuItem({ libelle: newLibelle, url: newUrl, is_active: true, ordre: items.length });
      setNewLibelle('');
      setNewUrl('');
      load();
      toast.success('Élément ajouté');
    } catch {
      toast.error('Erreur');
    }
  };

  const remove = async (id: string) => {
    try {
      await portailService.deleteMenuItem(id);
      load();
      toast.success('Supprimé');
    } catch {
      toast.error('Erreur');
    }
  };

  const toggleActive = async (item: PortailMenuItem) => {
    try {
      await portailService.updateMenuItem(item.id, { ...item, is_active: !item.is_active });
      load();
    } catch {
      toast.error('Erreur');
    }
  };

  if (loading) return <div className="text-mute">Chargement...</div>;

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Menu</h1>
      <ul className="space-y-2 mb-8">
        {items.map((item) => (
          <li key={item.id} className="flex items-center gap-4 p-3 border border-border rounded-lg bg-white">
            <span className="text-mute cursor-move" title="Glisser">⠿</span>
            <span className="flex-1 font-medium">{item.libelle}</span>
            <span className="text-sm text-mute truncate max-w-[200px]">{item.url}</span>
            <label className="flex items-center gap-2">
              <input
                type="checkbox"
                checked={item.is_active}
                onChange={() => toggleActive(item)}
                className="rounded"
              />
              Actif
            </label>
            <Button size="sm" variant="ghost" onClick={() => remove(item.id)} className="text-red-600">
              Supprimer
            </Button>
          </li>
        ))}
      </ul>
      <div className="flex gap-2 flex-wrap items-end">
        <Input
          placeholder="Libellé"
          value={newLibelle}
          onChange={(e) => setNewLibelle(e.target.value)}
          className="w-48"
        />
        <Input
          placeholder="URL"
          value={newUrl}
          onChange={(e) => setNewUrl(e.target.value)}
          className="w-64"
        />
        <Button onClick={add}>Ajouter</Button>
      </div>
    </div>
  );
}

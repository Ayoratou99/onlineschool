import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import type { PortailGalerieItem } from '@/types/tenant.types';
import Button from '@/components/ui/Button';
import ImageUpload from '@/components/ui/ImageUpload';
import Modal from '@/components/ui/Modal';
import Input from '@/components/ui/Input';

export default function GaleriePage() {
  const [items, setItems] = useState<PortailGalerieItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [editId, setEditId] = useState<string | null>(null);
  const [editLegende, setEditLegende] = useState('');
  const [editAlt, setEditAlt] = useState('');

  const load = () => {
    portailService.getGalerie().then(setItems).catch(() => {}).finally(() => setLoading(false));
  };

  useEffect(() => load(), []);

  const remove = async (id: string) => {
    try {
      await portailService.deleteGalerieItem(id);
      load();
      toast.success('Photo supprimée');
    } catch {
      toast.error('Erreur');
    }
  };

  const openEdit = (item: PortailGalerieItem) => {
    setEditId(item.id);
    setEditLegende(item.legende || '');
    setEditAlt(item.alt_text || '');
  };

  const saveEdit = async () => {
    if (!editId) return;
    try {
      await portailService.updateGalerieItem(editId, { legende: editLegende, alt_text: editAlt });
      load();
      setEditId(null);
      toast.success('Modifications enregistrées');
    } catch {
      toast.error('Erreur');
    }
  };

  const addPhoto = (url: string) => {
    portailService.createGalerieItem({ image_url: url, ordre: items.length }).then(() => {
      load();
      toast.success('Photo ajoutée');
    }).catch(() => toast.error('Erreur'));
  };

  if (loading) return <div className="text-mute">Chargement...</div>;

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Galerie</h1>
      <div className="grid grid-cols-3 gap-4 mb-6">
        {items.map((item, i) => (
          <div key={item.id} className="relative group rounded-lg overflow-hidden border border-border aspect-square">
            <img src={item.image_url} alt={item.alt_text || item.legende || ''} className="w-full h-full object-cover" />
            {i === 0 && (
              <span className="absolute top-2 left-2 px-2 py-0.5 bg-primary text-white text-xs rounded">
                1ère
              </span>
            )}
            <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
              <Button size="sm" variant="ghost" onClick={() => openEdit(item)} className="text-white">
                ✏️ Légende
              </Button>
              <Button size="sm" variant="ghost" onClick={() => remove(item.id)} className="text-red-300">
                ✕
              </Button>
            </div>
          </div>
        ))}
        <div className="aspect-square border-2 border-dashed border-border rounded-lg flex items-center justify-center hover:bg-off transition">
          <ImageUpload value="" onChange={addPhoto} aspectRatio="1/1" label="+ Ajouter des photos" />
        </div>
      </div>
      <Modal open={!!editId} onClose={() => setEditId(null)} title="Éditer la légende" size="sm">
        <div className="space-y-4">
          <Input label="Légende" value={editLegende} onChange={(e) => setEditLegende(e.target.value)} />
          <Input label="Texte alternatif" value={editAlt} onChange={(e) => setEditAlt(e.target.value)} />
          <Button onClick={saveEdit}>Enregistrer</Button>
        </div>
      </Modal>
    </div>
  );
}

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import { useNavigate, useParams } from 'react-router-dom';
import toast from 'react-hot-toast';
import { actualiteService } from '@/services/actualite.service';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission } from '@/router/permissions';
import type { UserRole } from '@/types/auth.types';
import type { ActualiteCategorie, ActualiteCiblage } from '@/types/actualite.types';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';
import Textarea from '@/components/ui/Textarea';
import Select from '@/components/ui/Select';
import ImageUpload from '@/components/ui/ImageUpload';

const schema = z.object({
  titre: z.string().min(1, 'Titre requis'),
  contenu: z.string(),
  image_url: z.string(),
  categorie: z.enum(['info', 'urgent', 'evenement', 'resultat']),
  ciblage: z.enum(['tous', 'etudiants', 'staff']),
  is_epingle: z.boolean(),
  publie_le: z.string(),
});

type FormData = z.infer<typeof schema>;

const categorieOptions = [
  { value: 'info', label: 'Info' },
  { value: 'urgent', label: 'Urgent' },
  { value: 'evenement', label: 'Événement' },
  { value: 'resultat', label: 'Résultat' },
];

const ciblageOptions = [
  { value: 'tous', label: 'Tous' },
  { value: 'etudiants', label: 'Étudiants seulement' },
  { value: 'staff', label: 'Staff seulement' },
];

export default function ActualiteFormPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const user = useAuthStore((s) => s.user);
  const role = (user?.role || 'ETUDIANT') as UserRole;
  const [loading, setLoading] = useState(false);
  const isEdit = !!id;

  const canEpingle = hasPermission(role, 'ACTUALITES_EPINGLER');

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      categorie: 'info',
      ciblage: 'tous',
      is_epingle: false,
      publie_le: '',
    },
  });

  useEffect(() => {
    if (isEdit && id) {
      actualiteService.get(id).then((data) => {
        setValue('titre', data.titre);
        setValue('contenu', data.contenu);
        setValue('image_url', data.image_url || '');
        setValue('categorie', data.categorie as ActualiteCategorie);
        setValue('ciblage', data.ciblage as ActualiteCiblage);
        setValue('is_epingle', data.is_epingle);
        setValue('publie_le', data.publie_le ? data.publie_le.slice(0, 16) : '');
      }).catch(() => toast.error('Actualité introuvable'));
    }
  }, [id, isEdit, setValue]);

  const onSubmit = async (data: FormData) => {
    setLoading(true);
    try {
      const payload = {
        ...data,
        publie_le: data.publie_le ? new Date(data.publie_le).toISOString() : null,
      };
      if (isEdit && id) {
        await actualiteService.update(id, payload);
        toast.success('Actualité mise à jour');
      } else {
        await actualiteService.create(payload);
        toast.success('Actualité créée');
      }
      navigate('/backoffice/actualites');
    } catch {
      toast.error('Erreur');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">
        {isEdit ? 'Modifier l\'actualité' : 'Nouvelle actualité'}
      </h1>
      <form onSubmit={handleSubmit(onSubmit)} className="max-w-2xl space-y-6">
        <Input label="Titre" {...register('titre')} error={errors.titre?.message} />
        <Textarea label="Contenu" {...register('contenu')} />
        <ImageUpload
          label="Image (optionnel)"
          value={watch('image_url')}
          onChange={(v) => setValue('image_url', v)}
          onRemove={() => setValue('image_url', '')}
        />
        <Select label="Catégorie" options={categorieOptions} {...register('categorie')} />
        <Select label="Ciblage" options={ciblageOptions} {...register('ciblage')} />
        {canEpingle && (
          <label className="flex items-center gap-2">
            <input type="checkbox" {...register('is_epingle')} className="rounded" />
            Épingler en première position
          </label>
        )}
        <div>
          <label className="block text-sm font-medium text-ink mb-1">Date de publication</label>
          <input
            type="datetime-local"
            {...register('publie_le')}
            className="w-full px-3 py-2 border rounded-lg bg-off border-border"
          />
          <p className="text-xs text-mute mt-1">Laisser vide pour garder en brouillon</p>
        </div>
        <div className="flex gap-2">
          <Button type="submit" loading={loading}>
            {isEdit ? 'Enregistrer' : 'Créer'}
          </Button>
          <Button type="button" variant="outline" onClick={() => navigate('/backoffice/actualites')}>
            Annuler
          </Button>
        </div>
      </form>
    </div>
  );
}

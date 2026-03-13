import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import { useTenantStore, applyTenantTheme } from '@/store/useTenantStore';
import type { PortailConfig } from '@/types/tenant.types';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';
import ColorPicker from '@/components/ui/ColorPicker';
import ImageUpload from '@/components/ui/ImageUpload';

const schema = z.object({
  nom_etablissement: z.string(),
  slogan: z.string(),
  couleur_primaire: z.string(),
  couleur_secondaire: z.string(),
  couleur_texte: z.string(),
  logo_url: z.string(),
  favicon_url: z.string(),
});

type FormData = z.infer<typeof schema>;

export default function IdentitePage() {
  const setPortailData = useTenantStore((s) => s.setPortailData);
  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      couleur_primaire: '#0B3D6E',
      couleur_secondaire: '#C8A84B',
      couleur_texte: '#18182E',
    },
  });

  useEffect(() => {
    portailService.getConfig().then((data) => {
      setValue('nom_etablissement', data.nom_etablissement || '');
      setValue('slogan', data.slogan || '');
      setValue('couleur_primaire', data.couleur_primaire || '#0B3D6E');
      setValue('couleur_secondaire', data.couleur_secondaire || '#C8A84B');
      setValue('couleur_texte', data.couleur_texte || '#18182E');
      setValue('logo_url', data.logo_url || '');
      setValue('favicon_url', data.favicon_url || '');
    }).catch(() => {});
  }, [setValue]);

  const onSubmit = async (data: FormData) => {
    try {
      await portailService.updateConfig(data);
      setPortailData({ config: data });
      applyTenantTheme(data);
      toast.success('Modifications sauvegardées');
    } catch {
      toast.error('Erreur lors de l\'enregistrement');
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Identité</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="max-w-2xl space-y-6">
        <Input label="Nom de l'établissement" {...register('nom_etablissement')} error={errors.nom_etablissement?.message} />
        <Input label="Slogan" {...register('slogan')} error={errors.slogan?.message} />
        <ColorPicker
          label="Couleur primaire"
          value={watch('couleur_primaire')}
          onChange={(v) => setValue('couleur_primaire', v)}
        />
        <ColorPicker
          label="Couleur secondaire"
          value={watch('couleur_secondaire')}
          onChange={(v) => setValue('couleur_secondaire', v)}
        />
        <ColorPicker
          label="Couleur du texte"
          value={watch('couleur_texte')}
          onChange={(v) => setValue('couleur_texte', v)}
        />
        <ImageUpload
          label="Logo"
          value={watch('logo_url')}
          onChange={(v) => setValue('logo_url', v)}
          onRemove={() => setValue('logo_url', '')}
        />
        <ImageUpload
          label="Favicon"
          value={watch('favicon_url')}
          onChange={(v) => setValue('favicon_url', v)}
          onRemove={() => setValue('favicon_url', '')}
          aspectRatio="1/1"
        />
        <Button type="submit">Enregistrer</Button>
      </form>
    </div>
  );
}

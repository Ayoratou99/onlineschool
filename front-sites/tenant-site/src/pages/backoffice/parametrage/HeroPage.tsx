import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';
import Textarea from '@/components/ui/Textarea';
import ImageUpload from '@/components/ui/ImageUpload';

const schema = z.object({
  image_url: z.string(),
  badge_texte: z.string(),
  titre: z.string(),
  sous_titre: z.string(),
  bouton_principal: z.string(),
  bouton_secondaire: z.string(),
});

type FormData = z.infer<typeof schema>;

export default function HeroPage() {
  const { register, handleSubmit, setValue, watch, formState: { errors } } = useForm<FormData>({
    resolver: zodResolver(schema),
  });

  useEffect(() => {
    portailService.getHero().then((data) => {
      setValue('image_url', data.image_url || '');
      setValue('badge_texte', data.badge_texte || '');
      setValue('titre', data.titre || '');
      setValue('sous_titre', data.sous_titre || '');
      setValue('bouton_principal', data.bouton_principal || '');
      setValue('bouton_secondaire', data.bouton_secondaire || '');
    }).catch(() => {});
  }, [setValue]);

  const onSubmit = async (data: FormData) => {
    try {
      await portailService.updateHero(data);
      toast.success('Modifications sauvegardées');
    } catch {
      toast.error('Erreur lors de l\'enregistrement');
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Hero</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="max-w-2xl space-y-6">
        <ImageUpload
          label="Image de fond"
          value={watch('image_url')}
          onChange={(v) => setValue('image_url', v)}
          onRemove={() => setValue('image_url', '')}
          aspectRatio="16/9"
        />
        <Input label="Badge (texte au-dessus du titre)" {...register('badge_texte')} />
        <Textarea
          label="Titre (entourez un mot d'*astérisques* pour le colorer)"
          {...register('titre')}
        />
        <Textarea label="Sous-titre" {...register('sous_titre')} />
        <Input label="Libellé bouton principal" {...register('bouton_principal')} />
        <Input label="Libellé bouton secondaire" {...register('bouton_secondaire')} />
        <Button type="submit">Enregistrer</Button>
      </form>
    </div>
  );
}

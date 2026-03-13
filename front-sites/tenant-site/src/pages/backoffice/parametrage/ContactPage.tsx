import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import toast from 'react-hot-toast';
import { portailService } from '@/services/portail.service';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';
import Textarea from '@/components/ui/Textarea';

const schema = z.object({
  adresse: z.string(),
  telephone: z.string(),
  email: z.string(),
  horaires_semaine: z.string(),
  horaires_samedi: z.string(),
  facebook_url: z.string(),
  twitter_url: z.string(),
  linkedin_url: z.string(),
  instagram_url: z.string(),
});

type FormData = z.infer<typeof schema>;

export default function ContactPage() {
  const { register, handleSubmit, setValue } = useForm<FormData>({ resolver: zodResolver(schema) });

  useEffect(() => {
    portailService.getContact().then((data) => {
      setValue('adresse', data.adresse || '');
      setValue('telephone', data.telephone || '');
      setValue('email', data.email || '');
      setValue('horaires_semaine', data.horaires_semaine || '');
      setValue('horaires_samedi', data.horaires_samedi || '');
      setValue('facebook_url', data.facebook_url || '');
      setValue('twitter_url', data.twitter_url || '');
      setValue('linkedin_url', data.linkedin_url || '');
      setValue('instagram_url', data.instagram_url || '');
    }).catch(() => {});
  }, [setValue]);

  const onSubmit = async (data: FormData) => {
    try {
      await portailService.updateContact(data);
      toast.success('Modifications sauvegardées');
    } catch {
      toast.error('Erreur');
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold text-ink mb-6">Contact</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="max-w-2xl space-y-6">
        <Textarea label="Adresse" {...register('adresse')} />
        <Input label="Téléphone" type="tel" {...register('telephone')} />
        <Input label="Email" type="email" {...register('email')} />
        <Input label="Horaires (semaine)" {...register('horaires_semaine')} />
        <Input label="Horaires (samedi)" {...register('horaires_samedi')} />
        <p className="text-sm font-medium text-ink">Réseaux sociaux</p>
        <Input label="Facebook" type="url" {...register('facebook_url')} />
        <Input label="Twitter" type="url" {...register('twitter_url')} />
        <Input label="LinkedIn" type="url" {...register('linkedin_url')} />
        <Input label="Instagram" type="url" {...register('instagram_url')} />
        <Button type="submit">Enregistrer</Button>
      </form>
    </div>
  );
}

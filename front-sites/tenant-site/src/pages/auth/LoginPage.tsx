import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import { useNavigate } from 'react-router-dom';
import toast from 'react-hot-toast';
import { authService } from '@/services/auth.service';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission } from '@/router/permissions';
import type { UserRole } from '@/types/auth.types';
import Button from '@/components/ui/Button';
import Input from '@/components/ui/Input';

const schema = z.object({
  identifiant: z.string().min(1, 'Identifiant requis'),
  mot_de_passe: z.string().min(1, 'Mot de passe requis'),
  role: z.enum([
    'ADMIN',
    'DIRECTION',
    'CHEF_DEPARTEMENT',
    'SCOLARITE',
    'ENSEIGNANT',
    'COMPTABLE',
    'BIBLIOTHEQUE',
    'ETUDIANT',
  ]),
});

type FormData = z.infer<typeof schema>;

const roleOptions: { value: UserRole; label: string }[] = [
  { value: 'ETUDIANT', label: 'Étudiant' },
  { value: 'ENSEIGNANT', label: 'Enseignant' },
  { value: 'SCOLARITE', label: 'Scolarité' },
  { value: 'DIRECTION', label: 'Direction' },
  { value: 'COMPTABLE', label: 'Comptabilité' },
  { value: 'BIBLIOTHEQUE', label: 'Bibliothèque' },
  { value: 'CHEF_DEPARTEMENT', label: 'Chef de département' },
  { value: 'ADMIN', label: 'Administrateur' },
];

export default function LoginPage() {
  const navigate = useNavigate();
  const setAuth = useAuthStore((s) => s.setAuth);
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors },
  } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: { role: 'ENSEIGNANT' },
  });

  const selectedRole = watch('role');

  const onSubmit = async (data: FormData) => {
    setLoading(true);
    try {
      const res = await authService.login(data);
      setAuth(res.user, res.token);
      if (data.role === 'ETUDIANT') {
        toast('Vous n\'avez pas accès au backoffice.', { icon: 'ℹ️' });
        navigate('/');
      } else {
        navigate('/backoffice/dashboard');
      }
    } catch (err: unknown) {
      const msg = err && typeof err === 'object' && 'response' in err
        ? (err as { response?: { data?: { message?: string } } }).response?.data?.message
        : 'Identifiant ou mot de passe incorrect';
      toast.error(String(msg));
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div>
        <p className="text-sm font-medium text-ink mb-2">Rôle</p>
        <div className="grid grid-cols-2 gap-2">
          {roleOptions.map((opt) => (
            <button
              key={opt.value}
              type="button"
              onClick={() => setValue('role', opt.value)}
              className={`px-3 py-2 rounded-lg border text-sm font-medium transition ${
                selectedRole === opt.value
                  ? 'border-primary bg-primary text-white'
                  : 'border-border text-ink hover:bg-black/5'
              }`}
            >
              {opt.label}
            </button>
          ))}
        </div>
      </div>
      <Input
        label="Identifiant (matricule ou email)"
        {...register('identifiant')}
        error={errors.identifiant?.message}
        autoComplete="username"
      />
      <Input
        label="Mot de passe"
        type="password"
        {...register('mot_de_passe')}
        error={errors.mot_de_passe?.message}
        autoComplete="current-password"
      />
      <Button type="submit" className="w-full" loading={loading}>
        Se connecter
      </Button>
      <p className="text-center text-sm text-mute">
        <a href="#" className="hover:text-primary">Mot de passe oublié</a>
      </p>
    </form>
  );
}

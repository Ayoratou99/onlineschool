import { Outlet } from 'react-router-dom';
import { useTenantStore } from '@/store/useTenantStore';

export default function AuthLayout() {
  const config = useTenantStore((s) => s.config);

  return (
    <div className="min-h-screen flex items-center justify-center bg-off p-4">
      <div className="w-full max-w-md">
        {config?.logo_url && (
          <div className="flex justify-center mb-6">
            <img src={config.logo_url} alt={config.nom_etablissement || ''} className="h-16 object-contain" />
          </div>
        )}
        <h1 className="text-center text-xl font-semibold text-ink mb-6">
          {config?.nom_etablissement || 'Espace numérique'}
        </h1>
        <Outlet />
      </div>
    </div>
  );
}

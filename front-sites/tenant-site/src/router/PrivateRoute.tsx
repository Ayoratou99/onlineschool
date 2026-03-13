import { Navigate, useLocation } from 'react-router-dom';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission, type Permission } from './permissions';
import type { UserRole } from '@/types/auth.types';

interface PrivateRouteProps {
  children: React.ReactNode;
  permission?: Permission;
}

export default function PrivateRoute({ children, permission }: PrivateRouteProps) {
  const location = useLocation();
  const { user, token } = useAuthStore();

  if (!token || !user) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  if (permission && user.role) {
    const allowed = hasPermission(user.role as UserRole, permission);
    if (!allowed) {
      return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-off text-ink p-4">
          <span className="text-6xl mb-4">🔒</span>
          <h1 className="text-2xl font-semibold">Accès refusé</h1>
          <p className="text-mute mt-2 text-center">Vous n&apos;avez pas les droits pour accéder à cette page.</p>
          <a href="/backoffice/dashboard" className="mt-6 px-4 py-2 bg-primary text-white rounded-lg">
            Retour au tableau de bord
          </a>
        </div>
      );
    }
  }

  return <>{children}</>;
}

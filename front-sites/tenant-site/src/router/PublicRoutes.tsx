import { Navigate, Outlet } from 'react-router-dom';
import { useAuthStore } from '@/store/useAuthStore';

export default function PublicRoutes() {
  const token = useAuthStore((s) => s.token);
  if (token) return <Navigate to="/backoffice" replace />;
  return <Outlet />;
}

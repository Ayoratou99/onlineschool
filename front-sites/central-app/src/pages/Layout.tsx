import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function Layout() {
  const { admin, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login', { state: { logoutMessage: true }, replace: true });
  };

  return (
    <div className="min-h-screen flex flex-col">
      <header className="bg-[rgb(13,148,136)] shadow-md">
        <div className="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
          <nav className="flex items-center gap-6">
            <NavLink
              to="/tenants"
              className={({ isActive }) =>
                `font-medium ${isActive ? 'text-white' : 'text-white/90 hover:text-white'}`
              }
            >
              Tenants
            </NavLink>
            <NavLink
              to="/tenants/new"
              className={({ isActive }) =>
                `font-medium ${isActive ? 'text-white' : 'text-white/90 hover:text-white'}`
              }
            >
              New tenant
            </NavLink>
          </nav>
          <div className="flex items-center gap-4">
            <span className="text-sm text-white/80">{admin?.email}</span>
            <button
              type="button"
              onClick={handleLogout}
              className="text-sm font-medium text-white/90 hover:text-white"
            >
              Log out
            </button>
          </div>
        </div>
      </header>
      <main className="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        <Outlet />
      </main>
    </div>
  );
}

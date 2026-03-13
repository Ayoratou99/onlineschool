import { NavLink } from 'react-router-dom';
import {
  LayoutDashboard,
  Settings,
  Newspaper,
  Building2,
  Image,
  Menu,
  BarChart3,
  Layers,
  Contact,
} from 'lucide-react';
import { useAuthStore } from '@/store/useAuthStore';
import { hasPermission, type Permission } from '@/router/permissions';
import type { UserRole } from '@/types/auth.types';

const parametrageLinks: { to: string; label: string; permission: Permission }[] = [
  { to: '/backoffice/parametrage/identite', label: 'Identité', permission: 'PARAMETRAGE_IDENTITE' },
  { to: '/backoffice/parametrage/hero', label: 'Hero', permission: 'PARAMETRAGE_HERO' },
  { to: '/backoffice/parametrage/menu', label: 'Menu', permission: 'PARAMETRAGE_MENU' },
  { to: '/backoffice/parametrage/stats', label: 'Stats', permission: 'PARAMETRAGE_STATS' },
  { to: '/backoffice/parametrage/galerie', label: 'Galerie', permission: 'PARAMETRAGE_GALERIE' },
  { to: '/backoffice/parametrage/sections', label: 'Sections', permission: 'PARAMETRAGE_SECTIONS' },
  { to: '/backoffice/parametrage/contact', label: 'Contact', permission: 'PARAMETRAGE_CONTACT' },
];

export default function Sidebar() {
  const user = useAuthStore((s) => s.user);
  const role = (user?.role || 'ETUDIANT') as UserRole;

  const hasParametrage = parametrageLinks.some((l) => hasPermission(role, l.permission));
  const hasActualites = hasPermission(role, 'ACTUALITES_VIEW');

  return (
    <aside className="w-56 bg-white border-r border-border flex flex-col">
      <div className="p-4 border-b border-border">
        <NavLink to="/backoffice/dashboard" className="font-semibold text-primary">
          Backoffice
        </NavLink>
      </div>
      <nav className="p-2 flex-1">
        <NavLink
          to="/backoffice/dashboard"
          className={({ isActive }) =>
            `flex items-center gap-2 px-3 py-2 rounded-lg mb-1 ${isActive ? 'bg-primary text-white' : 'text-ink hover:bg-black/5'}`
          }
        >
          <LayoutDashboard className="w-5 h-5" />
          Tableau de bord
        </NavLink>
        {hasParametrage && (
          <>
            <p className="px-3 py-1 text-xs font-medium text-mute mt-4">Paramétrage</p>
            {parametrageLinks.filter((l) => hasPermission(role, l.permission)).map((link) => (
              <NavLink
                key={link.to}
                to={link.to}
                className={({ isActive }) =>
                  `flex items-center gap-2 px-3 py-2 rounded-lg mb-1 ${isActive ? 'bg-primary text-white' : 'text-ink hover:bg-black/5'}`
                }
              >
                {link.label === 'Identité' && <Building2 className="w-5 h-5" />}
                {link.label === 'Hero' && <Image className="w-5 h-5" />}
                {link.label === 'Menu' && <Menu className="w-5 h-5" />}
                {link.label === 'Stats' && <BarChart3 className="w-5 h-5" />}
                {link.label === 'Galerie' && <Image className="w-5 h-5" />}
                {link.label === 'Sections' && <Layers className="w-5 h-5" />}
                {link.label === 'Contact' && <Contact className="w-5 h-5" />}
                {link.label}
              </NavLink>
            ))}
          </>
        )}
        {hasActualites && (
          <>
            <p className="px-3 py-1 text-xs font-medium text-mute mt-4">Contenu</p>
            <NavLink
              to="/backoffice/actualites"
              className={({ isActive }) =>
                `flex items-center gap-2 px-3 py-2 rounded-lg mb-1 ${isActive ? 'bg-primary text-white' : 'text-ink hover:bg-black/5'}`
              }
            >
              <Newspaper className="w-5 h-5" />
              Actualités
            </NavLink>
          </>
        )}
      </nav>
    </aside>
  );
}

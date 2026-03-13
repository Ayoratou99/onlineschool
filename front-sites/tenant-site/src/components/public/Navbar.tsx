import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Menu, X } from 'lucide-react';
import { useTenantStore } from '@/store/useTenantStore';
import Modal from '@/components/ui/Modal';

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [loginOpen, setLoginOpen] = useState(false);
  const { config, menu } = useTenantStore();

  return (
    <>
      <header className="sticky top-0 z-40 bg-white/95 border-b border-border backdrop-blur">
        <div className="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
          <Link to="/" className="flex items-center gap-2">
            {config?.logo_url ? (
              <img src={config.logo_url} alt="" className="h-10" />
            ) : (
              <span className="font-bold text-primary">{config?.nom_etablissement || 'Portail'}</span>
            )}
          </Link>
          <nav className="hidden md:flex items-center gap-6">
            {menu.filter((m) => m.is_active).map((m) => (
              <a key={m.id} href={m.url} className="text-ink hover:text-primary transition">
                {m.libelle}
              </a>
            ))}
            <button
              type="button"
              onClick={() => setLoginOpen(true)}
              className="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:opacity-90"
            >
              Espace numérique
            </button>
          </nav>
          <button
            type="button"
            className="md:hidden p-2"
            onClick={() => setMenuOpen(!menuOpen)}
            aria-label="Menu"
          >
            {menuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
        {menuOpen && (
          <div className="md:hidden border-t border-border py-4 px-4 flex flex-col gap-2">
            {menu.filter((m) => m.is_active).map((m) => (
              <a key={m.id} href={m.url} className="py-2" onClick={() => setMenuOpen(false)}>
                {m.libelle}
              </a>
            ))}
            <Link to="/login" className="py-2 text-primary font-medium" onClick={() => setMenuOpen(false)}>
              Espace numérique
            </Link>
          </div>
        )}
      </header>
      <Modal open={loginOpen} onClose={() => setLoginOpen(false)} title="Connexion" size="sm">
        <p className="text-mute mb-4">Redirection vers la page de connexion.</p>
        <Link
          to="/login"
          className="inline-block px-4 py-2 bg-primary text-white rounded-lg"
          onClick={() => setLoginOpen(false)}
        >
          Aller à la connexion
        </Link>
      </Modal>
    </>
  );
}

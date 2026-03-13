import { Link } from 'react-router-dom';
import { useTenantStore } from '@/store/useTenantStore';
import Button from '@/components/ui/Button';

export default function HeroSection() {
  const { hero } = useTenantStore();
  if (!hero) return null;

  const title = hero.titre || '';
  const parts = title.split(/\*([^*]+)\*/);
  const renderTitle = () =>
    parts.map((p, i) => (i % 2 === 1 ? <em key={i} className="text-secondary not-italic">{p}</em> : p));

  return (
    <section
      className="relative min-h-[70vh] flex items-center justify-center bg-cover bg-center text-white"
      style={{ backgroundImage: hero.image_url ? `url(${hero.image_url})` : undefined, backgroundColor: 'var(--tp)' }}
    >
      <div className="absolute inset-0 bg-black/40" />
      <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
        {hero.badge_texte && (
          <span className="inline-block px-4 py-1 rounded-full bg-white/20 text-sm mb-4">
            {hero.badge_texte}
          </span>
        )}
        <h1 className="text-4xl md:text-5xl font-bold mb-4">{renderTitle()}</h1>
        {hero.sous_titre && <p className="text-xl opacity-90 mb-8">{hero.sous_titre}</p>}
        <div className="flex flex-col sm:flex-row gap-4 justify-center">
          {hero.bouton_principal && (
            <Link to="/login">
              <Button className="w-full sm:w-auto">{hero.bouton_principal}</Button>
            </Link>
          )}
          {hero.bouton_secondaire && (
            <Button variant="outline" className="w-full sm:w-auto border-white text-white hover:bg-white hover:text-ink">
              {hero.bouton_secondaire}
            </Button>
          )}
        </div>
      </div>
    </section>
  );
}

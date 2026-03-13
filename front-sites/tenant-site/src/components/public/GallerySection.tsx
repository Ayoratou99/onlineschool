import { useTenantStore } from '@/store/useTenantStore';

export default function GallerySection() {
  const { galerie } = useTenantStore();
  if (!galerie.length) return null;

  return (
    <section className="py-12 px-4">
      <div className="max-w-6xl mx-auto">
        <h2 className="text-2xl font-bold text-ink mb-6">Galerie</h2>
        <div className="grid grid-cols-2 md:grid-cols-3 gap-4 overflow-x-auto md:overflow-visible pb-4 md:pb-0">
          {galerie.map((g, i) => (
            <div key={g.id} className="rounded-lg overflow-hidden border border-border flex-shrink-0 w-full aspect-square">
              <img src={g.image_url} alt={g.alt_text || g.legende || ''} className="w-full h-full object-cover" />
              {i === 0 && (
                <span className="absolute top-2 left-2 px-2 py-0.5 bg-primary text-white text-xs rounded">
                  1ère
                </span>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

import { useTenantStore } from '@/store/useTenantStore';
import Badge from '@/components/ui/Badge';

const catLabels: Record<string, string> = {
  info: 'Info',
  urgent: 'Urgent',
  evenement: 'Événement',
  resultat: 'Résultat',
};

export default function NewsSection() {
  const { actualites } = useTenantStore();
  const recent = actualites.filter((a) => a.publie_le).slice(0, 3);
  if (!recent.length) return null;

  const [first, ...rest] = recent;

  return (
    <section className="py-12 px-4">
      <div className="max-w-6xl mx-auto">
        <h2 className="text-2xl font-bold text-ink mb-6">Actualités</h2>
        <div className="grid md:grid-cols-3 gap-6">
          <div className="md:col-span-2">
            <article className="rounded-xl overflow-hidden border border-border bg-white">
              {first.image_url && (
                <img src={first.image_url} alt="" className="w-full h-48 object-cover" />
              )}
              <div className="p-4">
                <Badge variant="primary">{catLabels[first.categorie] || first.categorie}</Badge>
                <h3 className="text-xl font-semibold mt-2">{first.titre}</h3>
                <p className="text-mute line-clamp-2 mt-1">{first.contenu}</p>
              </div>
            </article>
          </div>
          <div className="flex flex-col gap-4">
            {rest.map((a) => (
              <article key={a.id} className="rounded-lg border border-border bg-white p-4">
                <Badge variant="primary">{catLabels[a.categorie] || a.categorie}</Badge>
                <h3 className="font-semibold mt-2">{a.titre}</h3>
                <p className="text-sm text-mute line-clamp-1">{a.contenu}</p>
              </article>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

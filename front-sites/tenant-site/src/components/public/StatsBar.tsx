import { useTenantStore } from '@/store/useTenantStore';

export default function StatsBar() {
  const { stats } = useTenantStore();
  if (!stats.length) return null;

  return (
    <section className="py-6 px-4 -mt-8 relative z-20">
      <div className="max-w-6xl mx-auto">
        <div className="bg-white/95 backdrop-blur rounded-xl shadow-lg border border-border p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
          {stats.slice(0, 4).map((s) => (
            <div key={s.id} className="text-center">
              <p className="text-2xl font-bold text-primary">{s.valeur}</p>
              <p className="text-sm text-mute">{s.libelle}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

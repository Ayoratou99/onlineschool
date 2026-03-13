import { useTenantStore } from '@/store/useTenantStore';

export default function Footer() {
  const { config, contact } = useTenantStore();

  return (
    <footer className="bg-ink text-white py-8 px-4">
      <div className="max-w-6xl mx-auto flex flex-col md:flex-row justify-between gap-6">
        <div>
          <p className="font-semibold">{config?.nom_etablissement || 'Établissement'}</p>
          {config?.slogan && <p className="text-sm text-white/80 mt-1">{config.slogan}</p>}
        </div>
        {contact && (
          <div className="text-sm text-white/80">
            {contact.adresse && <p>{contact.adresse}</p>}
            {contact.telephone && <p>{contact.telephone}</p>}
            {contact.email && <p>{contact.email}</p>}
          </div>
        )}
      </div>
    </footer>
  );
}

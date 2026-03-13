import { useTenantStore } from '@/store/useTenantStore';

export default function ContactSection() {
  const { contact } = useTenantStore();
  if (!contact) return null;

  return (
    <section className="py-12 px-4 bg-white">
      <div className="max-w-6xl mx-auto grid md:grid-cols-2 gap-8">
        <div>
          <h2 className="text-2xl font-bold text-ink mb-4">Contact</h2>
          {contact.adresse && <p className="text-mute">{contact.adresse}</p>}
          {contact.telephone && <p className="mt-2">{contact.telephone}</p>}
          {contact.email && <p className="mt-2">{contact.email}</p>}
          {contact.horaires_semaine && (
            <p className="mt-4 text-sm text-mute">Semaine : {contact.horaires_semaine}</p>
          )}
          {contact.horaires_samedi && (
            <p className="text-sm text-mute">Samedi : {contact.horaires_samedi}</p>
          )}
        </div>
        <div>
          <p className="text-sm text-mute mb-2">Réseaux sociaux</p>
          <div className="flex gap-4">
            {contact.facebook_url && (
              <a href={contact.facebook_url} target="_blank" rel="noopener noreferrer" className="text-primary">
                📘 Facebook
              </a>
            )}
            {contact.instagram_url && (
              <a href={contact.instagram_url} target="_blank" rel="noopener noreferrer" className="text-primary">
                📸 Instagram
              </a>
            )}
            {contact.linkedin_url && (
              <a href={contact.linkedin_url} target="_blank" rel="noopener noreferrer" className="text-primary">
                💼 LinkedIn
              </a>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}

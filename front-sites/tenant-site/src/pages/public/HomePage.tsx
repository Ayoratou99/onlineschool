import { useEffect, useState } from 'react';
import { portailService } from '@/services/portail.service';
import { useTenantStore, applyTenantTheme } from '@/store/useTenantStore';
import Spinner from '@/components/ui/Spinner';
import HeroSection from '@/components/public/HeroSection';
import StatsBar from '@/components/public/StatsBar';
import NewsSection from '@/components/public/NewsSection';
import GallerySection from '@/components/public/GallerySection';
import ContactSection from '@/components/public/ContactSection';

export default function HomePage() {
  const [loading, setLoading] = useState(true);
  const setPortailData = useTenantStore((s) => s.setPortailData);

  useEffect(() => {
    portailService
      .getPortailPublic()
      .then((data) => {
        setPortailData({
          config: data.config,
          hero: data.hero,
          stats: data.stats || [],
          menu: data.menu || [],
          sections: data.sections || [],
          contact: data.contact,
          galerie: data.galerie || [],
          actualites: (data.actualites || []).slice(0, 3),
        });
        if (data.config) applyTenantTheme(data.config);
      })
      .catch(() => setPortailData({}))
      .finally(() => setLoading(false));
  }, [setPortailData]);

  if (loading) {
    return (
      <div className="min-h-[60vh] flex items-center justify-center">
        <Spinner />
      </div>
    );
  }

  return (
    <>
      <HeroSection />
      <StatsBar />
      <NewsSection />
      <GallerySection />
      <ContactSection />
    </>
  );
}

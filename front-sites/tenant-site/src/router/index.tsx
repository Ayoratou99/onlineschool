import { Routes, Route, Navigate } from 'react-router-dom';
import PrivateRoute from './PrivateRoute';
import PublicLayout from '@/components/layout/PublicLayout';
import AuthLayout from '@/components/layout/AuthLayout';
import BackofficeLayout from '@/components/layout/BackofficeLayout';
import HomePage from '@/pages/public/HomePage';
import LoginPage from '@/pages/auth/LoginPage';
import DashboardPage from '@/pages/backoffice/DashboardPage';
import IdentitePage from '@/pages/backoffice/parametrage/IdentitePage';
import HeroPage from '@/pages/backoffice/parametrage/HeroPage';
import MenuPage from '@/pages/backoffice/parametrage/MenuPage';
import StatsPage from '@/pages/backoffice/parametrage/StatsPage';
import GaleriePage from '@/pages/backoffice/parametrage/GaleriePage';
import SectionsPage from '@/pages/backoffice/parametrage/SectionsPage';
import ContactPage from '@/pages/backoffice/parametrage/ContactPage';
import ActualitesListPage from '@/pages/backoffice/actualites/ActualitesListPage';
import ActualiteFormPage from '@/pages/backoffice/actualites/ActualiteFormPage';

export default function AppRouter() {
  return (
    <Routes>
      <Route path="/" element={<PublicLayout />}>
        <Route index element={<HomePage />} />
      </Route>
      <Route path="/login" element={<AuthLayout />}>
        <Route index element={<LoginPage />} />
      </Route>
      <Route
        path="/backoffice"
        element={
          <PrivateRoute>
            <BackofficeLayout />
          </PrivateRoute>
        }
      >
        <Route index element={<Navigate to="/backoffice/dashboard" replace />} />
        <Route path="dashboard" element={<DashboardPage />} />
        <Route path="parametrage/identite" element={<IdentitePage />} />
        <Route path="parametrage/hero" element={<HeroPage />} />
        <Route path="parametrage/menu" element={<MenuPage />} />
        <Route path="parametrage/stats" element={<StatsPage />} />
        <Route path="parametrage/galerie" element={<GaleriePage />} />
        <Route path="parametrage/sections" element={<SectionsPage />} />
        <Route path="parametrage/contact" element={<ContactPage />} />
        <Route path="actualites" element={<ActualitesListPage />} />
        <Route path="actualites/nouvelle" element={<ActualiteFormPage />} />
        <Route path="actualites/:id/modifier" element={<ActualiteFormPage />} />
      </Route>
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

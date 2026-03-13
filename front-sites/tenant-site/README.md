# Tenant Site — Portail public & backoffice

Site React (Vite + TypeScript + Tailwind) pour le portail public et le backoffice d’un tenant (école/université) de l’ERP scolaire multi-tenant.

## Stack

- React 18, Vite 5, TypeScript
- Tailwind CSS v3 (couleurs dynamiques via variables CSS `--tp`, `--ts`, `--ink`)
- React Router v6, Axios, Zustand, React Hook Form + Zod, Lucide React, react-hot-toast

## Rôles et permissions

Rôles et permissions sont en **MAJUSCULES** ; le rôle administrateur est **ADMIN** (aligné avec le backend). Voir `src/router/permissions.ts`.

## Lancement

```bash
cp .env.example .env
# Ajuster VITE_API_BASE_URL (ex: http://localhost:8000/api pour le backend Laravel)
npm install
npm run dev
```

Ouvre http://localhost:3001 (port configuré dans `vite.config.ts`).

## Build

```bash
npm run build
```

## Structure

- `src/config/api.ts` — client Axios + intercepteurs (token, 401/403)
- `src/store/` — useAuthStore, useTenantStore (Zustand + persist)
- `src/router/` — routes, PrivateRoute, matrice de permissions
- `src/components/ui/` — Button, Input, Textarea, Select, Badge, Card, Modal, Spinner, ImageUpload, ColorPicker
- `src/components/layout/` — PublicLayout, AuthLayout, BackofficeLayout
- `src/components/public/` — Navbar, Footer, HeroSection, StatsBar, NewsSection, GallerySection, ContactSection
- `src/components/backoffice/` — Sidebar, Topbar, ActualiteCard
- `src/pages/` — HomePage, LoginPage, DashboardPage, pages paramétrage et actualités

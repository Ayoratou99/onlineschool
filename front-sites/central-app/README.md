# Online School — Central App

React frontend for the **central** admin: login, tenant CRUD, and clean. It talks to the Laravel central API (e.g. `http://localhost:8000`).

## Setup

```bash
cd central-app
npm install
```

## Run

- **Dev:** `npm run dev` — app at http://localhost:3000. API calls to `/api` are proxied to `http://localhost:8000` (start the Laravel app on port 8000).
- **Build:** `npm run build`
- **Preview build:** `npm run preview`

## Environment

- `VITE_API_URL` — Leave empty in dev (proxy used). In production, set to your central API base URL (e.g. `https://central.example.com`).

## Usage

1. Open the app on a **central domain** (e.g. http://localhost:3000).
2. Log in with a central admin (e.g. `admin@central.local` / `password`).
3. Manage tenants: list, create (ID + domains), edit domains, clean (run migrations), delete.

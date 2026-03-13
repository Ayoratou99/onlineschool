import { Outlet } from 'react-router-dom';
import Sidebar from '@/components/backoffice/Sidebar';
import Topbar from '@/components/backoffice/Topbar';

export default function BackofficeLayout() {
  return (
    <div className="min-h-screen flex bg-off text-ink">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <Topbar />
        <main className="flex-1 p-4 overflow-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}

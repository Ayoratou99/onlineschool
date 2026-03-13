import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Alert from '../../components/Alert';
import ConfirmModal from '../../components/ConfirmModal';
import TenantDashboard from '../../components/TenantDashboard';
import * as api from '../../lib/api';

export default function TenantList() {
  const [tenants, setTenants] = useState<api.Tenant[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [flash, setFlash] = useState<{ type: 'success' | 'error'; message: string } | null>(null);
  const [page, setPage] = useState(1);
  const [total, setTotal] = useState(0);
  const [perPage] = useState(15);
  const [cleaning, setCleaning] = useState<string | null>(null);
  const [deleting, setDeleting] = useState<string | null>(null);
  const [locking, setLocking] = useState<string | null>(null);
  const [deleteConfirmId, setDeleteConfirmId] = useState<string | null>(null);

  const load = async () => {
    setLoading(true);
    setError('');
    try {
      const res = await api.fetchTenants(page, perPage, true);
      setTenants(res.data.data);
      setTotal(res.data.total);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to load tenants');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, [page]);

  const handleClean = async (id: string) => {
    setCleaning(id);
    setFlash(null);
    try {
      await api.cleanTenant(id);
      await load();
      setFlash({ type: 'success', message: 'Tenant migrations completed.' });
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Clean failed' });
    } finally {
      setCleaning(null);
    }
  };

  const handleDeleteClick = (id: string) => {
    setFlash(null);
    setDeleteConfirmId(id);
  };

  const handleDeleteConfirm = async () => {
    if (!deleteConfirmId) return;
    setDeleting(deleteConfirmId);
    try {
      await api.deleteTenant(deleteConfirmId);
      setDeleteConfirmId(null);
      await load();
      setFlash({ type: 'success', message: 'Tenant deleted.' });
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Delete failed' });
    } finally {
      setDeleting(null);
    }
  };

  const handleLock = async (id: string) => {
    setLocking(id);
    setFlash(null);
    try {
      await api.lockTenant(id);
      await load();
      setFlash({ type: 'success', message: 'Tenant locked. API access disabled.' });
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Lock failed' });
    } finally {
      setLocking(null);
    }
  };

  const handleUnlock = async (id: string) => {
    setLocking(id);
    setFlash(null);
    try {
      await api.unlockTenant(id);
      await load();
      setFlash({ type: 'success', message: 'Tenant unlocked.' });
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Unlock failed' });
    } finally {
      setLocking(null);
    }
  };

  const totalPages = Math.ceil(total / perPage) || 1;

  return (
    <div>
      <TenantDashboard />

      <div className="flex items-center justify-between mb-6">
        <h1 className="text-xl font-semibold text-stone-900">Tenants</h1>
        <Link
          to="/tenants/new"
          className="px-4 py-2 rounded-lg bg-[rgb(13,148,136)] text-white font-medium hover:bg-brand-700 transition"
        >
          Add tenant
        </Link>
      </div>

      {flash && (
        <Alert
          variant={flash.type}
          onDismiss={() => setFlash(null)}
          className="mb-4"
        >
          {flash.message}
        </Alert>
      )}
      {error && (
        <Alert variant="error" className="mb-4">
          {error}
        </Alert>
      )}

      <ConfirmModal
        open={deleteConfirmId !== null}
        title="Delete tenant"
        message="This will remove the tenant and its database. This action cannot be undone."
        confirmLabel="Delete"
        cancelLabel="Cancel"
        variant="primary"
        loading={deleting !== null}
        onConfirm={handleDeleteConfirm}
        onCancel={() => setDeleteConfirmId(null)}
      />

      {loading ? (
        <div className="py-12 text-center text-stone-500">Loading…</div>
      ) : tenants.length === 0 ? (
        <div className="bg-white rounded-xl border border-surface-200 p-12 text-center text-stone-500">
          No tenants yet. <Link to="/tenants/new" className="text-brand-600 hover:underline">Create one</Link>.
        </div>
      ) : (
        <div className="bg-white rounded-xl border border-surface-200 overflow-hidden">
          <table className="w-full">
            <thead className="bg-surface-50 border-b border-surface-200">
              <tr>
                <th className="text-left py-3 px-4 font-medium text-stone-600">ID</th>
                <th className="text-left py-3 px-4 font-medium text-stone-600">Domains</th>
                <th className="text-left py-3 px-4 font-medium text-stone-600">Users</th>
                <th className="text-left py-3 px-4 font-medium text-stone-600">Storage</th>
                <th className="text-left py-3 px-4 font-medium text-stone-600">Status</th>
                <th className="text-left py-3 px-4 font-medium text-stone-600">Created</th>
                <th className="text-right py-3 px-4 font-medium text-stone-600">Actions</th>
              </tr>
            </thead>
            <tbody>
              {tenants.map((t) => (
                <tr key={t.id} className="border-b border-surface-100 last:border-0">
                  <td className="py-3 px-4 font-mono text-sm">{t.id}</td>
                  <td className="py-3 px-4">
                    <div className="flex flex-wrap gap-1">
                      {t.domains?.map((d) => (
                        <span
                          key={d.id}
                          className="inline-block px-2 py-0.5 rounded bg-surface-100 text-stone-600 text-sm"
                        >
                          {d.domain}
                        </span>
                      ))}
                    </div>
                  </td>
                  <td className="py-3 px-4 text-stone-600 text-sm">
                    {t.stats?.users_count ?? '—'}
                  </td>
                  <td className="py-3 px-4 text-stone-600 text-sm">
                    {t.stats?.database_size_mb != null ? `${t.stats.database_size_mb} MB` : '—'}
                  </td>
                  <td className="py-3 px-4">
                    {t.locked ? (
                      <span className="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                        Locked
                      </span>
                    ) : (
                      <span className="inline-flex items-center rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-800">
                        Active
                      </span>
                    )}
                  </td>
                  <td className="py-3 px-4 text-stone-500 text-sm">
                    {new Date(t.created_at).toLocaleDateString()}
                  </td>
                  <td className="py-3 px-4 text-right">
                    <Link
                      to={`/tenants/${t.id}/edit`}
                      className="text-brand-600 hover:underline text-sm font-medium mr-3"
                    >
                      Edit
                    </Link>
                    {t.locked ? (
                      <button
                        type="button"
                        onClick={() => handleUnlock(t.id)}
                        disabled={locking === t.id}
                        className="text-brand-600 hover:text-brand-700 text-sm font-medium mr-3 disabled:opacity-50"
                      >
                        {locking === t.id ? '…' : 'Unlock'}
                      </button>
                    ) : (
                      <button
                        type="button"
                        onClick={() => handleLock(t.id)}
                        disabled={locking === t.id}
                        className="text-amber-600 hover:text-amber-700 text-sm font-medium mr-3 disabled:opacity-50"
                      >
                        {locking === t.id ? '…' : 'Lock'}
                      </button>
                    )}
                    <button
                      type="button"
                      onClick={() => handleClean(t.id)}
                      disabled={cleaning === t.id}
                      className="text-stone-600 hover:text-stone-900 text-sm font-medium mr-3 disabled:opacity-50"
                    >
                      {cleaning === t.id ? 'Running…' : 'Clean'}
                    </button>
                    <button
                      type="button"
                      onClick={() => handleDeleteClick(t.id)}
                      disabled={deleting === t.id}
                      className="text-red-600 hover:text-red-700 text-sm font-medium disabled:opacity-50"
                    >
                      {deleting === t.id ? 'Deleting…' : 'Delete'}
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          {totalPages > 1 && (
            <div className="flex items-center justify-between py-3 px-4 border-t border-surface-200">
              <span className="text-sm text-stone-500">
                {total} tenant{total !== 1 ? 's' : ''}
              </span>
              <div className="flex gap-2">
                <button
                  type="button"
                  onClick={() => setPage((p) => Math.max(1, p - 1))}
                  disabled={page <= 1}
                  className="px-3 py-1 rounded border border-surface-200 text-sm disabled:opacity-50 hover:bg-surface-50"
                >
                  Previous
                </button>
                <span className="px-3 py-1 text-sm text-stone-600">
                  {page} / {totalPages}
                </span>
                <button
                  type="button"
                  onClick={() => setPage((p) => Math.min(totalPages, p + 1))}
                  disabled={page >= totalPages}
                  className="px-3 py-1 rounded border border-surface-200 text-sm disabled:opacity-50 hover:bg-surface-50"
                >
                  Next
                </button>
              </div>
            </div>
          )}
        </div>
      )}
    </div>
  );
}

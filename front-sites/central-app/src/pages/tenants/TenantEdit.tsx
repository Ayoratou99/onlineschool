import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Alert from '../../components/Alert';
import * as api from '../../lib/api';

export default function TenantEdit() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [tenant, setTenant] = useState<api.Tenant | null>(null);
  const [domainsStr, setDomainsStr] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [locking, setLocking] = useState(false);
  const [error, setError] = useState('');
  const [flash, setFlash] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  const load = () => {
    if (!id) return;
    setLoading(true);
    api
      .fetchTenant(id, true)
      .then((res) => {
        setTenant(res.data);
        setDomainsStr(res.data.domains?.map((d) => d.domain).join('\n') ?? '');
        setError('');
      })
      .catch((err) => setError(err instanceof Error ? err.message : 'Failed to load'))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    load();
  }, [id]);

  const domains = domainsStr
    .split(/[\n,]/)
    .map((d) => d.trim())
    .filter(Boolean);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!id || !tenant) return;
    if (domains.length === 0) {
      setError('At least one domain is required');
      return;
    }
    setError('');
    setFlash(null);
    setSaving(true);
    try {
      await api.updateTenant(id, { domains });
      navigate('/tenants');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Update failed');
    } finally {
      setSaving(false);
    }
  };

  const handleLock = async () => {
    if (!id) return;
    setLocking(true);
    setFlash(null);
    try {
      await api.lockTenant(id);
      setFlash({ type: 'success', message: 'Tenant locked. API access disabled.' });
      load();
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Lock failed' });
    } finally {
      setLocking(false);
    }
  };

  const handleUnlock = async () => {
    if (!id) return;
    setLocking(true);
    setFlash(null);
    try {
      await api.unlockTenant(id);
      setFlash({ type: 'success', message: 'Tenant unlocked.' });
      load();
    } catch (err) {
      setFlash({ type: 'error', message: err instanceof Error ? err.message : 'Unlock failed' });
    } finally {
      setLocking(false);
    }
  };

  if (loading) return <div className="py-8 text-stone-500">Loading…</div>;
  if (!tenant) {
    return (
      <Alert variant="error" className="mt-4">
        {error || 'Tenant not found.'}
      </Alert>
    );
  }

  return (
    <div>
      <h1 className="text-xl font-semibold text-stone-900 mb-6">Edit tenant: {tenant.id}</h1>

      {flash && (
        <Alert variant={flash.type} onDismiss={() => setFlash(null)} className="mb-4">
          {flash.message}
        </Alert>
      )}

      <div className="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div className="rounded-xl border border-brand-200 bg-brand-50/30 p-4">
          <p className="text-sm font-medium text-stone-500">Users</p>
          <p className="text-xl font-bold text-[rgb(13,148,136)]">
            {tenant.stats?.users_count ?? '—'}
          </p>
        </div>
        <div className="rounded-xl border border-brand-200 bg-brand-50/30 p-4">
          <p className="text-sm font-medium text-stone-500">Database storage</p>
          <p className="text-xl font-bold text-[rgb(13,148,136)]">
            {tenant.stats?.database_size_mb != null ? `${tenant.stats.database_size_mb} MB` : '—'}
          </p>
        </div>
        <div className="rounded-xl border border-surface-200 bg-white p-4 flex flex-col justify-center">
          <p className="text-sm font-medium text-stone-500 mb-2">Status</p>
          {tenant.locked ? (
            <span className="inline-flex w-fit items-center rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-800">
              Locked
            </span>
          ) : (
            <span className="inline-flex w-fit items-center rounded-full bg-brand-100 px-3 py-1 text-sm font-medium text-brand-800">
              Active
            </span>
          )}
          <div className="mt-3">
            {tenant.locked ? (
              <button
                type="button"
                onClick={handleUnlock}
                disabled={locking}
                className="text-sm font-medium text-brand-600 hover:text-brand-700 disabled:opacity-50"
              >
                {locking ? '…' : 'Unlock tenant'}
              </button>
            ) : (
              <button
                type="button"
                onClick={handleLock}
                disabled={locking}
                className="text-sm font-medium text-amber-600 hover:text-amber-700 disabled:opacity-50"
              >
                {locking ? '…' : 'Lock tenant'}
              </button>
            )}
          </div>
        </div>
      </div>

      <form
        onSubmit={handleSubmit}
        className="bg-white rounded-xl border border-surface-200 p-6 max-w-lg"
      >
        {error && (
          <Alert variant="error" className="mb-4">
            {error}
          </Alert>
        )}
        <label className="block text-sm font-medium text-stone-700 mb-1">Tenant ID</label>
        <input
          type="text"
          value={tenant.id}
          disabled
          className="w-full px-4 py-2.5 rounded-lg border border-surface-200 bg-surface-50 text-stone-500 font-mono mb-4 cursor-not-allowed"
        />
        <label className="block text-sm font-medium text-stone-700 mb-1">
          Domains (one per line or comma-separated)
        </label>
        <textarea
          value={domainsStr}
          onChange={(e) => setDomainsStr(e.target.value)}
          rows={3}
          className="w-full px-4 py-2.5 rounded-lg border border-surface-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none mb-6 font-mono text-sm"
        />
        <div className="flex gap-3">
          <button
            type="submit"
            disabled={saving}
            className="px-4 py-2.5 rounded-lg bg-[rgb(13,148,136)] text-white font-medium hover:bg-brand-700 disabled:opacity-50 transition"
          >
            {saving ? 'Saving…' : 'Save'}
          </button>
          <button
            type="button"
            onClick={() => navigate('/tenants')}
            className="px-4 py-2.5 rounded-lg border border-surface-200 text-stone-700 hover:bg-surface-50 transition"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
}

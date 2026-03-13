import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Alert from '../../components/Alert';
import * as api from '../../lib/api';

export default function TenantCreate() {
  const navigate = useNavigate();
  const [id, setId] = useState('');
  const [domainsStr, setDomainsStr] = useState('foo.localhost');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const domains = domainsStr
    .split(/[\n,]/)
    .map((d) => d.trim())
    .filter(Boolean);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!id.trim()) {
      setError('ID is required');
      return;
    }
    if (domains.length === 0) {
      setError('At least one domain is required');
      return;
    }
    setError('');
    setLoading(true);
    try {
      await api.createTenant({ id: id.trim(), domains });
      navigate('/tenants');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Create failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1 className="text-xl font-semibold text-stone-900 mb-6">New tenant</h1>
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
          value={id}
          onChange={(e) => setId(e.target.value)}
          placeholder="e.g. foo"
          className="w-full px-4 py-2.5 rounded-lg border border-surface-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none mb-4 font-mono"
        />
        <label className="block text-sm font-medium text-stone-700 mb-1">
          Domains (one per line or comma-separated)
        </label>
        <textarea
          value={domainsStr}
          onChange={(e) => setDomainsStr(e.target.value)}
          rows={3}
          placeholder="foo.localhost"
          className="w-full px-4 py-2.5 rounded-lg border border-surface-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none mb-6 font-mono text-sm"
        />
        <div className="flex gap-3">
          <button
            type="submit"
            disabled={loading}
            className="px-4 py-2.5 rounded-lg bg-[rgb(13,148,136)] text-white font-medium hover:bg-brand-700 disabled:opacity-50 transition"
          >
            {loading ? 'Creating…' : 'Create tenant'}
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

import { useState, useEffect } from 'react';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  Cell,
} from 'recharts';
import * as api from '../lib/api';

const CHART_COLOR = 'rgb(13, 148, 136)';

export default function TenantDashboard() {
  const [stats, setStats] = useState<api.DashboardStats | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    api
      .fetchDashboardStats()
      .then((res) => {
        setStats(res.data);
        setError('');
      })
      .catch((err) => setError(err instanceof Error ? err.message : 'Failed to load stats'))
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <div className="mb-8 rounded-2xl border border-brand-200 bg-brand-50/30 p-8 text-center text-stone-500">
        Loading statistics…
      </div>
    );
  }
  if (error || !stats) {
    return (
      <div className="mb-8 rounded-2xl border border-red-200 bg-red-50/50 p-4 text-sm text-red-700">
        {error || 'No statistics available'}
      </div>
    );
  }

  const { total_tenants, total_users, total_database_size_mb, by_tenant } = stats;
  const chartData = by_tenant.slice(0, 10);

  return (
    <div className="mb-8 space-y-6">
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div className="rounded-xl border border-brand-200 bg-white p-5 shadow-sm">
          <p className="text-sm font-medium text-stone-500">Total tenants</p>
          <p className="mt-1 text-2xl font-bold text-[rgb(13,148,136)]">{total_tenants}</p>
        </div>
        <div className="rounded-xl border border-brand-200 bg-white p-5 shadow-sm">
          <p className="text-sm font-medium text-stone-500">Total users</p>
          <p className="mt-1 text-2xl font-bold text-[rgb(13,148,136)]">{total_users}</p>
        </div>
        <div className="rounded-xl border border-brand-200 bg-white p-5 shadow-sm">
          <p className="text-sm font-medium text-stone-500">Database storage</p>
          <p className="mt-1 text-2xl font-bold text-[rgb(13,148,136)]">
            {total_database_size_mb.toFixed(1)} MB
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div className="rounded-2xl border border-brand-200 bg-white p-6 shadow-sm">
          <h3 className="mb-4 text-sm font-semibold text-stone-700">Users per tenant (top 10)</h3>
          <div className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={chartData} margin={{ top: 8, right: 8, left: 0, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" stroke="#e7e5e4" />
                <XAxis dataKey="id" tick={{ fontSize: 11 }} />
                <YAxis allowDecimals={false} tick={{ fontSize: 11 }} />
                <Tooltip
                  contentStyle={{ borderRadius: '8px', border: '1px solid #e7e5e4' }}
                  formatter={(value: number) => [value, 'Users']}
                  labelFormatter={(id) => `Tenant: ${id}`}
                />
                <Bar dataKey="users_count" name="Users" fill={CHART_COLOR} radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>
        <div className="rounded-2xl border border-brand-200 bg-white p-6 shadow-sm">
          <h3 className="mb-4 text-sm font-semibold text-stone-700">
            Database size per tenant (MB, top 10)
          </h3>
          <div className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={chartData} margin={{ top: 8, right: 8, left: 0, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" stroke="#e7e5e4" />
                <XAxis dataKey="id" tick={{ fontSize: 11 }} />
                <YAxis tick={{ fontSize: 11 }} />
                <Tooltip
                  contentStyle={{ borderRadius: '8px', border: '1px solid #e7e5e4' }}
                  formatter={(value: number) => [value.toFixed(2), 'MB']}
                  labelFormatter={(id) => `Tenant: ${id}`}
                />
                <Bar
                  dataKey="database_size_mb"
                  name="Storage (MB)"
                  fill={CHART_COLOR}
                  radius={[4, 4, 0, 0]}
                >
                  {chartData.map((_, i) => (
                    <Cell key={i} fill={CHART_COLOR} opacity={0.7 + (i % 3) * 0.1} />
                  ))}
                </Bar>
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>
      </div>
    </div>
  );
}

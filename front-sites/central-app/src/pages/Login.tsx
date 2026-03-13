import { useState } from 'react';
import { useNavigate, Navigate, useLocation } from 'react-router-dom';
import Alert from '../components/Alert';
import { useAuth } from '../contexts/AuthContext';

export default function Login() {
  const { token, login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const showLogoutMessage = !!(location.state as { logoutMessage?: boolean } | null)?.logoutMessage;
  const dismissLogoutMessage = () => navigate('/login', { state: {}, replace: true });

  if (token) return <Navigate to="/" replace />;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      await login(email, password);
      navigate('/');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-surface-50 via-white to-brand-50/30 px-4">
      <div className="w-full max-w-sm">
        <div className="text-center mb-8">
          <h1 className="text-2xl font-bold text-stone-900 tracking-tight">Online School</h1>
          <p className="text-stone-500 mt-1">Central admin</p>
        </div>
        <form
          onSubmit={handleSubmit}
          className="bg-white rounded-2xl shadow-lg shadow-stone-200/50 border border-surface-200 p-8"
        >
          {showLogoutMessage && (
            <Alert variant="success" onDismiss={dismissLogoutMessage} className="mb-4">
              You have been logged out.
            </Alert>
          )}
          {error && (
            <Alert variant="error" className="mb-4">
              {error}
            </Alert>
          )}
          <label className="block text-sm font-medium text-stone-700 mb-1">Email</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            autoComplete="email"
            className="w-full px-4 py-2.5 rounded-lg border border-surface-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition mb-4"
            placeholder="admin@central.local"
          />
          <label className="block text-sm font-medium text-stone-700 mb-1">Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            autoComplete="current-password"
            className="w-full px-4 py-2.5 rounded-lg border border-surface-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition mb-6"
          />
          <button
            type="submit"
            disabled={loading}
            className="w-full py-2.5 rounded-lg bg-[rgb(13,148,136)] text-white font-medium hover:bg-brand-700 focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 transition"
          >
            {loading ? 'Signing in…' : 'Sign in'}
          </button>
        </form>
        <p className="text-center text-stone-400 text-sm mt-6">
          Use central domain (e.g. localhost) to access this app.
        </p>
      </div>
    </div>
  );
}

import { createContext, useContext, useState, useCallback, useEffect } from 'react';
import * as api from '../lib/api';

type AuthContextType = {
  token: string | null;
  admin: api.Admin | null;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  setToken: (t: string | null) => void;
  setAdmin: (a: api.Admin | null) => void;
};

const AuthContext = createContext<AuthContextType | null>(null);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [token, setTokenState] = useState<string | null>(() => localStorage.getItem('admin_token'));
  const [admin, setAdmin] = useState<api.Admin | null>(null);

  const setToken = useCallback((t: string | null) => {
    setTokenState(t);
    if (t) localStorage.setItem('admin_token', t);
    else localStorage.removeItem('admin_token');
  }, []);

  const login = useCallback(async (email: string, password: string) => {
    const res = await api.login(email, password);
    setToken(res.access_token);
    setAdmin(res.admin);
  }, [setToken]);

  const logout = useCallback(async () => {
    try {
      await api.logout();
    } catch {
      // ignore
    }
    setToken(null);
    setAdmin(null);
  }, [setToken]);

  useEffect(() => {
    if (!token) {
      setAdmin(null);
      return;
    }
    api.getMe()
      .then((r) => setAdmin(r.data))
      .catch(() => {
        setToken(null);
        setAdmin(null);
      });
  }, [token, setToken]);

  return (
    <AuthContext.Provider value={{ token, admin, login, logout, setToken, setAdmin }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}

import { createContext, useContext, useState, useEffect, type ReactNode } from 'react';

interface AuthContextType {
  isAuthenticated: boolean;
  token: string | null;
  idUserIns: string | null;
  login: (token: string, idUserIns: string) => void;
  logout: () => void;
  isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [token, setToken] = useState<string | null>(null);
  const [idUserIns, setIdUserIns] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const storedToken = localStorage.getItem('jwt_token');
    const storedIdUser = localStorage.getItem('id_user_ins');

    if (storedToken) setToken(storedToken);
    if (storedIdUser) setIdUserIns(storedIdUser);

    setIsLoading(false); // sinaliza que terminou de carregar
  }, []);

  const login = (newToken: string, newIdUserIns: string) => {
    setToken(newToken);
    setIdUserIns(newIdUserIns);
    localStorage.setItem('jwt_token', newToken);
    localStorage.setItem('id_user_ins', newIdUserIns);
  };

  const logout = () => {
    setToken(null);
    setIdUserIns(null);
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('id_user_ins');
  };

  return (
    <AuthContext.Provider
      value={{
        isAuthenticated: !!token,
        token,
        idUserIns,
        login,
        logout,
        isLoading,
      }}
    >
      {!isLoading && children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth precisa ser com AuthProvider');
  return context;
}

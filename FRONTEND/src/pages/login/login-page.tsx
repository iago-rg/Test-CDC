'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useState } from 'react';
import api from '../../lib/axios';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';

const loginSchema = z.object({
  email: z.string().email('Email inválido'),
  password: z.string().min(3, 'Senha precisa conter ao menos 3 caracteres'),
});

type LoginData = z.infer<typeof loginSchema>;

export default function LoginPage() {
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const {
    register,
    handleSubmit,
    formState: { errors }
  } = useForm<LoginData>({
    resolver: zodResolver(loginSchema)
  });

  const onSubmit = async (data: LoginData) => {
    try {
      setLoading(true);
      const response = await api.post('/auth/login', data);
      const { token, id_user_ins } = response?.data?.data;
      if (!token) throw new Error('Token não retornado.');
      login(token, id_user_ins);
      navigate('/customer');
    } catch (err: any) {
      alert(err.response?.data?.message || err.message || 'Erro ao fazer login');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#f4f6fb] px-4">
      <div className="w-full max-w-md rounded-xl bg-white shadow-xl p-8">
        <div className="flex justify-center mb-6">
          <img src="src/assets/images/cdc_card_logo.jpeg" alt="Logo" className="w-16 h-16" />
        </div>
        <h1 className="text-2xl font-semibold text-center text-gray-900">CDC Bank</h1>
        <p className="text-center text-sm text-gray-500 mb-6">Plataforma bancária digital e segura</p>
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="text-sm font-medium text-gray-700">Email</label>
            <div className="relative mt-1">
              <input
                type="email"
                placeholder="Insira seu email"
                className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0245AE]"
                {...register('email')}
              />
              <span className="absolute inset-y-0 right-3 flex items-center text-gray-400">@</span>
            </div>
            {errors.email && <p className="text-sm text-red-500 mt-1">{errors.email.message}</p>}
          </div>
          <div>
            <label className="text-sm font-medium text-gray-700">Senha</label>
            <div className="relative mt-1">
              <input
                type="password"
                placeholder="Insira sua senha"
                className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0245AE]"
                {...register('password')}
              />
              <span className="absolute inset-y-0 right-3 flex items-center text-gray-400">👁️</span>
            </div>
            {errors.password && <p className="text-sm text-red-500 mt-1">{errors.password.message}</p>}
          </div>
          <button
            type="submit"
            disabled={loading}
            className="w-full bg-[#0245AE] hover:bg-[#003f95] text-white py-2 rounded-lg font-semibold transition"
          >
            {loading ? 'Entrando...' : 'Entrar'}
          </button>
        </form>
        <div className="flex justify-between text-sm mt-4 text-[#0245AE]">
          <a href="#">Esqueceu sua senha ?</a>
          <a href="#">Precisa de ajuda ?</a>
        </div>
        <div className="mt-6 text-xs text-center text-gray-500">
          <div className="flex justify-center items-center space-x-1 mb-2">
            <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" d="M12 11c0-1.657-1.343-3-3-3s-3 1.343-3 3 3 3 3 3 3-1.343 3-3z" />
              <path strokeLinecap="round" strokeLinejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
            </svg>
            <span>Sua conexão é segura e criptografada</span>
          </div>
          <p>© 2024 CDC Bank. Todos os direitos reservados.</p>
          <div className="space-x-2">
            <a href="#" className="hover:underline">Política de privacidade</a>
            <a href="#" className="hover:underline">Termos de serviço</a>
          </div>
        </div>
      </div>
    </div>
  );
}

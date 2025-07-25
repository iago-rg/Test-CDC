import { useQuery } from '@tanstack/react-query';
import { Button } from '../../../components/button';
import { useAuth } from '../../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';
import api from '../../../lib/axios';
import { useState, useMemo } from 'react';
import { useForm } from 'react-hook-form';
import toast from 'react-hot-toast';

interface Transaction {
  transaction_id: string;
  sender_id: string;
  receiver_id: string;
  amount: string;
  sender_name: string;
  receiver_name: string;
  date_transaction: string;
}

interface Customer {
  id: string;
  name: string;
}

export default function TransactionsPage() {
  const { logout } = useAuth();
  const navigate = useNavigate();
  const [isModalOpen, setIsModalOpen] = useState(false);

  const { data: transactionsData, isLoading, error, refetch } = useQuery<{ data: Transaction[] }>({
    queryKey: ['transactions'],
    queryFn: async () => {
      const res = await api.get('/transactions/search');
      if (!res.data.success) throw new Error(res.data.message || 'Erro ao buscar transações');
      return res.data;
    },
  });

  const { data: customersData } = useQuery<{ data: Customer[] }>({
    queryKey: ['customers'],
    queryFn: async () => {
      const res = await api.get('/customers/search');
      if (!res.data.success) throw new Error(res.data.message || 'Erro ao buscar clientes');
      return res.data;
    },
  });

  const transactions = transactionsData?.data || [];
  const customers = customersData?.data || [];

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  const handleCreateTransaction = async (data: { amount: string; sender_id: string; receiver_id: string }) => {
    try {
      const response = await api.post('/transactions/create', {
        ...data,
        amount: data?.amount?.replaceAll(',', '.'),
      });
      if (response.data.success) {
        setIsModalOpen(false);
        await refetch();
      }
    } catch (error) {
      const apiError = error?.response?.data?.message;
      toast.error(apiError ?? 'Erro ao criar transação');
    }
  };

  const { thisMonthTotal, lastMonthTotal } = useMemo(() => {
    const now = new Date();
    const thisMonth = now.getMonth();
    const lastMonth = thisMonth === 0 ? 11 : thisMonth - 1;
    const thisYear = now.getFullYear();
    const lastMonthYear = thisMonth === 0 ? thisYear - 1 : thisYear;

    let thisMonthTotal = 0;
    let lastMonthTotal = 0;

    transactions.forEach(tx => {
      const date = new Date(tx.date_transaction);
      const year = date.getFullYear();
      const month = date.getMonth();
      const amount = parseFloat(tx.amount);

      if (year === thisYear && month === thisMonth) {
        thisMonthTotal += amount;
      } else if (year === lastMonthYear && month === lastMonth) {
        lastMonthTotal += amount;
      }
    });

    return { thisMonthTotal, lastMonthTotal };
  }, [transactions]);

  return (
    <div className="flex h-screen bg-gray-100">
      <aside className="w-64 bg-white border-r p-4">
        <h1 className="text-xl font-bold text-center text-blue-800 mb-6">CDC Bank</h1>
        <nav className="space-y-2">
          <Button variant="ghost" className="w-full bg-gray-400 text-center" onClick={() => navigate('/customer')}>
            Clientes
          </Button>
          <Button className="w-full bg-blue-800 hover:bg-blue-900">Transações</Button>
        </nav>
      </aside>

      <main className="flex-1 p-6 overflow-auto space-y-6">
        <div className="flex justify-between">
          <h2 className="text-2xl font-semibold">Gerenciamento de transações</h2>
          <div>
          <Button className="bg-blue-800 hover:bg-blue-900 mr-2" onClick={() => setIsModalOpen(true)}>
            + Nova transação
          </Button>
          <Button className="bg-blue-800 hover:bg-blue-900" onClick={handleLogout}>
            Sair
          </Button>
          </div>
        </div>

        {isModalOpen && (
          <TransactionModal
            customers={customers}
            onClose={() => setIsModalOpen(false)}
            onSubmit={handleCreateTransaction}
          />
        )}

        <div className="bg-white shadow rounded-lg p-6">
          <div className="flex justify-between items-center">
            <div>
              <h2 className="text-xl font-semibold">Total movimentado</h2>
              <p className="text-2xl font-bold text-blue-800">
                R$ {transactions.reduce((sum, tx) => sum + parseFloat(tx.amount), 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
              </p>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-3 gap-4">
          <div className="bg-white p-4 rounded shadow">
            <p className="text-sm text-gray-500">Esse mês</p>
            <p className="text-green-600 font-semibold">
              R$ {thisMonthTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
            </p>
          </div>
          <div className="bg-white p-4 rounded shadow">
            <p className="text-sm text-gray-500">Último mês</p>
            <p className="text-green-800 font-semibold">
              R$ {lastMonthTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
            </p>
          </div>
          <div className="bg-white p-4 rounded shadow">
            <p className="text-sm text-gray-500">Total de transações</p>
            <p className="text-gray-900 font-semibold">{transactions.length}</p>
          </div>
        </div>

        <div className="bg-white shadow rounded-lg p-6">
          <h2 className="text-lg font-semibold mb-4">Histórico de transações</h2>
          {isLoading ? (
            <p className="text-center text-gray-500">Carregando...</p>
          ) : error ? (
            <p className="text-red-500 text-center">{(error as Error).message}</p>
          ) : (
            <div className="overflow-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data/Hora</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remetente</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destinatário</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100">
                  {transactions.map((tx) => (
                    <tr key={tx.transaction_id}>
                      <td className="px-6 py-4 text-sm text-gray-700">
                        {new Date(tx.date_transaction).toLocaleDateString('pt-BR')}<br />
                        {new Date(tx.date_transaction).toLocaleTimeString('pt-BR').slice(0, 5)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-xs font-bold mr-2">
                            {tx.sender_name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                          </div>
                          <span className="text-sm text-gray-900">{tx.sender_name}</span>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-800 text-xs font-bold mr-2">
                            {tx.receiver_name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                          </div>
                          <span className="text-sm text-gray-900">{tx.receiver_name}</span>
                        </div>
                      </td>
                      <td className="px-6 py-4 text-sm font-semibold text-gray-700">
                        R$ {Number(tx.amount).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                      </td>
                      <td className="px-6 py-4">
                        <span className="text-xs text-green-600 font-semibold">Completa</span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
          <div className="p-2 text-sm text-gray-500">
            Exibindo {transactions.length} transações
          </div>
        </div>
      </main>
    </div>
  );
}

function TransactionModal({
  customers,
  onClose,
  onSubmit,
}: {
  customers: Customer[];
  onClose: () => void;
  onSubmit: (data: { amount: string; sender_id: string; receiver_id: string }) => void;
}) {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<{ amount: string; sender_id: string; receiver_id: string }>();

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
      <div className="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl animate-fade-in">
        <h3 className="text-xl font-semibold mb-4 text-gray-800">Nova Transação</h3>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="amount">Valor</label>
            <input
              id="amount"
              {...register('amount', { required: 'Informe o valor' })}
              placeholder="Ex: 100.00"
              className="w-full border rounded px-3 py-2"
            />
            {errors.amount && <p className="text-sm text-red-500 mt-1">{errors.amount.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="sender_id">Remetente</label>
            <select
              id="sender_id"
              {...register('sender_id', { required: 'Selecione o remetente' })}
              className="w-full border rounded px-3 py-2"
              defaultValue=""
            >
              <option value="" disabled>Selecione o cliente</option>
              {customers.map((c) => (
                <option key={c.id} value={c.id}>{c.name}</option>
              ))}
            </select>
            {errors.sender_id && <p className="text-sm text-red-500 mt-1">{errors.sender_id.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="receiver_id">Destinatário</label>
            <select
              id="receiver_id"
              {...register('receiver_id', { required: 'Selecione o destinatário' })}
              className="w-full border rounded px-3 py-2"
              defaultValue=""
            >
              <option value="" disabled>Selecione o cliente</option>
              {customers.map((c) => (
                <option key={c.id} value={c.id}>{c.name}</option>
              ))}
            </select>
            {errors.receiver_id && <p className="text-sm text-red-500 mt-1">{errors.receiver_id.message}</p>}
          </div>

          <div className="flex justify-end gap-2 pt-4">
            <Button type="button" variant="ghost" onClick={onClose}>
              Cancelar
            </Button>
            <Button type="submit" className="bg-blue-600 text-white hover:bg-blue-700">
              Enviar
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
}
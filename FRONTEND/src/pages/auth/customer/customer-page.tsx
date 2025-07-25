import { useQuery } from '@tanstack/react-query';
import { z } from 'zod';
import { Input } from '../../../components/input';
import { Button } from '../../../components/button';
import { Pencil, Trash2 } from 'lucide-react';
import api from '../../../lib/axios';
import { useState } from 'react';
import CustomerModal from '../../../components/create-modal';
import type { CustomerFormData } from '../../../components/create-modal';
import { useDebounce } from 'use-debounce';
import { useAuth } from '../../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';
import toast from 'react-hot-toast';

const customerSchema = z.object({
  name: z.string().min(1, 'Informe o nome'),
  document_id: z.string().min(11, 'Informe o CPF/CNPJ'),
  birth_date: z.string().min(1, 'Informe a data de nascimento'),
  monthly_income: z.string().min(1, 'Informe a renda mensal'),
  balance: z.string().min(1, 'Informe o saldo em conta'),
});

type Customer = z.infer<typeof customerSchema> & {
  id: string;
  balance: string;
  idade: string;
};

export default function CustomerPage() {
  const { logout, idUserIns } = useAuth();
  const navigate = useNavigate();
  const [open, setOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const [debouncedSearch] = useDebounce(searchTerm, 400);
  const [editingCustomer, setEditingCustomer] = useState<Customer | null>(null);
  const [customerToDelete, setCustomerToDelete] = useState<Customer | null>(null);

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  const handleModalSubmit = async (formData: CustomerFormData, id?: string) => {
    try {
      if (id) {
        await api.put('/customers/update', {
          ...formData,
          id_user_ins: idUserIns,
          balance: formData?.balance?.replaceAll(',', '.'),
          monthly_income: formData?.monthly_income?.replaceAll(',', '.'),
          id,
        });
      } else {
        await api.post('/customers/create', {
          ...formData,
          id_user_ins: idUserIns,
          balance: formData?.balance?.replaceAll(',', '.'),
          monthly_income: formData?.monthly_income?.replaceAll(',', '.'),
        });
      }
      refetch();
      setOpen(false);
      setEditingCustomer(null);
    } catch (error) {
      const apiError = error.response?.data?.message;
      toast.error(apiError ?? `Erro ao ${id ? 'editar' : 'criar'} cliente`);
    }
  };

  const handleConfirmDelete = async () => {
    if (!customerToDelete) return;
    try {
      await api.delete(`/customers/delete/${customerToDelete.id}`);
      toast.success('Cliente deletado com sucesso');
      refetch();
    } catch (error) {
      toast.error('Erro ao deletar cliente');
    } finally {
      setCustomerToDelete(null);
    }
  };

  function formatCpfCnpj(value: string): string {
    const onlyNumbers = value.replace(/\D/g, '');
    if (onlyNumbers.length === 11) {
      return onlyNumbers.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }
    if (onlyNumbers.length === 14) {
      return onlyNumbers.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }
    return value;
  }

  const { data, isLoading, error, refetch } = useQuery<Customer[]>({
    queryKey: ['customers', debouncedSearch],
    queryFn: async () => {
      const response = await api.get('https://linen-seahorse-362403.hostingersite.com/customers/search', {
        params: {
          search: debouncedSearch || undefined,
        },
      });

      if (!response.data.success)
        throw new Error(response.data.message || 'Erro ao buscar clientes');
      return response.data.data as Customer[];
    },
  });

  return (
    <div className="flex h-screen bg-gray-100">
      <aside className="w-64 bg-white border-r p-4">
        <h1 className="text-xl font-bold text-center text-blue-800 mb-6">CDC Bank</h1>
        <nav className="space-y-2">
          <Button className="w-full bg-blue-800 hover:bg-blue-900">Clientes</Button>
          <Button variant="ghost" className="w-full text-center" onClick={() => navigate('/transactions')}>
            Transações
          </Button>
        </nav>
      </aside>
      <main className="flex-1 p-6 overflow-auto">
        <CustomerModal
          isOpen={open}
          onClose={() => {
            setOpen(false);
            setEditingCustomer(null);
          }}
          onSubmit={handleModalSubmit}
          idUserIns={idUserIns}
          customer={editingCustomer}
          onSuccessUpdate={refetch}
        />

        {customerToDelete && (
          <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
            <div className="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl">
              <h3 className="text-xl font-semibold mb-4 text-gray-800">Excluir cliente</h3>
              <p className="mb-6 text-gray-700">
                Tem certeza que deseja excluir <strong>{customerToDelete.name}</strong>?
              </p>
              <div className="flex justify-end gap-2">
                <Button variant="ghost" onClick={() => setCustomerToDelete(null)}>
                  Cancelar
                </Button>
                <Button className="bg-red-600 text-white hover:bg-red-700" onClick={handleConfirmDelete}>
                  Confirmar exclusão
                </Button>
              </div>
            </div>
          </div>
        )}

        <div className="flex justify-between items-center mb-4">
          <h2 className="text-2xl font-semibold">Gerenciamento de clientes</h2>
          <div className="flex gap-2">
            <Button
              onClick={() => {
                setEditingCustomer(null);
                setOpen(true);
              }}
              className="bg-blue-800 hover:bg-blue-700 text-white"
            >
              + Novo cliente
            </Button>
            <Button className="bg-blue-800 hover:bg-blue-900" onClick={handleLogout}>
              Sair
            </Button>
          </div>
        </div>

        <div className="relative mb-4">
          <Input
            placeholder="Pesquisar por nome, CPF ou CNPJ..."
            className="w-full"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>

        {isLoading ? (
          <p>Carregando clientes...</p>
        ) : (
          <div className="bg-white rounded-lg shadow overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CPF/CNPJ</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Idade / Tempo de empresa</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Renda mensal</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {data?.map((customer, idx) => (
                  <tr key={idx}>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-semibold mr-4">
                          {customer.name
                            .split(' ')
                            .map(n => n[0])
                            .slice(0, 2)
                            .join('')}
                        </div>
                        <div>
                          <div className="font-medium text-gray-900">{customer.name}</div>
                          <div className="text-sm text-gray-500">{customer.document_id.length === 11 ? 'Pessoa Física' : 'Pessoa Jurídica'}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">{formatCpfCnpj(customer.document_id)}</td>
                    <td className="px-6 py-4 whitespace-nowrap">{customer.idade} anos</td>
                    <td className="px-6 py-4 whitespace-nowrap text-green-600">
                      R$ {Number(customer.monthly_income).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap font-medium">
                      R$ {Number(customer.balance).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap flex gap-3 items-center">
                      <button
                        className="text-blue-600 hover:text-blue-900"
                        onClick={() => {
                          setEditingCustomer(customer);
                          setOpen(true);
                        }}
                      >
                        <Pencil size={16} />
                      </button>
                      <button
                        className="text-red-600 hover:text-red-900"
                        onClick={() => setCustomerToDelete(customer)}
                      >
                        <Trash2 size={16} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            <div className="p-4 text-sm text-gray-500">
              Exibindo {data?.length} de {data?.length} clientes
            </div>
          </div>
        )}
      </main>
    </div>
  );
}
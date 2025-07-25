import { useEffect } from 'react';
import { Input } from '../components/input';
import { Button } from '../components/button';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import toast from 'react-hot-toast';

const customerSchema = z.object({
  name: z.string().min(1, 'Informe o nome'),
  document_id: z.string().min(11, 'Informe o CPF/CNPJ'),
  birth_date: z.string().min(1, 'Informe a data de nascimento'),
  monthly_income: z.string().min(1, 'Informe a renda mensal'),
  balance: z.string().min(1, 'Informe o saldo inicial'),
});

export type CustomerFormData = z.infer<typeof customerSchema>;

export interface Customer {
  id: string;
  name: string;
  document_id: string;
  birth_date: string;
  monthly_income: string;
  balance: string;
}

interface CustomerModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (data: CustomerFormData, id?: string) => void;
  idUserIns: string | null;
  customer?: Customer | null;
  onSuccessUpdate?: () => void;
}

export default function CustomerModal({
  isOpen,
  onClose,
  onSubmit,
  idUserIns,
  customer,
  onSuccessUpdate,
}: CustomerModalProps) {
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<CustomerFormData>({
    resolver: zodResolver(customerSchema),
  });


  useEffect(() => {
    if (customer) {
      reset({
        name: customer.name,
        document_id: customer.document_id,
        birth_date: customer.birth_date,
        monthly_income: customer.monthly_income,
        balance: customer.balance,
      });
    } else {
      reset({
        name: '',
        document_id: '',
        birth_date: '',
        monthly_income: '',
        balance: '',
      });
    }
  }, [customer, reset]);

  if (!isOpen) return null;

  const handleClose = () => {
    reset();
    onClose();
  };

  const handleFormSubmit = async (data: CustomerFormData) => {
    if (!idUserIns) {
      toast.error('Usuário não autenticado.');
      return;
    }

    try {
       onSubmit(data, customer?.id);

      onSuccessUpdate?.();

      setTimeout(() => {
        handleClose();
      }, 1000);
    } catch (error: any) {
      const apiError = error.response?.data?.message || 'Erro inesperado.';
      toast.error(apiError);
    }
  };

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
      <div className="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl animate-fade-in">
        <h3 className="text-xl font-semibold mb-4 text-gray-800">
          {customer ? 'Editar Cliente' : 'Novo Cliente'}
        </h3>

        <form onSubmit={handleSubmit(handleFormSubmit)} className="space-y-4">
          <div>
            <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">
              Nome completo
            </label>
            <Input id="name" {...register('name')} placeholder="Nome completo" className="w-full" />
            {errors.name && <p className="text-sm text-red-500 mt-1">{errors.name.message}</p>}
          </div>

          <div>
            <label htmlFor="document_id" className="block text-sm font-medium text-gray-700 mb-1">
              CPF ou CNPJ
            </label>
            <Input
              id="document_id"
              {...register('document_id')}
              placeholder="CPF ou CNPJ"
              className={`w-full`}
              disabled={!!customer?.id}
              maxLength={14}
              
            />
            {errors.document_id && (
              <p className="text-sm text-red-500 mt-1">{errors.document_id.message}</p>
            )}
          </div>

          <div>
            <label htmlFor="birth_date" className="block text-sm font-medium text-gray-700 mb-1">
              Data de nascimento
            </label>
            <Input
              id="birth_date"
              {...register('birth_date')}
              type="date"
              placeholder="Data de nascimento"
              className="w-full"
            />
            {errors.birth_date && (
              <p className="text-sm text-red-500 mt-1">{errors.birth_date.message}</p>
            )}
          </div>

          <div>
            <label htmlFor="monthly_income" className="block text-sm font-medium text-gray-700 mb-1">
              Renda mensal
            </label>
            <Input
              id="monthly_income"
              {...register('monthly_income')}
              placeholder="Renda mensal"
              className="w-full"
            />
            {errors.monthly_income && (
              <p className="text-sm text-red-500 mt-1">{errors.monthly_income.message}</p>
            )}
          </div>

          <div>
            <label htmlFor="balance" className="block text-sm font-medium text-gray-700 mb-1">
              Saldo 
            </label>
            <Input
              id="balance"
              {...register('balance')}
              placeholder="Saldo"
              className="w-full"
              disabled={!!customer?.id}
            />
            {errors.balance && (
              <p className="text-sm text-red-500 mt-1">{errors.balance.message}</p>
            )}
          </div>

          <div className="flex justify-end gap-2 pt-4">
            <Button type="button" variant="ghost" onClick={handleClose}>
              Cancelar
            </Button>
            <Button type="submit" className="bg-blue-600 text-white hover:bg-blue-700">
              Salvar
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
}
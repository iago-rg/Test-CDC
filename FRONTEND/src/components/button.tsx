import { cn } from '../lib/utils';
import { type ButtonHTMLAttributes } from 'react';

type ButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: 'default' | 'ghost';
};

export function Button({ variant = 'default', className, ...props }: ButtonProps) {
  const base = 'px-4 py-2 rounded text-sm font-medium transition';
  const variants = {
    default: 'bg-blue-700 text-white hover:bg-blue-800',
    ghost: 'bg-transparent hover:bg-gray-100 text-gray-700',
  };
  return <button className={cn(base, variants[variant], className)} {...props} />;
}
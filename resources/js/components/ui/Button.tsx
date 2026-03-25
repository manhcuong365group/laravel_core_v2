import React from 'react';

type ButtonVariant = 'primary' | 'secondary' | 'ghost';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: ButtonVariant;
}

const variantClasses: Record<ButtonVariant, string> = {
    primary: 'bg-black text-white hover:bg-neutral-800',
    secondary: 'bg-white text-neutral-800 border border-neutral-300 hover:bg-neutral-50',
    ghost: 'bg-transparent text-neutral-700 hover:bg-neutral-100',
};

export function Button({ variant = 'secondary', className = '', ...props }: ButtonProps) {
    return (
        <button
            className={`inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition ${variantClasses[variant]} ${className}`}
            {...props}
        />
    );
}


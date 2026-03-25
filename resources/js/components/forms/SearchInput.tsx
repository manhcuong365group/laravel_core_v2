import React from 'react';

interface SearchInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
}

export function SearchInput({ label = 'Search', className = '', ...props }: SearchInputProps) {
    return (
        <label className="block">
            <span className="mb-1.5 block text-xs font-medium uppercase tracking-wide text-neutral-500">{label}</span>
            <input
                type="text"
                className={`w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 outline-none transition focus:border-neutral-800 ${className}`}
                {...props}
            />
        </label>
    );
}


import React from 'react';

export interface SelectOption {
    label: string;
    value: string;
}

interface SelectFilterProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
    label: string;
    options: SelectOption[];
}

export function SelectFilter({ label, options, className = '', ...props }: SelectFilterProps) {
    return (
        <label className="block">
            <span className="mb-1.5 block text-xs font-medium uppercase tracking-wide text-neutral-500">{label}</span>
            <select
                className={`w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 outline-none transition focus:border-neutral-800 ${className}`}
                {...props}
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        </label>
    );
}


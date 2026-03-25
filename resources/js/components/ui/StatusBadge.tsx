import React from 'react';

type StatusType = 'active' | 'inactive' | 'pending' | 'blocked';

interface StatusBadgeProps {
    status: StatusType;
    label?: string;
}

const styles: Record<StatusType, string> = {
    active: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    inactive: 'bg-neutral-100 text-neutral-700 border-neutral-200',
    pending: 'bg-amber-50 text-amber-700 border-amber-200',
    blocked: 'bg-rose-50 text-rose-700 border-rose-200',
};

export function StatusBadge({ status, label }: StatusBadgeProps) {
    return (
        <span className={`inline-flex rounded-full border px-2.5 py-1 text-xs font-medium ${styles[status]}`}>
            {label ?? status}
        </span>
    );
}


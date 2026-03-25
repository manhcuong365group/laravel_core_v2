import React from 'react';

interface PageHeaderProps {
    title: string;
    subtitle?: string;
    actions?: React.ReactNode;
}

export function PageHeader({ title, subtitle, actions }: PageHeaderProps) {
    return (
        <div className="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 className="text-2xl font-semibold text-neutral-900">{title}</h1>
                {subtitle ? <p className="mt-1 text-sm text-neutral-500">{subtitle}</p> : null}
            </div>
            {actions ? <div className="flex items-center gap-2">{actions}</div> : null}
        </div>
    );
}


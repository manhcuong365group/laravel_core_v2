import React from 'react';

interface AuthLayoutProps {
    title: string;
    subtitle?: string;
    children: React.ReactNode;
}

export default function AuthLayout({ title, subtitle, children }: AuthLayoutProps) {
    return (
        <div className="flex min-h-screen items-center justify-center bg-neutral-100 px-4 dark:bg-neutral-950">
            <div className="w-full max-w-md rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h1 className="text-xl font-semibold text-neutral-900 dark:text-neutral-100">{title}</h1>
                {subtitle ? <p className="mt-1 text-sm text-neutral-500">{subtitle}</p> : null}
                <div className="mt-6">{children}</div>
            </div>
        </div>
    );
}


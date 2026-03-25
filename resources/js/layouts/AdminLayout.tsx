import React from 'react';

interface AdminLayoutProps {
    title?: string;
    children: React.ReactNode;
}

export default function AdminLayout({ title = 'Admin', children }: AdminLayoutProps) {
    return (
        <div className="min-h-screen bg-neutral-100 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
            <div className="flex min-h-screen">
                <aside className="hidden w-64 border-r border-neutral-200 bg-white p-5 lg:block dark:border-neutral-800 dark:bg-neutral-900">
                    <div className="mb-6 text-lg font-semibold">{title}</div>
                    <nav className="space-y-1 text-sm">
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Dashboard
                        </a>
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Users
                        </a>
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Roles
                        </a>
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Orders
                        </a>
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Reports
                        </a>
                        <a className="block rounded-md px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800" href="#">
                            Settings
                        </a>
                    </nav>
                </aside>
                <div className="flex min-w-0 flex-1 flex-col">
                    <header className="border-b border-neutral-200 bg-white dark:border-neutral-800 dark:bg-neutral-900">
                        <div className="flex items-center justify-between gap-3 px-4 py-3 lg:px-6">
                            <input
                                type="search"
                                placeholder="Search..."
                                className="w-full max-w-md rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:border-neutral-800 dark:border-neutral-700 dark:bg-neutral-900"
                            />
                            <div className="flex items-center gap-3 text-sm">
                                <button className="rounded-md border border-neutral-300 px-3 py-2 dark:border-neutral-700">Notifications</button>
                                <button className="rounded-md border border-neutral-300 px-3 py-2 dark:border-neutral-700">Profile</button>
                            </div>
                        </div>
                    </header>
                    <main className="flex-1 px-4 py-6 lg:px-6">{children}</main>
                    <footer className="border-t border-neutral-200 bg-white px-4 py-3 text-xs text-neutral-500 dark:border-neutral-800 dark:bg-neutral-900 lg:px-6">
                        <div className="flex items-center justify-between">
                            <span>Version 1.0.0</span>
                            <span>Copyright 2026</span>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    );
}


import React from 'react';

interface DataTableProps {
    headers: string[];
    children: React.ReactNode;
}

export function DataTable({ headers, children }: DataTableProps) {
    return (
        <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm">
            <div className="overflow-x-auto">
                <table className="min-w-full text-left">
                    <thead className="bg-neutral-50">
                        <tr>
                            {headers.map((header) => (
                                <th key={header} className="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                                    {header}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-neutral-100">{children}</tbody>
                </table>
            </div>
        </div>
    );
}


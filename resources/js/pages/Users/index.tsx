import React, { useMemo, useState } from 'react';
import AdminLayout from '../../layouts/AdminLayout';
import { Button } from '../../components/ui/Button';
import { PageHeader } from '../../components/ui/PageHeader';
import { FilterBar } from '../../components/forms/FilterBar';
import { SearchInput } from '../../components/forms/SearchInput';
import { SelectFilter } from '../../components/forms/SelectFilter';
import { DataTable } from '../../components/tables/DataTable';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PaginationFooter } from '../../components/tables/PaginationFooter';

type UserStatus = 'active' | 'inactive' | 'blocked';

interface UserRow {
    id: number;
    name: string;
    email: string;
    role: string;
    status: UserStatus;
    createdAt: string;
}

const usersSeed: UserRow[] = [
    { id: 1, name: 'Nguyen Van A', email: 'a@example.com', role: 'Admin', status: 'active', createdAt: '2026-03-01' },
    { id: 2, name: 'Tran Thi B', email: 'b@example.com', role: 'Editor', status: 'inactive', createdAt: '2026-02-25' },
    { id: 3, name: 'Le Van C', email: 'c@example.com', role: 'Viewer', status: 'blocked', createdAt: '2026-02-20' },
];

export default function UsersPage() {
    const [search, setSearch] = useState('');
    const [role, setRole] = useState('');
    const [status, setStatus] = useState('');
    const [page, setPage] = useState(1);

    const filteredUsers = useMemo(() => {
        return usersSeed.filter((user) => {
            const searchMatch =
                search.length === 0 ||
                user.name.toLowerCase().includes(search.toLowerCase()) ||
                user.email.toLowerCase().includes(search.toLowerCase());
            const roleMatch = role.length === 0 || user.role === role;
            const statusMatch = status.length === 0 || user.status === status;
            return searchMatch && roleMatch && statusMatch;
        });
    }, [search, role, status]);

    return (
        <AdminLayout title="Admin">
            <PageHeader
                title="Users"
                actions={
                    <Button variant="primary" type="button">
                        + Tao moi
                    </Button>
                }
            />

            <FilterBar>
                <SearchInput label="Search" placeholder="Name or email..." value={search} onChange={(e) => setSearch(e.target.value)} />
                <SelectFilter
                    label="Filter role"
                    value={role}
                    onChange={(e) => setRole(e.target.value)}
                    options={[
                        { label: 'All roles', value: '' },
                        { label: 'Admin', value: 'Admin' },
                        { label: 'Editor', value: 'Editor' },
                        { label: 'Viewer', value: 'Viewer' },
                    ]}
                />
                <SelectFilter
                    label="Filter status"
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    options={[
                        { label: 'All status', value: '' },
                        { label: 'Active', value: 'active' },
                        { label: 'Inactive', value: 'inactive' },
                        { label: 'Blocked', value: 'blocked' },
                    ]}
                />
            </FilterBar>

            <DataTable headers={['Ten', 'Email', 'Role', 'Trang thai', 'Ngay tao', 'Thao tac']}>
                {filteredUsers.map((user) => (
                    <tr key={user.id} className="hover:bg-neutral-50">
                        <td className="px-4 py-3 text-sm text-neutral-900">{user.name}</td>
                        <td className="px-4 py-3 text-sm text-neutral-700">{user.email}</td>
                        <td className="px-4 py-3 text-sm text-neutral-700">{user.role}</td>
                        <td className="px-4 py-3">
                            <StatusBadge status={user.status} />
                        </td>
                        <td className="px-4 py-3 text-sm text-neutral-700">{user.createdAt}</td>
                        <td className="px-4 py-3">
                            <div className="flex gap-2">
                                <Button variant="ghost" type="button" className="px-2 py-1 text-xs">
                                    Edit
                                </Button>
                                <Button variant="ghost" type="button" className="px-2 py-1 text-xs text-rose-600">
                                    Delete
                                </Button>
                            </div>
                        </td>
                    </tr>
                ))}
            </DataTable>

            <PaginationFooter
                page={page}
                totalPages={1}
                totalRecords={filteredUsers.length}
                onPrev={() => setPage((current) => Math.max(1, current - 1))}
                onNext={() => setPage((current) => current + 1)}
            />
        </AdminLayout>
    );
}


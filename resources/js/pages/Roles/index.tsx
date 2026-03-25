import React from 'react';
import AdminLayout from '../../layouts/AdminLayout';
import { Card } from '../../components/ui/Card';
import { PageHeader } from '../../components/ui/PageHeader';

export default function RolesPage() {
    return (
        <AdminLayout title="Admin">
            <PageHeader title="Roles & Permissions" />
            <Card>
                <p className="text-sm text-neutral-600">Role/permission management scaffold is ready for integration.</p>
            </Card>
        </AdminLayout>
    );
}


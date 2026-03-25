import React from 'react';
import AdminLayout from '../../layouts/AdminLayout';
import { Card } from '../../components/ui/Card';
import { PageHeader } from '../../components/ui/PageHeader';

export default function SettingsPage() {
    return (
        <AdminLayout title="Admin">
            <PageHeader title="Settings" />
            <Card>
                <p className="text-sm text-neutral-600">Settings page scaffold is ready for your forms and tabs.</p>
            </Card>
        </AdminLayout>
    );
}


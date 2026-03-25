import React from 'react';
import AdminLayout from '../../layouts/AdminLayout';
import { Card } from '../../components/ui/Card';
import { PageHeader } from '../../components/ui/PageHeader';
import { Skeleton } from '../../components/ui/Skeleton';

export default function DashboardPage() {
    return (
        <AdminLayout title="Admin">
            <PageHeader title="Dashboard" subtitle="Overview and recent activities" />
            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card>
                    <p className="text-sm text-neutral-500">Users</p>
                    <p className="mt-2 text-2xl font-semibold">1,245</p>
                </Card>
                <Card>
                    <p className="text-sm text-neutral-500">Orders</p>
                    <p className="mt-2 text-2xl font-semibold">382</p>
                </Card>
                <Card>
                    <p className="text-sm text-neutral-500">Revenue</p>
                    <p className="mt-2 text-2xl font-semibold">$24,500</p>
                </Card>
            </div>
            <div className="mt-5 grid grid-cols-1 gap-4 lg:grid-cols-2">
                <Card>
                    <h3 className="mb-3 text-sm font-semibold">Chart</h3>
                    <Skeleton className="h-52 w-full" />
                </Card>
                <Card>
                    <h3 className="mb-3 text-sm font-semibold">Recent Activity</h3>
                    <div className="space-y-3">
                        <Skeleton className="h-8 w-full" />
                        <Skeleton className="h-8 w-full" />
                        <Skeleton className="h-8 w-full" />
                    </div>
                </Card>
            </div>
        </AdminLayout>
    );
}


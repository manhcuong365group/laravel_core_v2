import React from 'react';
import { Card } from '../ui/Card';

interface FilterBarProps {
    children: React.ReactNode;
}

export function FilterBar({ children }: FilterBarProps) {
    return (
        <Card className="mb-5">
            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">{children}</div>
        </Card>
    );
}


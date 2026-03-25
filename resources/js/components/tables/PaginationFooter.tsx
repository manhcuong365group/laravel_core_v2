import React from 'react';
import { Button } from '../ui/Button';

interface PaginationFooterProps {
    page: number;
    totalPages: number;
    totalRecords: number;
    onPrev: () => void;
    onNext: () => void;
}

export function PaginationFooter({ page, totalPages, totalRecords, onPrev, onNext }: PaginationFooterProps) {
    return (
        <div className="mt-4 flex flex-col gap-3 text-sm text-neutral-600 md:flex-row md:items-center md:justify-between">
            <span>Total records: {totalRecords}</span>
            <div className="flex items-center gap-2">
                <Button variant="secondary" onClick={onPrev} disabled={page <= 1}>
                    Previous
                </Button>
                <span className="px-2">
                    Page {page}/{totalPages}
                </span>
                <Button variant="secondary" onClick={onNext} disabled={page >= totalPages}>
                    Next
                </Button>
            </div>
        </div>
    );
}


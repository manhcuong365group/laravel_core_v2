@props([
    'status' => 'default',
])

@php
    $originalStatus = $status;
    
    // Handle booleans and numeric strings
    if (is_bool($status) || $status === '1' || $status === 1) {
        $status = $status ? 'active' : 'inactive';
    } elseif ($status === '0' || $status === 0) {
        $status = 'inactive';
    }

    $statusStr = (string) $status;
    $classes = match (true) {
        in_array($statusStr, ['new', 'subscribed', 'pending', 'draft']) => 'bg-blue-500/15 text-blue-300 border-blue-500/20',
        in_array($statusStr, ['completed', 'approved', 'published', 'active', 'replied', '1', 'true']) => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/20',
        in_array($statusStr, ['cancelled', 'deleted', 'inactive', 'unsubscribed', '0', 'false']) => 'bg-rose-500/15 text-rose-300 border-rose-500/20',
        in_array($statusStr, ['read', 'processing', 'scheduled']) => 'bg-amber-500/15 text-amber-300 border-amber-500/20',
        default => 'bg-white/10 text-text-muted border-white/15',
    };

    // Custom labels for statuses in Vietnamese
    $label = match ($statusStr) {
        'scheduled' => 'Lên lịch',
        '1', 'true', 'active', 'published', 'approved' => 'Hiển thị',
        '0', 'false', 'inactive' => 'Ẩn',
        'pending' => 'Chờ xử lý',
        'subscribed' => 'Đã đăng ký',
        'unsubscribed' => 'Đã hủy đăng ký',
        'completed' => 'Hoàn thành',
        'deleted' => 'Đã xóa',
        'replied' => 'Đã phản hồi',
        'read' => 'Đã xem',
        'processing' => 'Đang xử lý',
        'new' => 'Mới',
        'draft' => 'Bản nháp',
        'cancelled' => 'Đã hủy',
        default => $slot->isEmpty() ? ucfirst($statusStr) : $slot,
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {$classes}"]) }}>
    {{ $label }}
</span>

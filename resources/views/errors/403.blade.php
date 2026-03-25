@extends(.layouts.app')

@section('title', 'Truy cập bị từ chối')

@section('content')
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="text-center max-w-lg">
            <div class="w-24 h-24 bg-danger/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ti ti-lock text-5xl text-danger"></i>
            </div>
            <h1 class="text-4xl font-black text-text-main mb-3">403</h1>
            <h2 class="text-xl font-bold text-text-main mb-4">Truy cập bị từ chối</h2>
            <p class="text-text-muted mb-8">
                Bạn không có quyền truy cập trang này. Vui lòng liên hệ quản trị viên nếu bạn nghĩ đây là lỗi.
            </p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('backend.dashboard') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl transition-all">
                    <i class="ti ti-arrow-left"></i> Về trang chủ
                </a>
                <a href="javascript:history.back()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-bg-surface border border-border-glass text-text-muted font-bold rounded-xl transition-all hover:bg-white/10">
                    <i class="ti ti-history"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
@endsection


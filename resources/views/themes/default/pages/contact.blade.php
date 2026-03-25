@extends('theme::layouts.app')

@section('title', 'Liên hệ - ' . (app(\App\Services\TenantManager::class)->getTenant()?->name ?? config('app.name')))

@section('content')
    <div class="bg-bg-main py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <!-- Contact Info -->
                    <div>
                        <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-8">Liên hệ với chúng tôi</h1>
                        <p class="text-xl text-text-muted leading-relaxed mb-12">
                            Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy gửi tin nhắn hoặc gọi cho chúng tôi bất cứ
                            khi nào bạn cần.
                        </p>

                        <div class="space-y-8">
                            <div class="glass-card p-6 flex items-start gap-6">
                                <div
                                    class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg mb-1">Địa chỉ</h3>
                                    <p class="text-text-muted">123 Đường ABC, Quận X, TP. Hồ Chí Minh</p>
                                </div>
                            </div>

                            <div class="glass-card p-6 flex items-start gap-6">
                                <div
                                    class="w-12 h-12 bg-accent/10 rounded-2xl flex items-center justify-center text-accent shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg mb-1">Điện thoại</h3>
                                    <p class="text-text-muted">0123 456 789</p>
                                </div>
                            </div>

                            <div class="glass-card p-6 flex items-start gap-6">
                                <div
                                    class="w-12 h-12 bg-success/10 rounded-2xl flex items-center justify-center text-success shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg mb-1">Email</h3>
                                    <p class="text-text-muted">contact@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="glass-card p-8 md:p-12">
                        <h2 class="text-3xl font-black mb-10">Gửi lời nhắn</h2>

                        @if (session('success'))
                            <div class="bg-success/10 border border-success/20 text-success p-4 rounded-xl mb-8">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold ml-1">Họ tên</label>
                                    <input type="text" name="name" required
                                        class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold ml-1">Email</label>
                                    <input type="email" name="email" required
                                        class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold ml-1">Số điện thoại</label>
                                <input type="text" name="phone"
                                    class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold ml-1">Tiêu đề</label>
                                <input type="text" name="subject"
                                    class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold ml-1">Lời nhắn</label>
                                <textarea name="message" rows="5" required
                                    class="w-full px-6 py-4 bg-bg-main border border-border-glass rounded-2xl text-sm focus:outline-none focus:border-primary transition-colors"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-5 bg-primary text-white font-black rounded-2xl shadow-glow-primary hover:opacity-90 transition-all text-lg">Gửi
                                ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

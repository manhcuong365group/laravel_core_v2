<header class="w-full z-50">
    <!-- Top Promotional Banner (Optional / Seasonal) -->
    <div class="w-full bg-[#111111] py-1 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <span class="text-[10px] sm:text-[11px] text-white/70 font-bold tracking-[0.2em] uppercase">Hệ thống nâng cấp
                xe chuyên nghiệp toàn quốc - 365Group</span>
        </div>
    </div>

    <!-- Main Header Bar -->
    <div class="bg-primary text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-20 items-center gap-4 lg:gap-12">
                <!-- Logo -->
                <a href="/" class="shrink-0 flex items-center hover:opacity-90 transition-opacity">
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-black tracking-tighter italic uppercase">365<span
                                class="text-white/80">Group</span></span>
                    </div>
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-2xl relative group">
                    <input type="text" placeholder="Bạn cần tìm gì?"
                        class="w-full h-11 pl-5 pr-12 rounded-full border-none bg-white text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-white/20 transition-all font-semibold text-sm">
                    <button
                        class="absolute right-0 top-0 h-full w-14 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <i class="ti ti-search text-xl"></i>
                    </button>
                </div>

                <!-- Right Links -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="/gioi-thieu"
                        class="text-sm font-black hover:text-white/80 transition-colors uppercase tracking-tight">Giới
                        thiệu</a>
                    <a href="/tin-tuc"
                        class="text-sm font-black hover:text-white/80 transition-colors uppercase tracking-tight">Tin
                        tức</a>
                    @auth
                        <a href="{{ route('backend.dashboard') }}"
                            class="flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-black uppercase transition-all border border-white/10">
                            <i class="ti ti-user-bolt"></i> Admin
                        </a>
                    @else
                        <a href="/login"
                            class="text-sm font-black uppercase hover:text-white/80 transition-colors flex items-center gap-1.5">
                            <i class="ti ti-login-2"></i> Đăng nhập
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Action -->
                <div class="flex lg:hidden items-center gap-2">
                    <button class="p-2 text-white hover:bg-white/10 rounded-lg">
                        <i class="ti ti-search text-2xl"></i>
                    </button>
                    <button class="p-2 text-white hover:bg-white/10 rounded-lg">
                        <i class="ti ti-menu-2 text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Icon Navigation Menu -->
    <div
        class="bg-primary text-white border-t border-white/10 sticky top-0 shadow-2xl z-40 overflow-x-auto scrollbar-hide">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <div class="flex items-center justify-between min-w-[750px] lg:min-w-0">
                @php
                    $navItems = [
                        ['label' => 'Đèn tăng sáng', 'icon' => 'ti-bulb', 'url' => '#'],
                        ['label' => 'Phim cách nhiệt', 'icon' => 'ti-mountain', 'url' => '#'],
                        ['label' => 'PPF / WRAP FILM', 'icon' => 'ti-car', 'url' => '#'],
                        ['label' => 'Chi nhánh', 'icon' => 'ti-map-pin', 'url' => '#'],
                        ['label' => 'Tra cứu BH', 'icon' => 'ti-shield-check', 'url' => '#'],
                        ['label' => 'Đồ chơi xe', 'icon' => 'ti-tools', 'url' => '#'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a href="{{ $item['url'] }}"
                        class="flex-1 flex flex-col items-center py-4 px-2 border-b-4 border-transparent hover:border-white hover:bg-white/5 transition-all group">
                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-xl group-hover:bg-white/10 transition-colors mb-1.5">
                            <i class="ti {{ $item['icon'] }} text-2xl group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span
                            class="text-[10px] sm:text-[11px] font-black uppercase tracking-tight text-center leading-tight">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>


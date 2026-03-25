@extends('frontend.layouts.app')

@section('title', '365Group - Hệ thống nâng cấp xe chuyên nghiệp toàn quốc')

@section('content')
    <!-- Hero Section -->
    <div class="bg-white py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Main Slider -->
                <div class="lg:col-span-8">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl aspect-[16/8] bg-slate-100 group">
                        <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c3d9?q=80&w=1920&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="Main banner">
                        <div
                            class="absolute inset-0 bg-linear-to-r from-black/60 to-transparent flex items-center p-8 sm:p-16">
                            <div class="max-w-lg">
                                <span
                                    class="inline-block px-4 py-1.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-6">Mới
                                    nhất</span>
                                <h1
                                    class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight leading-[1.1] mb-8">
                                    Nâng cấp Ánh Sáng <br> <span class="bg-primary px-2">Đỉnh Cao</span> 2025
                                </h1>
                                <a href="#"
                                    class="px-8 py-3.5 bg-white text-primary font-black uppercase rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all inline-block">Khám
                                    phá ngay</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Banners -->
                <div class="lg:col-span-4 hidden lg:block">
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 h-full">
                        <div class="h-full rounded-2xl overflow-hidden bg-slate-100 shadow-lg relative group">
                            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=800&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center p-4">
                                <span
                                    class="text-white font-black uppercase text-center tracking-widest text-sm drop-shadow-lg">Phim
                                    cách nhiệt</span>
                            </div>
                        </div>
                        <div class="h-full rounded-2xl overflow-hidden bg-slate-100 shadow-lg relative group">
                            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center p-4">
                                <span
                                    class="text-white font-black uppercase text-center tracking-widest text-sm drop-shadow-lg">Bảng
                                    giá 2025</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Category Section: Đèn tăng sáng -->
    <div class="py-16 bg-bg-main">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-center justify-between border-b-2 border-primary mb-10 pb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-xl">
                        <i class="ti ti-bulb text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Đèn tăng sáng</h2>
                </div>
                <a href="#" class="text-sm font-bold text-primary hover:underline flex items-center gap-1 group">
                    Xem tất cả <i class="ti ti-chevron-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Product Grid (5 columns on desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @for ($i = 0; $i < 5; $i++)
                    <div
                        class="bg-white rounded-2xl p-4 border border-slate-100 hover:shadow-2xl hover:border-primary/20 transition-all group flex flex-col h-full relative overflow-hidden">
                        <div class="absolute top-2 left-2 z-10">
                            <span
                                class="px-2 py-0.5 bg-primary text-white text-[9px] font-black uppercase tracking-widest rounded-md">Mới</span>
                        </div>
                        <div
                            class="aspect-square bg-slate-50 rounded-xl mb-4 p-2 flex items-center justify-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1593452135709-0018d45aa235?q=80&w=400&auto=format&fit=crop"
                                class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex-1 flex flex-col">
                            <h3
                                class="text-xs font-black text-slate-900 uppercase tracking-tight leading-tight mb-2 line-clamp-2">
                                Bi LED GTR PREMIUM ULTRA V3 2025</h3>
                            <div class="mt-auto">
                                <div class="text-primary font-black text-sm">7.500.000đ</div>
                                <div class="text-[10px] text-slate-400 line-through">8.500.000đ</div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Banner Section -->
    <div class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl overflow-hidden h-[300px] shadow-xl relative group">
                <img src="https://images.unsplash.com/photo-1511919884226-fd3cad34687c?q=80&w=1920&auto=format&fit=crop"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-linear-to-r from-primary/80 to-transparent flex items-center p-12">
                    <div class="max-w-xl text-white">
                        <h2 class="text-4xl font-black uppercase mb-4 tracking-tighter">Bảo hành điện tử <br> toàn quốc</h2>
                        <p class="text-lg opacity-90 mb-6 font-medium">Hệ thống bảo hành kích hoạt tự động theo số điện
                            thoại khách hàng.</p>
                        <a href="#"
                            class="px-8 py-3 bg-white text-primary font-black uppercase rounded-2xl shadow-xl hover:scale-105 transition-all inline-block">Kiểm
                            tra ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News Section -->
    <div class="py-20 bg-[#F9F9F9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-slate-900 uppercase tracking-widest mb-4">Góc kỹ thuật & Tin tức</h2>
                <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 0; $i < 3; $i++)
                    <div
                        class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all group border border-slate-100">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1493238792000-8113da705763?q=80&w=600&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-8">
                            <div class="flex items-center gap-2 mb-4">
                                <span
                                    class="bg-primary/10 text-primary text-[10px] font-black uppercase px-3 py-1 rounded-full">Tin
                                    tức</span>
                                <span class="text-xs text-slate-400 font-medium italic">10/03/2026</span>
                            </div>
                            <h3
                                class="text-xl font-black text-slate-900 uppercase tracking-tight leading-tight mb-4 group-hover:text-primary transition-colors line-clamp-2">
                                Hướng dẫn chọn đèn bi led cho xe Vinfast VF8</h3>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-6 font-medium">Tìm hiểu các
                                dòng bi led phù hợp nhất cho dòng xe điện Vinfast VF8, tối ưu công suất và thẩm mỹ...</p>
                            <a href="#"
                                class="text-xs font-black text-primary uppercase tracking-widest flex items-center gap-2 group/btn">
                                Xem chi tiết <i
                                    class="ti ti-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

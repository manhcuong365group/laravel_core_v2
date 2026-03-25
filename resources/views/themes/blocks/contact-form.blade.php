{{-- Contact Form Block --}}
<section id="{{ $blockId }}" class="py-24 px-6 relative overflow-hidden"
    @if (!empty($data['bg_color'])) style="background-color: {{ $data['bg_color'] }}" @else class="bg-slate-50" @endif
    x-data="{ show: false }" x-intersect.once="show = true">

    {{-- Decorative Background --}}
    <div class="absolute inset-0 bg-slate-50/50 backdrop-blur-3xl -z-10"></div>
    <div
        class="absolute top-1/4 -left-64 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl mix-blend-multiply pointer-events-none">
    </div>
    <div
        class="absolute bottom-1/4 -right-64 w-[500px] h-[500px] bg-rose-200/30 rounded-full blur-3xl mix-blend-multiply pointer-events-none">
    </div>

    <div class="max-w-6xl mx-auto relative z-10 transition-all duration-1000 transform translate-y-8 opacity-0"
        :class="show ? 'translate-y-0 opacity-100' : ''">

        @if (!empty($data['title']))
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">{{ $data['title'] }}</h2>
                @if (!empty($data['subtitle']))
                    <p class="text-slate-500 text-lg lg:text-xl font-medium max-w-2xl mx-auto">{{ $data['subtitle'] }}
                    </p>
                @endif
            </div>
        @endif

        <div
            class="bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 overflow-hidden border border-slate-100/60 p-2">
            <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-8 rounded-4xl overflow-hidden bg-slate-50">

                {{-- Contact Info Panel --}}
                <div class="bg-indigo-600 p-10 lg:p-14 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -m-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -m-20 w-64 h-64 bg-indigo-900/40 rounded-full blur-3xl"></div>

                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <h3 class="text-3xl font-black mb-4">Thông Tin Liên Hệ</h3>
                            <p class="text-indigo-100 text-lg mb-12">Hãy để lại lời nhắn, chúng tôi sẽ liên hệ lại với
                                bạn trong thời gian sớm nhất.</p>

                            <div class="space-y-8">
                                @if ($data['show_phone'] ?? true)
                                    @php $phone = \App\Models\Setting::get('hotline', ''); @endphp
                                    @if ($phone)
                                        <div
                                            class="flex items-center gap-6 group cursor-pointer hover:-translate-y-1 transition-transform">
                                            <div
                                                class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-white/20 transition-colors">
                                                <i class="ti ti-phone text-2xl text-white"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-medium text-indigo-200 mb-1 text-sm uppercase tracking-widest">
                                                    Hotline</h4>
                                                <p class="text-xl font-bold">{{ $phone }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($data['show_address'] ?? true)
                                    @php $address = \App\Models\Setting::get('address', ''); @endphp
                                    @if ($address)
                                        <div
                                            class="flex items-center gap-6 group cursor-pointer hover:-translate-y-1 transition-transform">
                                            <div
                                                class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-white/20 transition-colors">
                                                <i class="ti ti-map-pin text-2xl text-white"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-medium text-indigo-200 mb-1 text-sm uppercase tracking-widest">
                                                    Địa chỉ</h4>
                                                <p class="text-lg font-bold leading-snug">{{ $address }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($data['show_email'] ?? true)
                                    @php $email = \App\Models\Setting::get('email', ''); @endphp
                                    @if ($email)
                                        <div
                                            class="flex items-center gap-6 group cursor-pointer hover:-translate-y-1 transition-transform">
                                            <div
                                                class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-white/20 transition-colors">
                                                <i class="ti ti-mail text-2xl text-white"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-medium text-indigo-200 mb-1 text-sm uppercase tracking-widest">
                                                    Email</h4>
                                                <p class="text-lg font-bold leading-snug">{{ $email }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form Form --}}
                <div class="p-10 lg:p-14 bg-white">
                    <form action="{{ route('contact.store') ?? '#' }}" method="POST"
                        class="space-y-6 h-full flex flex-col justify-center">
                        @csrf
                        <div class="space-y-6">
                            <div class="relative group">
                                <label
                                    class="absolute -top-3 left-4 px-2 bg-white text-xs font-bold text-slate-500 uppercase tracking-widest z-10 transition-colors group-focus-within:text-indigo-600">Họ
                                    và tên *</label>
                                <input type="text" name="name" required
                                    class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-white focus:bg-white text-slate-900 font-medium focus:ring-0 focus:border-indigo-500 transition-colors shadow-sm placeholder-slate-300">
                            </div>

                            <div class="relative group">
                                <label
                                    class="absolute -top-3 left-4 px-2 bg-white text-xs font-bold text-slate-500 uppercase tracking-widest z-10 transition-colors group-focus-within:text-indigo-600">Số
                                    điện thoại *</label>
                                <input type="tel" name="phone" required
                                    class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-white focus:bg-white text-slate-900 font-medium focus:ring-0 focus:border-indigo-500 transition-colors shadow-sm placeholder-slate-300">
                            </div>

                            <div class="relative group">
                                <label
                                    class="absolute -top-3 left-4 px-2 bg-white text-xs font-bold text-slate-500 uppercase tracking-widest z-10 transition-colors group-focus-within:text-indigo-600">Email
                                    (Tùy chọn)</label>
                                <input type="email" name="email"
                                    class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-white focus:bg-white text-slate-900 font-medium focus:ring-0 focus:border-indigo-500 transition-colors shadow-sm placeholder-slate-300">
                            </div>

                            <div class="relative group">
                                <label
                                    class="absolute -top-3 left-4 px-2 bg-white text-xs font-bold text-slate-500 uppercase tracking-widest z-10 transition-colors group-focus-within:text-indigo-600">Nội
                                    dung *</label>
                                <textarea name="message" rows="4" required
                                    class="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-white focus:bg-white text-slate-900 font-medium focus:ring-0 focus:border-indigo-500 transition-colors shadow-sm resize-none"></textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full px-8 py-4 mt-6 bg-slate-900 text-white font-black rounded-xl hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                            <i class="ti ti-send text-xl"></i> Gửi Yêu Cầu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

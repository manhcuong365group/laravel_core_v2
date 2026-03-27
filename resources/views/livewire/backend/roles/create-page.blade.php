<div class="max-w-[1200px] mx-auto pb-20">
    {{-- Glassmorphism Background Elements --}}
    <div class="fixed top-20 right-20 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] -z-10 animate-pulse"></div>
    <div class="fixed bottom-20 left-20 w-[400px] h-[400px] bg-secondary/5 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 2s"></div>

    {{-- Breadcrumb & Header --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 px-4">
        <div class="space-y-4">
            <x-backend.ui.button variant="neutral" :href="route('backend.users.roles')" icon="ti ti-arrow-left" class="!rounded-2xl !bg-white/5 border-white/5 hover:!bg-white/10 group h-10">
                Quay lại danh sách
            </x-backend.ui.button>
            <div>
                <h1 class="text-4xl font-black text-text-main tracking-tight flex items-center gap-4">
                    <span class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-lg shadow-primary/5 rotate-3">
                        <i class="ti ti-shield-plus"></i>
                    </span>
                    Thiết lập Vai trò
                </h1>
                <p class="text-text-muted mt-3 font-medium opacity-60 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary/40"></span>
                    Xác định tên định danh và phân phối các quyền thực thi cho vai trò mới.
                </p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-12 gap-8 px-4">
        {{-- Left: Main Info --}}
        <div class="lg:col-span-8 space-y-8">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-[2.5rem] opacity-50 blur-[2px] group-hover:opacity-100 transition-opacity"></div>
                <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/60 backdrop-blur-xl p-10 overflow-hidden">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-primary">
                            <i class="ti ti-info-circle text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black text-text-main tracking-tight uppercase italic opacity-80">Thông tin định danh</h2>
                    </div>

                    <div class="space-y-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black uppercase tracking-[0.2em] text-text-muted px-1 opacity-70">Tên vai trò (Slug)</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i class="ti ti-shield text-text-muted/40 group-focus-within/input:text-primary transition-colors"></i>
                                </div>
                                <input type="text"
                                    wire:model="name"
                                    placeholder="VD: marketing, manager, editor..."
                                    class="w-full h-14 pl-12 pr-4 rounded-2xl border border-white/5 bg-white/[0.03] text-sm text-text-main placeholder:text-text-muted/30 focus:border-primary/30 focus:bg-white/[0.05] focus:outline-none focus:ring-4 focus:ring-primary/5 transition-all font-bold tracking-wide"
                                    required
                                >
                            </div>
                            @error('name') <p class="text-[10px] font-black uppercase tracking-widest text-danger px-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-text-muted italic px-1 opacity-50">* Nên sử dụng tiếng Anh không dấu, ngăn cách bởi dấu gạch ngang.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permissions Selection --}}
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-secondary/20 to-primary/20 rounded-[2.5rem] opacity-40 blur-[2px] group-hover:opacity-80 transition-opacity"></div>
                <div class="relative rounded-[2.5rem] border border-white/5 bg-bg-surface/40 backdrop-blur-xl p-10">
                    <div class="flex items-center justify-between mb-10 pb-6 border-b border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-secondary">
                                <i class="ti ti-lock-access text-xl"></i>
                            </div>
                            <h2 class="text-xl font-black text-text-main tracking-tight uppercase italic opacity-80">Cấu hình quyền hạn</h2>
                        </div>
                        <div class="text-[10px] font-black text-text-muted bg-white/5 border border-white/5 px-4 py-1.5 rounded-full uppercase tracking-widest">
                            Có {{ $permissions->count() }} quyền hệ thống
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <label class="relative group/perm cursor-pointer">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="hidden peer">
                                <div class="h-full flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-white/[0.02] transition-all duration-300 peer-checked:border-primary/40 peer-checked:bg-primary/5 hover:bg-white/[0.05] group-hover/perm:border-white/10">
                                    <div class="w-8 h-8 rounded-lg border border-white/5 bg-white/5 flex items-center justify-center transition-all peer-checked:bg-primary peer-checked:border-primary peer-checked:shadow-[0_0_12px_rgba(var(--primary-rgb),0.5)]">
                                        <i class="ti ti-check text-white opacity-0 transition-opacity peer-checked:opacity-100 text-xs translate-y-px"></i>
                                    </div>
                                    <span class="text-[11px] font-black text-text-muted uppercase tracking-wider peer-checked:text-text-main transition-colors truncate">
                                        {{ $permission->name }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPermissions') <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-danger px-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Right: Status & Action --}}
        <div class="lg:col-span-4 space-y-8">
            <div class="sticky top-8 space-y-8">
                {{-- Quick Stats Card --}}
                <div class="rounded-[2.5rem] border border-white/5 bg-gradient-to-br from-bg-surface/80 to-bg-surface/40 backdrop-blur-xl p-10 overflow-hidden relative group">
                    {{-- Decorative Element --}}
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl transition-transform duration-1000 group-hover:scale-150"></div>
                    
                    <h3 class="text-sm font-black text-text-main uppercase tracking-[0.2em] mb-8 italic flex items-center gap-3">
                        <i class="ti ti-stairs text-primary"></i>
                        Trạng thái thiết lập
                    </h3>

                    <div class="space-y-6">
                        <div class="flex justify-between items-center py-4 border-b border-white/5">
                            <span class="text-[11px] font-black text-text-muted uppercase tracking-widest opacity-60">Quyền đã chọn</span>
                            <span class="text-xl font-black text-primary font-mono tracking-tighter">{{ count($selectedPermissions) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b border-white/5">
                            <span class="text-[11px] font-black text-text-muted uppercase tracking-widest opacity-60">Ước tính cấp độ</span>
                            <span class="text-[11px] font-black px-3 py-1 rounded-full bg-white/5 border border-white/5 text-text-main uppercase">
                                @if(count($selectedPermissions) > 10) High Access @elseif(count($selectedPermissions) > 0) Standard @else Not Config @endif
                            </span>
                        </div>
                        
                        <div class="pt-10 flex flex-col gap-3">
                            <x-backend.ui.button type="submit" variant="primary" icon="ti ti-check" class="w-full !rounded-2xl h-14 !text-sm font-black uppercase tracking-[0.1em] shadow-xl shadow-primary/20" wire:loading.attr="disabled">
                                <span wire:loading.remove>Tạo vai trò mới</span>
                                <span wire:loading>Đang khởi tạo...</span>
                            </x-backend.ui.button>
                            <x-backend.ui.button variant="neutral" :href="route('backend.users.roles')" class="w-full !rounded-2xl h-12 !bg-transparent border-white/5 hover:!bg-white/5 !text-xs opacity-60 hover:opacity-100">
                                Hủy thiết lập
                            </x-backend.ui.button>
                        </div>
                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="rounded-[2.5rem] border border-white/5 bg-white/[0.02] p-8">
                    <h4 class="text-[10px] font-black text-text-muted uppercase tracking-[0.3em] mb-4 flex items-center gap-2 italic">
                        <i class="ti ti-bulb text-warning"></i>
                        Hướng dẫn cấu hình
                    </h4>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <i class="ti ti-circle-1 text-primary text-sm mt-0.5 font-bold"></i>
                            <p class="text-[11px] text-text-muted/70 leading-relaxed font-medium">Sử dụng tên ngắn gọn, có nghĩa (ví dụ: 'sale-manager', 'content-editor').</p>
                        </li>
                        <li class="flex gap-3">
                            <i class="ti ti-circle-2 text-primary text-sm mt-0.5 font-bold"></i>
                            <p class="text-[11px] text-text-muted/70 leading-relaxed font-medium">Chỉ nên gán những quyền thực sự cần thiết để đảm bảo tính bảo mật hệ thống.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>



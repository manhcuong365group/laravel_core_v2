<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <x-backend.ui.button variant="neutral" :href="route('backend.contacts.index')" icon="ti ti-arrow-left" size="sm">
                Quay lại
            </x-backend.ui.button>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight">Chi tiết liên hệ #{{ $contact->id }}</h1>
                <p class="text-sm text-text-muted mt-1">Gửi lúc {{ $contact->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-backend.ui.button variant="danger" wire:click="deleteContact" icon="ti ti-trash" onclick="confirm('Bạn có chắc chắn muốn xóa liên hệ này?') || event.stopImmediatePropagation()">
                Xóa liên hệ
            </x-backend.ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <x-backend.layout.card class="p-6">
                <h2 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                    <i class="ti ti-mail text-primary"></i> Nội dung liên hệ
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider">Tiêu đề</label>
                        <p class="text-text-main font-bold text-lg">{{ $contact->subject }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs font-black text-text-muted uppercase tracking-wider">Tin nhắn</label>
                        <div class="mt-2 p-4 rounded-xl bg-bg-surface border border-border-glass text-text-main whitespace-pre-wrap leading-relaxed">
                            {{ $contact->message }}
                        </div>
                    </div>
                </div>
            </x-backend.layout.card>

            <x-backend.layout.card class="p-6">
                <h2 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                    <i class="ti ti-notes text-primary"></i> Ghi chú nội bộ
                </h2>
                
                <form wire:submit="updateNotes" class="space-y-4">
                    <textarea 
                        wire:model="admin_notes" 
                        rows="5" 
                        placeholder="Nhập ghi chú về liên hệ này (chỉ quản trị viên thấy)..."
                        class="w-full px-4 py-3 rounded-xl border border-border-glass bg-bg-surface text-text-main placeholder-text-muted/50 focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all"
                    ></textarea>
                    
                    <div class="flex justify-end">
                        <x-backend.ui.button type="submit" variant="primary" icon="ti ti-device-floppy">
                            Lưu ghi chú
                        </x-backend.ui.button>
                    </div>
                </form>
            </x-backend.layout.card>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <x-backend.layout.card class="p-6">
                <h2 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                    <i class="ti ti-user text-primary"></i> Thông tin người gửi
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <i class="ti ti-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-muted uppercase font-black tracking-wider">Họ tên</p>
                            <p class="text-text-main font-bold">{{ $contact->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <i class="ti ti-mail text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-muted uppercase font-black tracking-wider">Email</p>
                            <a href="mailto:{{ $contact->email }}" class="text-primary hover:underline break-all font-bold">
                                {{ $contact->email }}
                            </a>
                        </div>
                    </div>

                    @if($contact->phone)
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <i class="ti ti-phone text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-muted uppercase font-black tracking-wider">Số điện thoại</p>
                            <p class="text-text-main font-bold">{{ $contact->phone }}</p>
                        </div>
                    @endif
                </div>
            </x-backend.layout.card>

            <x-backend.layout.card class="p-6">
                <h2 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                    <i class="ti ti-settings text-primary"></i> Trạng thái
                </h2>
                
                <div class="space-y-4">
                    @foreach($statuses as $key => $label)
                        <button 
                            wire:click="updateStatus('{{ $key }}')"
                            @class([
                                'w-full flex items-center justify-between px-4 py-3 rounded-xl border transition-all',
                                'bg-primary/10 border-primary text-primary font-bold shadow-sm' => $status === $key,
                                'bg-bg-surface border-border-glass text-text-muted hover:border-primary/50' => $status !== $key,
                            ])
                        >
                            <span>{{ $label }}</span>
                            @if($status === $key)
                                <i class="ti ti-check"></i>
                            @endif
                        </button>
                    @endforeach
                </div>
            </x-backend.layout.card>
        </div>
    </div>
</div>


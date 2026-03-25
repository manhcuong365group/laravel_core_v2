<div class="space-y-6">
    @include('backend.layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Hệ thống'],
            ['label' => 'Hoạt động gần đây'],
        ],
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">Hoạt động gần đây</h1>
            <p class="mt-1 text-sm text-text-muted">Theo dõi các hoạt động của người dùng trên hệ thống.</p>
        </div>
        <x-backend.ui.button type="secondary" wire:click="clearFilters">
            <i class="ti ti-refresh mr-1"></i> Làm mới bộ lọc
        </x-backend.ui.button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-white/5 border border-border-glass rounded-2xl">
        <div>
            <label class="block text-[11px] font-bold text-text-muted uppercase tracking-widest mb-1.5">Người dùng</label>
            <select wire:model.live="user_id" class="w-full bg-white/5 border border-border-glass rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                <option value="">Tất cả người dùng</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-text-muted uppercase tracking-widest mb-1.5">Hành động</label>
            <input type="text" wire:model.live.debounce.300ms="action" placeholder="Tìm hành động..." class="w-full bg-white/5 border border-border-glass rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-text-muted uppercase tracking-widest mb-1.5">Từ ngày</label>
            <input type="date" wire:model.live="date_from" class="w-full bg-white/5 border border-border-glass rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-text-muted uppercase tracking-widest mb-1.5">Đến ngày</label>
            <input type="date" wire:model.live="date_to" class="w-full bg-white/5 border border-border-glass rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
        </div>
    </div>

    <div class="bg-white/5 border border-border-glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-white/5 text-text-muted uppercase text-[11px] font-bold tracking-widest">
                        <th class="px-6 py-4">Thời gian</th>
                        <th class="px-6 py-4">Người dùng</th>
                        <th class="px-6 py-4">Hành động</th>
                        <th class="px-6 py-4">Đối tượng</th>
                        <th class="px-6 py-4 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-glass">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-text-muted">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs mr-3">
                                        {{ substr($log->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-text-main">{{ $log->user?->name ?? 'Hệ thống' }}</div>
                                        <div class="text-[10px] text-text-muted">{{ $log->user?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[11px] font-bold bg-white/10 text-text-main border border-border-glass">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->subject_type)
                                    <div class="text-xs text-text-main">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </div>
                                @else
                                    <span class="text-text-muted">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-xs text-text-muted">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-text-muted">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-activity text-4xl mb-2 opacity-20"></i>
                                    <p>Không tìm thấy hoạt động nào.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="p-6 border-t border-border-glass">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>


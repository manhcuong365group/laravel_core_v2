<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-text-main tracking-tight">{{ $title }}</h1>
            <p class="text-sm text-text-muted mt-1">Cập nhật thông tin đơn hàng #{{ $order->code }}.</p>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.orders.index')" icon="ti ti-arrow-left">
            Quay lại
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin khách hàng -->
            <x-backend.layout.card title="Thông tin khách hàng" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-backend.forms.input wire:model="customer_name" name="customer_name" label="Tên khách hàng" required placeholder="Nhập tên khách hàng..." />
                    <x-backend.forms.input wire:model="customer_phone" name="customer_phone" label="Số điện thoại" required placeholder="Nhập số điện thoại..." />
                    <x-backend.forms.input wire:model="customer_email" name="customer_email" label="Email" type="email" placeholder="customer@example.com" />
                    <x-backend.forms.textarea wire:model="customer_address" name="customer_address" label="Địa chỉ" rows="2" placeholder="Nhập địa chỉ giao hàng..." />
                </div>
            </x-backend.layout.card>

            <!-- Sản phẩm trong đơn hàng -->
            <x-backend.layout.card title="Sản phẩm trong đơn hàng" class="p-6">
                <div class="overflow-x-auto -mx-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-glass">
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest">Sản phẩm</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-24">Số lượng</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-32">Giá</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-32 text-right">Thành tiền</th>
                                <th class="px-6 py-3 text-[11px] font-black text-text-muted uppercase tracking-widest w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-glass/50">
                            @foreach($items as $index => $item)
                                <tr wire:key="item-{{ $index }}">
                                    <td class="px-6 py-4">
                                        <select 
                                            wire:change="updateProduct({{ $index }}, $event.target.value)"
                                            class="w-full px-3 py-2 rounded-lg border border-border-glass bg-bg-surface text-sm focus:ring-2 focus:ring-primary/50 outline-none transition-all"
                                        >
                                            <option value="">-- Chọn sản phẩm --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" @selected($item['product_id'] == $product->id)>
                                                    {{ $product->name }} ({{ $product->sku }}) - {{ number_format($product->price, 0, ',', '.') }}đ
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("items.{$index}.product_name_snapshot") <p class="text-xs text-danger mt-1">Vui lòng chọn sản phẩm</p> @enderror
                                    </td>
                                    <td class="px-6 py-4">
                                        <input 
                                            type="number" 
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)" 
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="w-full px-3 py-2 rounded-lg border border-border-glass bg-bg-surface text-sm focus:ring-2 focus:ring-primary/50 outline-none transition-all"
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-text-main">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-text-main text-right">
                                        {{ number_format($item['line_total'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" wire:click="removeItem({{ $index }})" class="text-danger hover:text-danger-hover transition-colors">
                                            <i class="ti ti-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-bg-surface/30">
                                <td colspan="5" class="px-6 py-4">
                                    <x-backend.ui.button type="button" variant="neutral" size="sm" wire:click="addItem" icon="ti ti-plus">
                                        Thêm sản phẩm
                                    </x-backend.ui.button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-backend.layout.card>

            <!-- Ghi chú -->
            <x-backend.layout.card title="Ghi chú" class="p-6">
                <div class="space-y-4">
                    <x-backend.forms.textarea wire:model="notes" name="notes" label="Ghi chú của khách hàng" rows="3" placeholder="Ghi chú từ khách hàng..." />
                    <x-backend.forms.textarea wire:model="admin_notes" name="admin_notes" label="Ghi chú nội bộ" rows="3" placeholder="Ghi chú dành cho quản trị viên..." />
                </div>
            </x-backend.layout.card>
        </div>

        <div class="space-y-6 lg:sticky lg:top-24 self-start">
            <!-- Tóm tắt đơn hàng -->
            <x-backend.layout.card title="Tóm tắt đơn hàng" class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-text-muted">Tạm tính:</span>
                        <span class="font-bold text-text-main">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-black text-text-muted uppercase tracking-widest">Phí vận chuyển</label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="shipping_fee" 
                                class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-border-glass bg-bg-surface text-text-main focus:ring-2 focus:ring-primary/50 outline-none transition-all"
                            >
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted text-sm font-bold">đ</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-border-glass flex justify-between items-center">
                        <span class="text-base font-black text-text-main uppercase tracking-tight">Tổng cộng:</span>
                        <span class="text-xl font-black text-primary">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </x-backend.layout.card>

            <!-- Trạng thái -->
            <x-backend.layout.card title="Trạng thái & Thao tác" class="p-6">
                <div class="space-y-4">
                    <x-backend.forms.select wire:model="status" name="status" label="Trạng thái đơn hàng" :options="$statuses" required />
                    
                    <div class="flex flex-col gap-2 pt-2">
                        <x-backend.ui.button type="submit" variant="primary" size="lg" class="w-full shadow-lg shadow-primary/20">
                            <i class="ti ti-device-floppy mr-1.5"></i>
                            Cập nhật đơn hàng
                        </x-backend.ui.button>
                        <x-backend.ui.button variant="neutral" :href="route('backend.orders.index')" class="w-full">
                            Hủy bỏ
                        </x-backend.ui.button>
                    </div>
                </div>
            </x-backend.layout.card>
        </div>
    </form>
</div>


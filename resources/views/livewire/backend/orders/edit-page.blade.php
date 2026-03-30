<div class="space-y-8 pb-32">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 text-primary shadow-inner">
                 <i class="ti ti-shopping-cart text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-text-main tracking-tight uppercase tracking-[0.1em]">Sửa đơn hàng</h1>
                <p class="text-[11px] font-black text-text-muted mt-1 uppercase tracking-widest opacity-60">Cập nhật thông tin đơn hàng <b>#{{ $order->code }}</b>.</p>
            </div>
        </div>
        <x-backend.ui.button variant="neutral" :href="route('backend.orders.index')" icon="ti ti-arrow-left" class="rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white/5 border-white/10 hover:bg-white/10">
            Quay lại danh sách
        </x-backend.ui.button>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Main Column (8cols) -->
        <div class="xl:col-span-8 space-y-8">
            <!-- Customer Info -->
            <x-admin.form-section title="Thông tin khách hàng" icon="ti-user-circle" color="blue">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-backend.forms.input wire:model="customer_name" name="customer_name" label="Tên khách hàng" required placeholder="Nhập tên khách hàng..." />
                    <x-backend.forms.input wire:model="customer_phone" name="customer_phone" label="Số điện thoại" required placeholder="Nhập số điện thoại..." />
                    <div class="md:col-span-2">
                        <x-backend.forms.input wire:model="customer_email" name="customer_email" label="Địa chỉ Email" type="email" placeholder="customer@example.com" />
                    </div>
                    <div class="md:col-span-2">
                        <x-backend.forms.textarea wire:model="customer_address" name="customer_address" label="Địa chỉ giao hàng" rows="2" placeholder="Số nhà, tên đường, phường/xã..." />
                    </div>
                </div>
            </x-admin.form-section>

            <!-- Order Items -->
            <x-admin.form-section title="Chi tiết sản phẩm" icon="ti-list-details" color="purple" padding="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-widest">Sản phẩm</th>
                                <th class="px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-widest w-24 text-center">Số lượng</th>
                                <th class="px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-widest w-32">Đơn giá</th>
                                <th class="px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-widest w-32 text-right">Tổng</th>
                                <th class="px-6 py-4 text-[11px] font-black text-text-muted uppercase tracking-widest w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($items as $index => $item)
                                <tr wire:key="item-{{ $index }}" class="group hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <select 
                                            wire:change="updateProduct({{ $index }}, $event.target.value)"
                                            class="w-full px-3 py-2 rounded-xl border border-white/10 bg-bg-surface text-sm focus:ring-2 focus:ring-primary/50 outline-none transition-all"
                                        >
                                            <option value="">-- Chọn sản phẩm --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" @selected($item['product_id'] == $product->id)>
                                                    {{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input 
                                            type="number" 
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)" 
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="w-full px-3 py-2 rounded-xl border border-white/10 bg-bg-surface text-center text-sm focus:ring-2 focus:ring-primary/50 outline-none transition-all"
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-text-main">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-text-main text-right">
                                        {{ number_format($item['line_total'], 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" wire:click="removeItem({{ $index }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-danger hover:bg-danger/10 transition-colors">
                                            <i class="ti ti-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="px-6 py-4">
                                    <button type="button" wire:click="addItem" class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary hover:text-white transition-colors">
                                        <i class="ti ti-plus text-base"></i> Thêm sản phẩm mới
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-admin.form-section>

            <!-- Notes -->
            <x-admin.form-section title="Ghi chú" icon="ti-notes" color="orange">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-backend.forms.textarea wire:model="notes" name="notes" label="Ghi chú khách hàng" rows="3" placeholder="Yêu cầu đặc biệt từ khách..." />
                    <x-backend.forms.textarea wire:model="admin_notes" name="admin_notes" label="Ghi chú nội bộ" rows="3" placeholder="Chỉ quản trị viên mới thấy..." />
                </div>
            </x-admin.form-section>
        </div>

        <!-- Sidebar Column (4cols) -->
        <div class="xl:col-span-4 space-y-8 xl:sticky xl:top-24">
            <!-- Order Summary -->
            <x-admin.form-section title="Tóm tắt đơn hàng" icon="ti-calculator" color="emerald">
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-text-muted">Tổng tiền hàng:</span>
                        <span class="font-bold text-text-main">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-text-muted uppercase tracking-widest opacity-60">Phí vận chuyển (VNĐ)</label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="shipping_fee" 
                                class="w-full pl-4 pr-10 py-3 rounded-2xl border border-white/10 bg-white/5 text-text-main font-bold focus:ring-2 focus:ring-primary/50 outline-none transition-all shadow-inner"
                            >
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted text-xs font-bold uppercase">đ</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/5 space-y-1">
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-black text-text-muted uppercase tracking-widest">Tổng thanh toán</span>
                            <span class="text-2xl font-black text-primary tracking-tighter">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </x-admin.form-section>

            <!-- Status -->
            <x-admin.form-section title="Trạng thái" icon="ti-settings" color="orange">
                <x-backend.forms.select wire:model="status" name="status" label="Trạng thái đơn hàng" :options="$statuses" required />
            </x-admin.form-section>
        </div>

        <!-- Sticky Action Bar -->
        <x-admin.sticky-bar
            cancelHref="{{ route('backend.orders.index') }}"
            target="save"
            saveLabel="Cập nhật đơn hàng"
            mode="Order #{{ $order->code }}"
        >
            <x-slot:info>
                <span class="text-[13px] font-bold text-text-main">Khách: {{ $customer_name }}</span>
            </x-slot:info>
        </x-admin.sticky-bar>
    </form>
</div>

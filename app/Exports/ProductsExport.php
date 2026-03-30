<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with(['category', 'brand'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên sản phẩm',
            'SKU',
            'Giá niêm yết',
            'Giá khuyến mãi',
            'Số lượng kho',
            'Trạng thái kho',
            'Danh mục',
            'Thương hiệu',
            'Trạng thái (1: Hiện, 0: Ẩn)',
            'Nổi bật (1: Có, 0: Không)',
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->price,
            $product->sale_price,
            $product->stock_quantity,
            $product->stock_status,
            $product->category?->name,
            $product->brand?->name,
            $product->is_active ? 1 : 0,
            $product->is_featured ? 1 : 0,
        ];
    }
}

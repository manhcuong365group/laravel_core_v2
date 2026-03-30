<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find category by name or ID if possible, else use a default or null
        $categoryId = null;
        if (!empty($row['danh_muc'])) {
            $category = Category::where('name', $row['danh_muc'])->first();
            $categoryId = $category?->id;
        }

        $brandId = null;
        if (!empty($row['thuong_hieu'])) {
            $brand = Brand::where('name', $row['thuong_hieu'])->first();
            $brandId = $brand?->id;
        }

        // We use updateOrCreate based on SKU to avoid duplicates
        return Product::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'name' => $row['ten_san_pham'],
                'price' => $row['gia_niem_yet'],
                'sale_price' => $row['gia_khuyen_mai'],
                'stock_quantity' => $row['so_luong_kho'],
                'stock_status' => $row['trang_thai_kho'] ?? 'in_stock',
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'is_active' => (bool) $row['trang_thai_1_hien_0_an'],
                'is_featured' => (bool) $row['noi_bat_1_co_0_khong'],
                'slug' => Str::slug($row['ten_san_pham']),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'ten_san_pham' => 'required|string',
            'sku' => 'required|string',
            'gia_niem_yet' => 'required|numeric',
            'gia_khuyen_mai' => 'nullable|numeric',
            'so_luong_kho' => 'required|integer',
        ];
    }
}

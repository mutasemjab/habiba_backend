<?php
namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::all([
            'id',
            'product_name',
            'ar_product_name',
            'barcode',
            'product_description',
            'price',
            'category_id',
            'sub_category_id',
            'brand_id',
            'product_unit',
            'product_status',
            'image',
            'gallary',
            'created_at',
            'updated_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Arabic Product Name',
            'Barcode',
            'Description',
            'Price',
            'Category ID',
            'Subcategory ID',
            'Brand ID',
            'Unit',
            'Status',
            'Image',
            'Gallery',
            'Created At',
            'Updated At',
        ];
    }
}

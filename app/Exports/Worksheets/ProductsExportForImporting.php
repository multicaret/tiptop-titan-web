<?php

namespace App\Exports\Worksheets;

use App\Exports\WorkSheetExport;
use App\Models\Branch;
use App\Models\Product;

class ProductsExportForImporting extends WorkSheetExport
{
    protected Branch $branch;

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function collection()
    {
        return Product::where('branch_id', $this->branch->id)->get();
    }

    public function map($product): array
    {
        return [
            $product->excel_id,
            $product->id,
            $product->category_id,
            $product->category_ids,
            ...$product->translations->pluck('title')->toArray(),
            ...$product->translations->pluck('description')->toArray(),
            ...$product->translations->pluck('excerpt')->toArray(),
            $product->price,
            $product->price_discount_amount,
            $product->price_discount_by_percentage_text,
            $product->price_discount_began_at,
            $product->price_discount_finished_at,
            $product->width,
            $product->height,
            $product->depth,
            $product->weight,
            $product->type,
            $product->minimum_orderable_quantity,
            $product->maximum_orderable_quantity,
            $product->status,
            $product->is_storage_tracking_enabled_text,
            $product->available_quantity,
        ];
    }

    public function headings(): array
    {
        return [
            'Excel ID',
            'Product ID',
            'Menu Category ID',
            'Grocery Categories',
            'Arabic title',
            'English title',
            'Kurdish title',
            'Arabic description',
            'English description',
            'Kurdish description',
            'Arabic excerpt',
            'Englsih excerpt',
            'Kurdish excerpt',
            'Price',
            'Price discount amount',
            'Price discount by percentage',
            'Price discount began at',
            'Price discount finished at',
            'width',
            'height',
            'depth',
            'weight',
            'Product Type',
            'Minimum orderable quantity',
            'Maximum orderable quantity',
            'Product status',
            'Is storage tracking enabled?',
            'Available quantity',
        ];
    }

    public function prepareRows($rows): array
    {
        $closure = function (Product $product) {
            $product->excel_id = $product->importer_id;
            $product->type = \Str::beforeLast(Product::getChannelsArray()[$product->type], '-');
            $product->status = Product::getAllStatusesRich()[$product->status]['title'];
            $product->price_discount_amount = $product->price_discount_amount ?? 0;
            $product->price_discount_by_percentage_text = $product->price_discount_by_percentage || is_null($product->price_discount_by_percentage) ? 'yes' : 'no';
            $product->minimum_orderable_quantity = $product->minimum_orderable_quantity ?? 1;
            $storageTracking = $product->is_storage_tracking_enabled || ! is_null($product->is_storage_tracking_enabled) ? 'yes' : 'no';
            $product->is_storage_tracking_enabled_text = $storageTracking;

            $categories = $product->categories()->pluck('taxonomies.id');
            $product->category_ids = $categories ? implode(',', $categories->toArray()) : null;

            return $product;
        };

        return array_map($closure, $rows);
    }

    public function title(): string
    {
        return 'products';
    }
}

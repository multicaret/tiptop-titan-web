<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExportGeneral implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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
        $columnOne = [
            $product->id,
            $product->chain->title,
            $product->branch->title
        ];
        $columnTwo = [
            optional($product->category)->title,
            optional($product->unit)->title,
            $product->price,
            $product->price_discount_amount,
            $product->price_discount_by_percentage_string,
            $product->price_discount_began_at,
            $product->price_discount_finished_at,
            $product->available_quantity,
            $product->width,
            $product->height,
            $product->weight,
            $product->type,
            $product->minimum_orderable_quantity,
            $product->maximum_orderable_quantity,
            $product->order_column,
            $product->status,
            $product->custom_banner_began_at,
            $product->custom_banner_ended_at,
            $product->is_storage_tracking_enabled_string,
            $product->created_at->format(config('defaults.date.normal_format')),
            $product->updated_at->format(config('defaults.date.normal_format')),
        ];

        return array_merge($columnOne,
            $product->translations->pluck('title')->toArray(),
            $product->translations->pluck('description')->toArray(),
            $product->translations->pluck('excerpt')->toArray(),
            $product->translations->pluck('notes')->toArray(),
            $product->translations->pluck('custom_banner_text')->toArray(),
            $product->translations->pluck('unit_text')->toArray(),
            $columnTwo);
    }

    public function headings(): array
    {
        return [
            '#',
            'Chain',
            'Branch',
            'Arabic Title',
            'English Title',
            'Kurdish Title',
            'Arabic Description',
            'English Description',
            'Kurdish Description',
            'Arabic Excerpt',
            'English Excerpt',
            'Kurdish Excerpt',
            'Arabic Notes',
            'English Notes',
            'Kurdish Notes',
            'Arabic Custom banner text',
            'English Custom banner text',
            'Kurdish Custom banner text',
            'Arabic Unit text',
            'English Unit text',
            'Kurdish Unit text',
            'Category',
            'Unit',
            'Price',
            'Price discount amount',
            'Price discount by percentage',
            'Price discount began at',
            'Price discount finished at',
            'Available quantity',
            'Width',
            'Height',
            'Weight',
            'Type',
            'Minimum orderable quantity',
            'Maximum orderable quantity',
            'Order column',
            'Status',
            'Custom banner began at',
            'Custom banner ended at',
            'Is storage tracking enabled?',
            'Created at',
            'Updated at',
        ];
    }

    public function prepareRows($rows): array
    {
        $closure = function ($product) {
            $product->chain->title .= " (ID: {$product->chain_id})";
            $product->branch->title .= " (ID: {$product->branch_id})";
            $product->type = \Str::beforeLast(Product::getChannelsArray()[$product->type], '-');
            $product->status = Product::getAllStatusesRich()[$product->status]['title'];
            $product->is_storage_tracking_enabled_string = $product->is_storage_tracking_enabled ? 'Yes' : 'No';
            $product->price_discount_by_percentage_string = $product->price_discount_by_percentage ? 'Yes' : 'No';
            if ( ! is_null($product->category)) {
                $product->category->title .= " (ID: {$product->category_id})";
            }
            if ( ! is_null($product->unit)) {
                $product->unit->title .= " (ID: {$product->unit_id})";
            }
            return $product;
        };

        return array_map($closure, $rows);
    }
}

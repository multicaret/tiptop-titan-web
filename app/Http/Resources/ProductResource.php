<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'price_discount_by_percentage' => $this->price_discount_by_percentage,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'upc' => $this->upc,
            'is_storage_tracking_enabled' => $this->is_storage_tracking_enabled,
            'minimum_orderable_quantity' => $this->minimum_orderable_quantity,
            'order_column' => $this->order_column,
            'avg_rating' => $this->avg_rating,
            'rating_count' => $this->rating_count,
            'view_count' => $this->view_count,
            'status' => $this->status,
            'price_discount_began_at' => $this->price_discount_began_at,
            'price_discount_finished_at' => $this->price_discount_finished_at,
            'custom_banner_began_at' => $this->custom_banner_began_at,
            'custom_banner_ended_at' => $this->custom_banner_ended_at,
            'on_mobile_grid_tile_weight' => $this->on_mobile_grid_tile_weight,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cover' => $this->cover,
            'cover_full' => $this->cover_full,
            'discounted_price_formatted' => $this->discounted_price_formatted,
            'gallery' => $this->gallery,
            'is_published' => $this->is_published,
            'price_formatted' => $this->price_formatted,
            'thumbnail' => $this->thumbnail,
            'barcodes' => $this->barcodes,
            'barcodes_count' => $this->barcodes_count,
            'branch' => $this->branch,
            'categories' => $this->categories,
            'categories_count' => $this->categories_count,
            'chain' => $this->chain,
            'creator' => $this->creator,
            'editor' => $this->editor,
            'masterCategory' => $this->masterCategory,
            'media' => $this->media,
            'media_count' => $this->media_count,
            'tags' => $this->tags,
            'tags_count' => $this->tags_count,
            'unit' => $this->unit,
            'price' => $this->price,
            'price_discount_amount' => $this->price_discount_amount,
            'width' => $this->width,
            'height' => $this->height,
            'depth' => $this->depth,
            'weight' => $this->weight,
            'status_name' => $this->status_name,
            'translations_count' => $this->translations_count,
            'useTranslationFallback' => $this->useTranslationFallback,
            'translationForeignKey' => $this->translationForeignKey,
            'localeKey' => $this->localeKey,
            'translationModel' => $this->translationModel,

            'creator_id' => $this->creator_id,
            'editor_id' => $this->editor_id,
            'chain_id' => $this->chain_id,
            'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,

            'branch' => new BranchResource($this->whenLoaded('branch')),
            'categories' => TaxonomyResource::collection($this->whenLoaded('categories')),
            'chain' => new ChainResource($this->whenLoaded('chain')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'editor' => new UserResource($this->whenLoaded('editor')),
            'masterCategory' => new TaxonomyResource($this->whenLoaded('masterCategory')),
            'tags' => TaxonomyResource::collection($this->whenLoaded('tags')),
            'unit' => new TaxonomyResource($this->whenLoaded('unit')),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'categories' => TaxonomyResource::collection($this->whenLoaded('categories')),
            'chain' => new ChainResource($this->whenLoaded('chain')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'editor' => new UserResource($this->whenLoaded('editor')),
            'masterCategory' => new TaxonomyResource($this->whenLoaded('masterCategory')),
            'tags' => TaxonomyResource::collection($this->whenLoaded('tags')),
            'unit' => new TaxonomyResource($this->whenLoaded('unit')),
        ];
    }
}

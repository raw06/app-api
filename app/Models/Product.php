<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    const PRODUCT_SEARCH_FIELDS = [
        'title',
        'tags',
        'collections_name',
        'vendor'
    ];

    protected $fillable = [
        'id',
        'shop_id',
        'title',
        'handle',
        'image',
        'shopify_id',
        'vendor',
        'product_type',
        'collections_name',
        'tags',
        'status',
        'published_scope',
        'template_suffix'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function toSearchableArray(): array
    {
        $data = array_filter($this->getAttributes(), function ($value) {
            return $value !== null;
        });

        $questionsCount = $this->questions()->count();
        if ($questionsCount) {
            $data['questions_count'] = $questionsCount;
        }
        $tagsCount = $this->tags()->count();
        if ($tagsCount) {
            $data['tags_count'] = $tagsCount;
        }

        $shopName = shopNameFromDomain($this->shop->shop);
        data_set($data, 'shop_name', $shopName);

        $collectionsName = data_get($data, 'collections_name');
        if ($collectionsName) {
            $values = collect(explode(',', $collectionsName))->filter()->map(function ($item) {
                return trim($item);
            })->values()->all();
            data_set($data, 'collections_name', $values);
        }

        $tags = data_get($data, 'tags');
        if ($tags) {
            $values = collect(explode(',', $tags))->filter()->map(function ($item) {
                return trim($item);
            })->values()->all();
            data_set($data, 'tags', $values);
        }

        $createdAt = Carbon::parse(data_get($data, 'created_at'));
        data_set($data, 'created_at', $createdAt->__toString());
        $updatedAt = Carbon::parse(data_get($data, 'updated_at'));
        data_set($data, 'updated_at', $updatedAt->__toString());
        if (data_get($data, 'deleted_at')) {
            $deletedAt = Carbon::parse(data_get($data, 'deleted_at'));
            data_set($data, 'deleted_at', $deletedAt->__toString());
        }

        return $data;
    }
}

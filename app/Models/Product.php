<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['store_id', 'category_id', 'name', 'slug', 'description', 'image', 'price', 'compare_price', 'options', 'rating', 'featured', 'status',];

    public static function booted()
    {

        static::addGlobalScope('store', function (Builder $builder) {

            $user = Auth::user();

            if ($user && $user->store_id) {

                $builder->where('store_id', '=', $user->store_id);
            }
        });
    }

    public function category()
    {

        return $this->belongsTo(Category::class)->withDefault();
    }

    public function parent()
    {

        return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault([
            'name' => 'Main Category'
        ]);
    }
    public function store()
    {

        return $this->belongsTo(Store::class)->withDefault();
    }

    public function tags() {

        return $this->belongsToMany(Tag::class);

    }

    public function scopeFilter(Builder $builder, $filters) {

        if ($filters['name'] ?? false) {

            $builder->where('name', 'LIKE', "%{$filters['name']}%");

        }

        if ($filters['status'] ?? false) {

            $builder->whereStatus($filters['status']);

        }

    }

    public function scopeActive(Builder $builder) {

        $builder->whereStatus('active');

    }

    public function getImageUrlAttribute() {

        if (!$this->image) {

            return "https://www.ehabra.com/storage/images/documents/_res/wrh/def_product.png";

        }

        elseif (Str::startsWith($this->image, ['http://', 'https://'])) {

            return $this->image;

        }

        return asset('uploads/products/' . $this->image);

    }

    public function getSalePercentAttribute() {

        if (!$this->compare_price) {

            return 0;

        }

        return round(100 - 100 * ($this->price/$this->compare_price), 1);

    }
} 

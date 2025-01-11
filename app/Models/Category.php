<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'description', 'status', 'image', 'slug'];

    public function scopeActive(Builder $builder) {

        $builder->where('status', '=', 'active');

    }

    public function scopeFilter(Builder $builder, $filters) {

        if ($name = $filters['name'] ?? false) {

            $builder->where('categories.name', 'LIKE', "%$name%");

        }

        if ($status = $filters['status'] ?? false) {

            $builder->where('categories.status', '=', $status);

        }

    }

    public function products() {

        return $this->hasMany(Product::class);

    }

    public function getImageUrlAttribute() {

        if (!$this->image) {

            return "https://www.ehabra.com/storage/images/documents/_res/wrh/def_product.png";

        }

        elseif (Str::startsWith($this->image, ['http://', 'https://'])) {

            return $this->image;

        }

        return asset('uploads/categories/' . $this->image);

    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageProduk extends Model
{
    protected $fillable = ['product_id', 'image_path'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }

    public function getImagePathAttribute($value)
    {
        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        
        return 'storage/' . $value;
    }
}

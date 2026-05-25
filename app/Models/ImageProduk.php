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
        // If the path is already a full URL, return it
        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        
        // Convert 'products/product_1.jpg' into a unique seed for Picsum
        $seed = md5($value);
        return 'https://picsum.photos/seed/' . $seed . '/400/400';
    }
}

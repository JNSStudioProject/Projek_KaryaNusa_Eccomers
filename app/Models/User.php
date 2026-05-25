<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function produkDijual()
    {
        return $this->hasMany(Produk::class, 'user_id', 'id');
    }
    public function pemesanans()
    {
        return $this->belongsToMany(Produk::class, 'pemesanan', 'user_id', 'product_id')
            ->withPivot('total_item_pesanan', 'total_biaya', 'status_pesan', 'shipping_address', 'payment_method', 'tanggal_pemesanan')
            ->withTimestamps();
    }
    // public function Review()
    // {
    //     return $this->belongsToMany(Produk::class, 'Review', 'user_id', 'product_id')
    //         ->withPivot('review', 'rating')
    //         ->withTimestamps();
    // }
}

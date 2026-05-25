<?php

use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\EmailCons;
use App\Http\Controllers\Auth\EmailVerificationController;
Route::get('/unauthorized', function () {
    return 'You are not authorized to access this page.';
});


Route::middleware(['auth'])->get('/', function () {
    return view(view: 'index');
})->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::post('/save-address', [PemesananController::class, 'saveAddress'])->name('save-address');
    Route::get('/pesanan-saya', [PemesananController::class, 'pesananSaya'])->name('pesanan.saya');
});


Route::get('/sukses', [PaymentController::class, 'getThanku'])->name('sukses');
Route::get('/aboutUs', [PemesananController::class, 'AboutUs'])->name('AboutUsCustomer');
Route::get('/ViewCO', [PemesananController::class, 'ViewCheckout'])->name('ViewCheckout');
Route::get('/kota/{provinsi_id}', [PemesananController::class, 'kota'])->name('kota');
Route::get('/checkout', function() {
    return redirect()->route('cartCustomer');
})->name('checkout.get');
Route::post('/checkout', [PemesananController::class, 'Co'])->name('checkout');
Route::post('/hitungOngkir', [PemesananController::class, 'hitungOngkir'])->name('hitungOngkir');
Route::get('/ContactUs', [PemesananController::class, 'Contact'])->name('ContactCustomer');
Route::get('/detail/{id}', [PemesananController::class, 'detail'])->name('detailProduct');
Route::get('/cartView', [PemesananController::class, 'cart'])->name('cartCustomer');
Route::get('/load-more-products', [ProdukController::class, 'loadMore'])->name('load.more');
Route::post('/cart', [PemesananController::class, 'add_chart'])->name('cart.add');
Route::delete('/delete/{id}', [PemesananController::class, 'deleteCart'])->name('cart.delete');
Route::put('/Cart/update/{id}', [PemesananController::class, 'updateCart'])->name('cart.update');
Route::post('/Payment', [PaymentController::class, 'processOrder'])->name('Payment');
Route::get('/PaymentView', [PaymentController::class, 'getViewPayment'])->name('PaymentView');
Route::get('/email', [EmailCons::class, 'index'])->name('email');
Route::get('/Sendemail', [EmailCons::class, 'sendEmail'])->name('Sendemail');
Route::post('/Order', [PaymentController::class, 'Order'])->name('Order');
Route::post('/PaymentUpdate', [PaymentController::class, 'processOrderUpdate'])->name('PaymentUpdate');
Route::resource('/AdminPage', AdminController::class);
Route::resource('/Produk', ProdukController::class);
Route::resource('/Kategori', CategoryController::class);
Route::resource('/pemesanan', PemesananController::class);
// Route::get('/gerr', [EmailCons::class, 'gerr'])->name('gerr');

Route::get('/run-migrations', function () {
    \Artisan::call('migrate:fresh --seed --force');
    return 'Migrations completed successfully on Vercel: ' . \Artisan::output();
});

Route::get('/debug-images', function () {
    $products = \App\Models\Produk::with('images')->get();
    $output = [];
    foreach ($products as $p) {
        $imgs = $p->images->map(fn($i) => [
            'raw' => $i->getRawOriginal('image_path'),
            'accessor' => $i->image_path,
        ]);
        $output[] = [
            'id' => $p->id,
            'nama' => $p->nama,
            'image_count' => $p->images->count(),
            'images' => $imgs,
        ];
    }
    return response()->json($output, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/create-admin', function () {
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'jsilitonga42@gmail.com'],
        [
            'name' => 'Jessica Silitonga',
            'no_hp' => '081234567890',
            'password' => 'password123', // Let the model cast hash it
            'email_verified_at' => now(),
            'Role' => 'admin',
        ]
    );
    
    $attempt = \Illuminate\Support\Facades\Auth::attempt([
        'email' => 'jsilitonga42@gmail.com',
        'password' => 'password123'
    ]);
    
    return 'Akun berhasil diperbarui! Email: jsilitonga42@gmail.com | Password: password123 | Test Login: ' . ($attempt ? 'BERHASIL' : 'GAGAL');
});

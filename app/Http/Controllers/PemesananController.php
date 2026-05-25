<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;


class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pemesanan'] = Pemesanan::with('user')->latest()->paginate(10);
        return view('Admin.pemesanan_index', $data);
    }

    public function AboutUs()
    {
        return view('aboutUs');
    }
    public function pesananSaya()
    {
        $pemesanan = Pemesanan::with('products')->where('user_id', Auth::id())->latest()->get();
        return view('pesananSaya', compact('pemesanan'));
    }

    public function ViewCheckout(Request $request)
    {
        $selectedItems = $request->query('selectedID');
        if (!$selectedItems) {
            return redirect()->route('cartCustomer')->with('error', 'Silakan pilih item dari keranjang.');
        }

        $selectedItemsArray = explode(',', $selectedItems);
        $cart = Cart::whereIn('id', $selectedItemsArray)->with('produk.images')->get();
        
        $total_berat = 0;
        $subTotal = 0;
        foreach ($cart as $item) {
            $berat_produk = $item->produk->berat ?? 500;
            $total_berat += ($berat_produk * $item->quantity);
            $subTotal += ($item->produk->harga * $item->quantity);
        }

        $provinsi = [];
        if (!Auth::user()->kota_id) {
            $provinsi = Cache::remember('rajaongkir_provinsi', 86400, function () {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.rajaongkir.com/starter/province',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 5,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => ['key:a83772758c55b5e7ea48b40d11380c36'],
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if (!$err) {
                    return json_decode($response, true);
                }
                return [];
            });
            
            // MOCK DATA FALLBACK JIKA RAJAONGKIR DIBLOKIR INTERNET
            if (empty($provinsi) || isset($provinsi['rajaongkir']['status']['code']) && $provinsi['rajaongkir']['status']['code'] != 200) {
                $provinsi = [
                    'rajaongkir' => [
                        'status' => ['code' => 200, 'description' => 'OK'],
                        'results' => [
                            ['province_id' => '1', 'province' => 'Bali (Data Dummy)'],
                            ['province_id' => '6', 'province' => 'DKI Jakarta (Data Dummy)'],
                            ['province_id' => '9', 'province' => 'Jawa Barat (Data Dummy)'],
                            ['province_id' => '11', 'province' => 'Jawa Timur (Data Dummy)']
                        ]
                    ]
                ];
            }
        } // Penutup untuk if (!Auth::user()->kota_id) {
            
        // Konversi array ke JSON string agar formatnya sama dengan fungsi Co() saat mensubmit payment
        $selectedItems = json_encode($selectedItemsArray);
        
        return view('checkOut', compact('cart', 'total_berat', 'subTotal', 'selectedItems', 'provinsi'));
    }
    public function bayar(Request $request) {}

    public function kota($provinsi_id)
    {
        $kota = Cache::remember("rajaongkir_city_{$provinsi_id}", 86400, function () use ($provinsi_id) {
            $curl = curl_init();
    
            // API untuk mengambil kota berdasarkan provinsi
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.rajaongkir.com/starter/city?&province=' . $provinsi_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => ['key:a83772758c55b5e7ea48b40d11380c36'],
            ]);
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
    
            if (!$err) {
                return json_decode($response, true);
            }
            return [];
        });

        // MOCK DATA FALLBACK JIKA RAJAONGKIR DIBLOKIR INTERNET
        if (empty($kota) || isset($kota['rajaongkir']['status']['code']) && $kota['rajaongkir']['status']['code'] != 200) {
            $kota = [
                'rajaongkir' => [
                    'status' => ['code' => 200, 'description' => 'OK'],
                    'results' => [
                        ['city_id' => '114', 'city_name' => 'Denpasar (Dummy)'],
                        ['city_id' => '152', 'city_name' => 'Jakarta Pusat (Dummy)'],
                        ['city_id' => '22', 'city_name' => 'Bandung (Dummy)'],
                        ['city_id' => '444', 'city_name' => 'Surabaya (Dummy)']
                    ]
                ]
            ];
        }

        if (isset($kota['rajaongkir']['status']['code']) && $kota['rajaongkir']['status']['code'] == 200) {
            $options = "<option value=''>Pilih Kota</option>";
            foreach ($kota['rajaongkir']['results'] as $kt) {
                $options .= "<option value='" . $kt['city_id'] . "'>" . $kt['city_name'] . "</option>";
            }
            return $options;
        } else {
            return "<option value=''>Gagal memuat kota dari API</option>";
        }
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required',
            'kota_id' => 'required',
            'alamat_lengkap' => 'required',
            'kode_pos' => 'required'
        ]);

        $user = Auth::user();
        $user->update([
            'provinsi_id' => $request->provinsi_id,
            'kota_id' => $request->kota_id,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kode_pos' => $request->kode_pos,
        ]);

        return response()->json(['success' => true, 'message' => 'Alamat berhasil disimpan!']);
    }

    public function hitungOngkir(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = Auth::user();
            if (!$user->kota_id) {
                return response()->json(['success' => false, 'message' => 'Lengkapi alamat pengiriman terlebih dahulu.']);
            }

            $postFields = "origin=457" .
                "&destination=" . $user->kota_id .
                "&weight=" . $request->input('berat') .
                "&courier=" . $request->input('ekspidisi');

            // Inisialisasi CURL
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 2,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: a83772758c55b5e7ea48b40d11380c36"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $ongkirData = [];
            if (!$err) {
                $ongkirData = json_decode($response, true);
            }

            // MOCK DATA FALLBACK JIKA RAJAONGKIR DIBLOKIR INTERNET
            if (empty($ongkirData) || isset($ongkirData['rajaongkir']['status']['code']) && $ongkirData['rajaongkir']['status']['code'] != 200) {
                $ongkirData = [
                    'rajaongkir' => [
                        'status' => ['code' => 200, 'description' => 'OK'],
                        'results' => [
                            [
                                'code' => $request->ekspidisi,
                                'name' => strtoupper($request->ekspidisi) . ' (Dummy)',
                                'costs' => [
                                    [
                                        'service' => 'REG',
                                        'description' => 'Layanan Reguler (Simulasi)',
                                        'cost' => [
                                            [
                                                'value' => 15000,
                                                'etd' => '2-3',
                                                'note' => ''
                                            ]
                                        ]
                                    ],
                                    [
                                        'service' => 'YES',
                                        'description' => 'Yakin Esok Sampai (Simulasi)',
                                        'cost' => [
                                            [
                                                'value' => 25000,
                                                'etd' => '1-1',
                                                'note' => ''
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $ongkirData
            ]);
        }
    }


    public function Co(Request $request)
    {
        // Mengambil data selected_items dari request
        $selectedItems = request()->input('selected_items');
        if (!$selectedItems) {
            return redirect()->route('cartCustomer')->with('error', 'Silakan pilih item dari keranjang.');
        }
        $selectedItemsArray = json_decode($selectedItems, true);
        // Memastikan data adalah array
        if (is_array($selectedItemsArray)) {
            $cart = Cart::whereIn('id', $selectedItemsArray)->with('produk.images')->get();
            
            $total_berat = 0;
            $subTotal = 0;
            foreach ($cart as $item) {
                $berat_produk = $item->produk->berat ?? 500;
                $total_berat += ($berat_produk * $item->quantity);
                $subTotal += ($item->produk->harga * $item->quantity);
            }

            // Fetch provinces if user doesn't have kota_id
            $provinsi = [];
            if (!Auth::user()->kota_id) {
                $provinsi = Cache::remember('rajaongkir_provinsi', 86400, function () {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://api.rajaongkir.com/starter/province',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 5,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_HTTPHEADER => ['key:a83772758c55b5e7ea48b40d11380c36'],
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if (!$err) {
                        return json_decode($response, true);
                    }
                    return [];
                });
                
                // MOCK DATA FALLBACK JIKA RAJAONGKIR DIBLOKIR INTERNET
                if (empty($provinsi) || isset($provinsi['rajaongkir']['status']['code']) && $provinsi['rajaongkir']['status']['code'] != 200) {
                    $provinsi = [
                        'rajaongkir' => [
                            'status' => ['code' => 200, 'description' => 'OK'],
                            'results' => [
                                ['province_id' => '1', 'province' => 'Bali (Data Dummy)'],
                                ['province_id' => '6', 'province' => 'DKI Jakarta (Data Dummy)'],
                                ['province_id' => '9', 'province' => 'Jawa Barat (Data Dummy)'],
                                ['province_id' => '11', 'province' => 'Jawa Timur (Data Dummy)']
                            ]
                        ]
                    ];
                }
            }
            
            return view('checkOut', compact('cart', 'total_berat', 'subTotal', 'selectedItems', 'provinsi'));
        } else {
            // Jika data tidak valid, memberikan pesan error atau mengarahkan kembali
            return redirect()->back()->with('error', 'Item yang dipilih tidak valid.');
        }
    }

    public function updateCart(Request $request, $id_keranjang)
    {
        // Validasi data
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);
        // Cari item keranjang berdasarkan id
        $cartItem = Cart::findOrFail($id_keranjang);

        // Update jumlah item
        $cartItem->quantity = $request->input('jumlah');
        $cartItem->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('cartCustomer')->with('success', 'Item berhasil diperbarui.');
    }

    public function add_chart(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => false,
                    'message'  => 'Silakan login terlebih dahulu!',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId    = Auth::id();
        $productId = $request->input('id');
        $quantity  = (int) $request->input('quantity', 1);

        // Cek apakah produk sudah ada di keranjang milik user ini
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Tambahkan quantity jika produk sudah ada
            $cartItem->quantity += $quantity;
            $cartItem->save();
            $message = 'Jumlah produk di keranjang berhasil diperbarui.';
        } else {
            // Buat item baru di keranjang
            Cart::create([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang!';
        }

        // Hitung total item keranjang untuk badge navbar
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        // Jika AJAX → kembalikan JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => $message,
                'cart_count' => $cartCount,
            ]);
        }

        // Form submit biasa → redirect ke halaman shop
        session()->flash('success', $message);
        return redirect()->route('Produk.index');
    }

    public function detail($id)
    {
        $produk = Produk::with(['images', 'kategori'])->where('id', $id)->first();
        return view('product_detail', compact('produk'));
    }
    public function CartUpdate(Request $request)
    {
        $quantities = $request->input('quantity');

        foreach ($quantities as $cartId => $quantity) {
            $cart = Cart::find($cartId);
            if ($cart) {
                $cart->quantity = $quantity;
                $cart->save();
            }
        }
        return redirect()->route('cartCustomer')->with('success', 'Keranjang berhasil diperbarui.');
    }
    public function  Contact()
    {
        $user = Auth::user();
        return view('contact', compact('user'));
    }
    public function  cart()
    {
        $cart = Cart::with('produk.images') // Eager loading untuk relasi produk dan gambar
            ->where('user_id', Auth::id())
            ->get(); // Mengambil semua data  keranjang

        return view('cart', compact('cart'));
    }
    public function  deleteCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return redirect()->route('cartCustomer');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
    }
}

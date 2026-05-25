<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json('hallo F');
        }
        $produk = Produk::with(['images', 'kategori'])->get(); // Mengambil semua produk dan gambar terkait
        return view('shop', compact('produk'));
    }
    public function loadMore(Request $request)
    {
        if ($request->ajax()) {
            // Mengambil produk berdasarkan offset dan limit
            $produk = Produk::with('images')->skip($request->skip)->take(8)->get(); // Sesuaikan dengan jumlah produk yang ingin dimuat

            return response()->json(view('partialProduct', compact('produk'))->render());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = Cache::remember('categories_all', 86400, function () {
            return Category::all();
        });
        return view('Admin.input_produk', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required',
            'ukuran' => 'nullable|string',
            'jumlah_stok' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'berat' => 'nullable|numeric|min:0|max:100',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Membuat instance model Produk
        $produk = new Produk(); // Menggunakan model Produk, bukan ProdukController

        // Mengisi data produk dengan data yang sudah divalidasi
        $produk->fill($validated);

        // Menyimpan data produk ke database
        if ($produk->save()) {
            Cache::forget('home_latest_products');
            session()->flash('success', 'Data berhasil disimpan.');
        } else {
            session()->flash('error', 'Data tidak bisa disimpan.');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public'); // Menyimpan gambar dan mendapatkan path

                // Simpan path ke tabel image_produks
                $produk->images()->create([
                    'image_path' => $path,
                    'product_id' => $produk->id,  // Path gambar disimpan di sini
                ]);
            }
        }


        // Redirect atau kembali ke halaman sebelumnya setelah proses selesai
        return redirect()->route('Produk.create');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $data['categories'] = Cache::remember('categories_all', 86400, function () {
            return Category::all();
        });
        $data['produk'] = Produk::with(['kategori', 'images'])->where('id', $id)->first();
        if (!$data['produk']) {
            abort(404, 'Produk tidak ditemukan');
        }

        return view('Admin.edit_produk', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validasi request
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required',
            'ukuran' => 'nullable|string',
            'jumlah_stok' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Mengambil produk dari database berdasarkan id
        $produk = Produk::with('kategori', 'images')->findOrFail($id);

        // Memperbarui data produk
        $produk->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'ukuran' => $request->ukuran,
            'jumlah_stok' => $request->jumlah_stok,
            'harga' => $request->harga,
            'diskon' => $request->diskon,
        ]);
        
        Cache::forget('home_latest_products');

        // Menyimpan gambar jika ada
        if ($request->hasFile('images')) {
            // Menghapus gambar lama jika ada
            foreach ($produk->images as $image) {
                // Menghapus gambar dari storage
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }

                // Menghapus entry gambar lama dari database
                $image->delete();
            }

            // Menyimpan gambar baru
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public'); // Menyimpan gambar dan mendapatkan path

                // Membuat entry baru untuk setiap gambar
                $produk->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        // Menyimpan session flash message
        session()->flash('success', 'Data berhasil disimpan.');

        // Mengalihkan kembali ke halaman daftar produk
        return redirect('/AdminPage');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $produk)
    {
        // Mengambil produk beserta relasi kategori dan gambar
        $produk1 = Produk::with('kategori', 'images')->findOrFail($produk);

        // Jika produk terkait dengan kategori atau gambar, maka akan terhapus secara otomatis jika menggunakan cascade delete
        try {
            $produk1->delete();  // Produk dan data terkait (kategori, gambar) akan terhapus jika cascade delete sudah diatur
            Cache::forget('home_latest_products');
            session()->flash('success', 'Produk dan data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            // Menangani jika terjadi error dalam penghapusan
            session()->flash('error', 'Data produk gagal dihapus.');
        }
        return redirect('/AdminPage');
    }
}

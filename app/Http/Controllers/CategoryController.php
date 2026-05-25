<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response()->json('hallo F');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.input_Kategori');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validasi data yang dikirim dari form
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'gambar' => 'file|mimes:jpg,jpeg,png,pdf|max:2048' // Validasi file
        ]);

        // Membuat instance Kategori produk
        $kategori = new Category();

        // Mengisi data pasien dengan data yang sudah divalidasi
        $kategori->fill($validated);

        // Memeriksa apakah ada file yang diunggah
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('Thumbnail', 'public'); // Simpan file di storage/app/public/KTP
            $kategori->gambar = $path; // Simpan path file ke properti image pada model
        }


        // Menyimpan data pasien ke database
        $kategori->save();
        
        Cache::forget('categories_all');

        if ($request->wantsJson()) {
            return response()->json($kategori);
        }
        return redirect('Kategori/create')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

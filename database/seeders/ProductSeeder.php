<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ImageProduk;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Categories ─────────────────────────────────────────────
        $categoryData = [
            [
                'nama_kategori' => 'Kerajinan Tangan',
                'deskripsi'     => 'Produk kerajinan tangan asli buatan pengrajin lokal Indonesia.',
                'gambar'        => 'kerajinan.jpg',
            ],
            [
                'nama_kategori' => 'Pakaian & Aksesori',
                'deskripsi'     => 'Pakaian dan aksesori tradisional dengan motif batik dan tenun ikat.',
                'gambar'        => 'pakaian.jpg',
            ],
            [
                'nama_kategori' => 'Pertanian & Pangan',
                'deskripsi'     => 'Produk pertanian dan pangan organik langsung dari petani Indonesia.',
                'gambar'        => 'pertanian.jpg',
            ],
            [
                'nama_kategori' => 'Perhiasan & Dekorasi',
                'deskripsi'     => 'Perhiasan dan dekorasi rumah buatan tangan pengrajin lokal.',
                'gambar'        => 'perhiasan.jpg',
            ],
        ];

        foreach ($categoryData as $cat) {
            Category::firstOrCreate(['nama_kategori' => $cat['nama_kategori']], $cat);
        }

        $kerajinan = Category::where('nama_kategori', 'Kerajinan Tangan')->first();
        $pakaian   = Category::where('nama_kategori', 'Pakaian & Aksesori')->first();
        $pertanian = Category::where('nama_kategori', 'Pertanian & Pangan')->first();
        $perhiasan = Category::where('nama_kategori', 'Perhiasan & Dekorasi')->first();

        // ─── 2. Products ───────────────────────────────────────────────
        $products = [
            // Kerajinan Tangan
            [
                'kategori_id'  => $kerajinan->id,
                'nama'         => 'Anyaman Bambu Nusantara',
                'deskripsi'    => 'Kerajinan anyaman bambu asli dari pengrajin lokal Nusantara dengan teknik tradisional turun-temurun.',
                'jumlah_stok'  => 25,
                'harga'        => 85000,
                'diskon'       => 10,
                'berat'        => 300,
                'ukuran'       => 'M',
                'img_seed'     => 'bamboo123',
            ],
            [
                'kategori_id'  => $kerajinan->id,
                'nama'         => 'Tas Rotan Premium',
                'deskripsi'    => 'Tas rotan handmade premium, cocok untuk gaya hidup modern yang tetap menghargai warisan budaya lokal.',
                'jumlah_stok'  => 15,
                'harga'        => 175000,
                'diskon'       => 5,
                'berat'        => 500,
                'ukuran'       => 'L',
                'img_seed'     => 'rattan456',
            ],
            [
                'kategori_id'  => $kerajinan->id,
                'nama'         => 'Tempat Tisu Ukir Jati',
                'deskripsi'    => 'Tempat tisu kayu jati dengan ukiran motif batik Jawa yang elegan dan tahan lama.',
                'jumlah_stok'  => 40,
                'harga'        => 55000,
                'diskon'       => 0,
                'berat'        => 200,
                'ukuran'       => 'S',
                'img_seed'     => 'wood789',
            ],
            // Pakaian & Aksesori
            [
                'kategori_id'  => $pakaian->id,
                'nama'         => 'Batik Tulis Solo Premium',
                'deskripsi'    => 'Kain batik tulis asli Solo dengan motif Parang Rusak berkualitas premium, proses pewarnaan alami.',
                'jumlah_stok'  => 20,
                'harga'        => 320000,
                'diskon'       => 15,
                'berat'        => 400,
                'ukuran'       => 'XL',
                'img_seed'     => 'batik001',
            ],
            [
                'kategori_id'  => $pakaian->id,
                'nama'         => 'Tenun Ikat Flores',
                'deskripsi'    => 'Kain tenun ikat tradisional dari Flores, NTT. Setiap helai mengandung makna budaya mendalam.',
                'jumlah_stok'  => 10,
                'harga'        => 450000,
                'diskon'       => 0,
                'berat'        => 600,
                'ukuran'       => 'L',
                'img_seed'     => 'tenun002',
            ],
            // Pertanian & Pangan
            [
                'kategori_id'  => $pertanian->id,
                'nama'         => 'Kopi Arabika Gayo Aceh',
                'deskripsi'    => 'Kopi arabika single origin dari dataran tinggi Gayo, Aceh. Flavor notes: coklat, karamel, dan buah tropis.',
                'jumlah_stok'  => 100,
                'harga'        => 95000,
                'diskon'       => 0,
                'berat'        => 500,
                'ukuran'       => '250gr',
                'img_seed'     => 'coffee003',
            ],
            [
                'kategori_id'  => $pertanian->id,
                'nama'         => 'Madu Hutan Kalimantan',
                'deskripsi'    => 'Madu hutan murni dari Kalimantan, dipanen langsung dari lebah liar tanpa campuran apapun.',
                'jumlah_stok'  => 50,
                'harga'        => 125000,
                'diskon'       => 5,
                'berat'        => 600,
                'ukuran'       => '500ml',
                'img_seed'     => 'honey004',
            ],
            [
                'kategori_id'  => $pertanian->id,
                'nama'         => 'Rempah Bumbu Rendang',
                'deskripsi'    => 'Paket rempah bumbu rendang pilihan asli Minangkabau, cita rasa autentik khas Padang.',
                'jumlah_stok'  => 75,
                'harga'        => 45000,
                'diskon'       => 0,
                'berat'        => 250,
                'ukuran'       => '100gr',
                'img_seed'     => 'spice005',
            ],
            // Perhiasan & Dekorasi
            [
                'kategori_id'  => $perhiasan->id,
                'nama'         => 'Kalung Perak Filigri Sulawesi',
                'deskripsi'    => 'Kalung perak dengan teknik filigri khas Sulawesi Selatan, handcrafted oleh pengrajin perak berpengalaman.',
                'jumlah_stok'  => 8,
                'harga'        => 275000,
                'diskon'       => 0,
                'berat'        => 50,
                'ukuran'       => 'One Size',
                'img_seed'     => 'silver006',
            ],
            [
                'kategori_id'  => $perhiasan->id,
                'nama'         => 'Gelang Manik Dayak',
                'deskripsi'    => 'Gelang manik-manik motif Dayak Kalimantan, dibuat secara tradisional dengan manik kaca berkualitas tinggi.',
                'jumlah_stok'  => 30,
                'harga'        => 65000,
                'diskon'       => 10,
                'berat'        => 30,
                'ukuran'       => 'One Size',
                'img_seed'     => 'bracelet007',
            ],
            [
                'kategori_id'  => $perhiasan->id,
                'nama'         => 'Patung Ukir Balsa Bali',
                'deskripsi'    => 'Patung ukiran kayu balsa khas Bali, setiap karya merupakan mahakarya seni yang unik dan orisinil.',
                'jumlah_stok'  => 12,
                'harga'        => 195000,
                'diskon'       => 0,
                'berat'        => 400,
                'ukuran'       => '30cm',
                'img_seed'     => 'statue008',
            ],
        ];

        // ─── 3. Create storage directory ───────────────────────────────
        Storage::disk('public')->makeDirectory('products');

        // Picsum photo IDs for product images (consistent, unique)
        $picsumIds = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110];

        foreach ($products as $index => $productData) {
            $imgSeed = $productData['img_seed'];
            unset($productData['img_seed']);

            // Skip if product with same name already exists
            if (Produk::where('nama', $productData['nama'])->exists()) {
                continue;
            }

            $produk = Produk::create($productData);

            // ── Download image from picsum.photos ──
            $picsumId  = $picsumIds[$index] ?? ($index * 10 + 10);
            $imagePath = 'products/product_' . $produk->id . '.jpg';

            try {
                $ctx = stream_context_create(['http' => ['timeout' => 10]]);
                $imageContent = @file_get_contents(
                    'https://picsum.photos/id/' . $picsumId . '/400/400',
                    false,
                    $ctx
                );

                if ($imageContent !== false) {
                    Storage::disk('public')->put($imagePath, $imageContent);
                    ImageProduk::create([
                        'product_id' => $produk->id,
                        'image_path' => $imagePath,
                    ]);
                }
            } catch (\Throwable $e) {
                // Silently skip if image download fails (no internet / timeout)
                $this->command->warn("  ⚠  Could not download image for: {$produk->nama}");
            }
        }

        $this->command->info('  ✓  ProductSeeder completed: ' . Produk::count() . ' products seeded.');
    }
}

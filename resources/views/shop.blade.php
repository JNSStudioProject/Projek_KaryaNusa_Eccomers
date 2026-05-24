@extends('layoutUser.tempDasar', ['title' => 'Shop UMKM'])
@section('content')
@section('hero-title')
    Shop UMKM Indonesia
@endsection

<!-- kateogori -->
<div class="sec-banner bg0 p-t-80 p-b-50">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                <!-- Block1 -->
                <div class="block1 wrap-pic-w">
                    <img src="{{ asset('asset/image/dress2.png') }}" alt="IMG-BANNER">

                    <a href="product.html"
                        class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                        <div class="block1-txt-child1 flex-col-l">
                            <span class="block1-name ltext-102 trans-04 p-b-8">
                                Pakaian dan Aksesori
                            </span>



                        </div>

                        <div class="block1-txt-child2 p-b-4 trans-05">
                            <div class="block1-link stext-101 cl0 trans-09">
                                Shop Now
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                <!-- Block1 -->
                <div class="block1 wrap-pic-w">
                    <img src="{{ asset('asset/image/kerajinan2.png') }}" alt="IMG-BANNER">

                    <a href="product.html"
                        class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                        <div class="block1-txt-child1 flex-col-l">
                            <span class="block1-name ltext-102 trans-04 p-b-8">
                                Karya Kerajinan Asli
                            </span>



                        </div>

                        <div class="block1-txt-child2 p-b-4 trans-05">
                            <div class="block1-link stext-101 cl0 trans-09">
                                Shop Now
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                <!-- Block1 -->
                <div class="block1 wrap-pic-w">
                    <img src="{{ asset('asset/image/tani.png') }}" alt="IMG-BANNER">

                    <a href="product.html"
                        class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                        <div class="block1-txt-child1 flex-col-l">
                            <span class="block1-name ltext-102 trans-04 p-b-8">
                                Pertanian
                            </span>


                        </div>

                        <div class="block1-txt-child2 p-b-4 trans-05">
                            <div class="block1-link stext-101 cl0 trans-09">
                                Shop Now
                            </div>
                        </div>
                    </a>
                </div>
            </div>


        </div>
    </div>


    <!-- Product Section -->
    <div class="bg0 m-t-23 p-b-140">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

            <!-- Section Header -->
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 2px solid #f0f0f0;">
                <div>
                    <h3 class="ltext-103 cl5 mb-1" style="font-size: 1.6rem; font-weight: 700;">Tampilan Produk</h3>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Temukan produk UMKM terbaik Indonesia</p>
                </div>
                <span class="badge bg-dark px-3 py-2" style="border-radius: 20px; font-size: 0.8rem;">
                    {{ $produk->count() }} Produk
                </span>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                style="border-radius: 12px; border: none; background: linear-gradient(135deg, #d4edda, #c3e6cb); box-shadow: 0 4px 15px rgba(40,167,69,0.2);">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Filter Tabs -->
            <div class="d-flex flex-wrap gap-2 mb-4" id="filter-tabs">
                <button class="filter-btn active" data-filter="all"
                    style="padding: 8px 20px; border: 2px solid #333; background: #333; color: white; border-radius: 25px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    Semua
                </button>
                @foreach($produk->groupBy('kategori_id') as $kategoriId => $items)
                    @if($items->first()->kategori)
                    <button class="filter-btn" data-filter="{{ $kategoriId }}"
                        style="padding: 8px 20px; border: 2px solid #ddd; background: white; color: #555; border-radius: 25px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                        {{ $items->first()->kategori->nama_kategori }}
                    </button>
                    @endif
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="row g-4" id="product-grid">
                @forelse ($produk as $p)
                @php
                    $image  = $p->images->first();
                    $harga  = $p->harga;
                    $diskon = $p->diskon ?? 0;
                    $hargaCoret = $diskon > 0 ? $harga : null;
                    $hargaFinal = $diskon > 0 ? $harga * (1 - $diskon / 100) : $harga;
                @endphp
                <div class="col-6 col-md-4 col-lg-3 product-card-wrapper" data-kategori="{{ $p->kategori_id }}">
                    <div class="product-card" style="
                        background: white;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                        position: relative;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                    ">
                        <!-- Discount Badge -->
                        @if($diskon > 0)
                        <div style="
                            position: absolute; top: 12px; left: 12px; z-index: 10;
                            background: linear-gradient(135deg, #ff4757, #ff6b81);
                            color: white; font-size: 0.72rem; font-weight: 700;
                            padding: 4px 10px; border-radius: 20px;
                            box-shadow: 0 2px 8px rgba(255,71,87,0.4);
                        ">-{{ $diskon }}%</div>
                        @endif

                        <!-- Stock Badge -->
                        @if($p->jumlah_stok <= 5)
                        <div style="
                            position: absolute; top: 12px; right: 12px; z-index: 10;
                            background: linear-gradient(135deg, #ffa502, #ff6348);
                            color: white; font-size: 0.7rem; font-weight: 600;
                            padding: 4px 10px; border-radius: 20px;
                        ">Stok Terbatas</div>
                        @endif

                        <!-- Product Image -->
                        <div class="product-img-wrap" style="position: relative; overflow: hidden; background: #f8f8f8; height: 220px;">
                            @if($image)
                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $p->nama }}"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                                class="product-img"
                                onerror="this.src='https://via.placeholder.com/400x400/f0f0f0/999?text=No+Image'"
                            >
                            @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #f5f7fa, #c3cfe2);">
                                <i class="fa fa-image" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                            @endif

                            <!-- Hover Overlay -->
                            <div class="product-overlay" style="
                                position: absolute; inset: 0;
                                background: rgba(0,0,0,0.45);
                                display: flex; align-items: center; justify-content: center; gap: 10px;
                                opacity: 0; transition: opacity 0.3s ease;
                            ">
                                <a href="{{ route('detailProduct', $p->id) }}"
                                    style="width: 42px; height: 42px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; text-decoration: none; transition: transform 0.2s;"
                                    title="Lihat Detail">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button
                                    onclick="addToCart({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                    style="width: 42px; height: 42px; background: #333; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; transition: transform 0.2s; transition: background 0.2s;"
                                    title="Tambah ke Keranjang"
                                    id="cart-btn-{{ $p->id }}"
                                >
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div style="padding: 14px 16px 16px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <!-- Category -->
                                @if($p->kategori)
                                <span style="
                                    font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
                                    color: #888; display: block; margin-bottom: 4px;
                                ">{{ $p->kategori->nama_kategori }}</span>
                                @endif

                                <!-- Name -->
                                <a href="{{ route('detailProduct', $p->id) }}"
                                    style="font-size: 0.9rem; font-weight: 700; color: #222; text-decoration: none; line-height: 1.35; display: block; margin-bottom: 8px;"
                                    class="product-name-link">
                                    {{ Str::limit($p->nama, 40) }}
                                </a>

                                <!-- Price -->
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span style="font-size: 1rem; font-weight: 800; color: #2d3436;">
                                        Rp {{ number_format($hargaFinal, 0, ',', '.') }}
                                    </span>
                                    @if($hargaCoret)
                                    <span style="font-size: 0.78rem; color: #aaa; text-decoration: line-through;">
                                        Rp {{ number_format($hargaCoret, 0, ',', '.') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Add to Cart Button (bottom of card) -->
                            <button
                                onclick="addToCart({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                class="btn-add-cart-main"
                                style="
                                    margin-top: 12px; width: 100%;
                                    padding: 10px; border: 2px solid #333;
                                    background: transparent; color: #333;
                                    border-radius: 10px; font-size: 0.82rem; font-weight: 700;
                                    cursor: pointer; transition: all 0.25s;
                                    display: flex; align-items: center; justify-content: center; gap: 8px;
                                "
                                id="main-cart-btn-{{ $p->id }}"
                                @if($p->jumlah_stok == 0) disabled style="opacity:0.5; cursor:not-allowed;" @endif
                            >
                                <i class="fa fa-shopping-cart fa-sm"></i>
                                {{ $p->jumlah_stok == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div style="padding: 60px 20px;">
                        <i class="fa fa-box-open" style="font-size: 4rem; color: #ddd; margin-bottom: 20px; display: block;"></i>
                        <h5 style="color: #999; font-weight: 600;">Belum ada produk tersedia</h5>
                        <p style="color: #bbb; font-size: 0.9rem;">Silakan tambahkan produk melalui halaman admin.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Load More -->
            <div class="d-flex justify-content-center mt-5">
                <button class="load-more-btn"
                    style="
                        padding: 14px 45px; background: #333; color: white;
                        border: none; border-radius: 30px; font-size: 0.9rem; font-weight: 700;
                        cursor: pointer; transition: all 0.3s;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                    ">
                    <i class="fa fa-plus me-2"></i> Muat Lebih Banyak
                </button>
            </div>

        </div>
    </div>

    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

</div>
</div>

@endsection

@section('script')
<style>
    /* Product Card Hover Effects */
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15) !important;
    }
    .product-card:hover .product-img {
        transform: scale(1.08);
    }
    .product-card:hover .product-overlay {
        opacity: 1 !important;
    }
    .product-card:hover .product-name-link {
        color: #636e72 !important;
    }

    /* Cart Button Hover */
    .btn-add-cart-main:hover:not(:disabled) {
        background: #333 !important;
        color: white !important;
    }

    /* Filter Button Active State */
    .filter-btn.active {
        background: #333 !important;
        color: white !important;
        border-color: #333 !important;
    }
    .filter-btn:not(.active):hover {
        border-color: #333 !important;
        color: #333 !important;
    }

    /* Overlay buttons hover */
    .product-overlay a:hover,
    .product-overlay button:hover {
        transform: scale(1.1);
    }

    /* Loading spinner for cart button */
    .btn-loading {
        opacity: 0.7;
        pointer-events: none;
    }

    /* Toast Notification */
    #cart-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        min-width: 280px;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #cart-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    @media (max-width: 576px) {
        .product-card .product-img-wrap { height: 180px !important; }
        #cart-toast { left: 15px; right: 15px; min-width: unset; }
    }
</style>

<!-- Toast Notification -->
<div id="cart-toast" class="alert mb-0" role="alert">
    <div class="d-flex align-items-center gap-2">
        <i class="fa fa-check-circle" id="toast-icon" style="font-size: 1.2rem;"></i>
        <span id="toast-message"></span>
    </div>
</div>

<script>
$(document).ready(function () {

    // ── Filter Tabs ──────────────────────────────────────────────────
    $('.filter-btn').on('click', function () {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        const filter = $(this).data('filter');

        if (filter === 'all') {
            $('.product-card-wrapper').fadeIn(300);
        } else {
            $('.product-card-wrapper').each(function () {
                if ($(this).data('kategori') == filter) {
                    $(this).fadeIn(300);
                } else {
                    $(this).fadeOut(200);
                }
            });
        }
    });

    // ── Load More ────────────────────────────────────────────────────
    let skip = {{ $produk->count() }};
    $('.load-more-btn').on('click', function () {
        const btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Memuat...').prop('disabled', true);

        $.ajax({
            url: '{{ route('load.more') }}',
            method: 'GET',
            data: { skip: skip },
            success: function (response) {
                if (response && response.trim() !== '') {
                    $('#product-grid').append(response);
                    skip += 8;
                    btn.html('<i class="fa fa-plus me-2"></i> Muat Lebih Banyak').prop('disabled', false);
                } else {
                    btn.html('Semua produk telah dimuat').prop('disabled', true)
                        .css('opacity', '0.5');
                }
            },
            error: function () {
                btn.html('<i class="fa fa-plus me-2"></i> Muat Lebih Banyak').prop('disabled', false);
            }
        });
    });

});

// ── Add to Cart (AJAX) ───────────────────────────────────────────────
function addToCart(productId, productName) {
    const btnOverlay = document.getElementById('cart-btn-' + productId);
    const btnMain    = document.getElementById('main-cart-btn-' + productId);

    // Show loading state
    if (btnMain) {
        btnMain.innerHTML = '<i class="fa fa-spinner fa-spin fa-sm"></i> Menambahkan...';
        btnMain.classList.add('btn-loading');
    }

    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ id: productId, quantity: 1 }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.redirect) {
            showToast(data.message || 'Silakan login terlebih dahulu.', 'error');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
            return;
        }

        if (data.success) {
            showToast(data.message || 'Produk ditambahkan ke keranjang!', 'success');

            // Restore button
            if (btnMain) {
                btnMain.innerHTML = '<i class="fa fa-check fa-sm"></i> Ditambahkan!';
                btnMain.style.background = '#2ecc71';
                btnMain.style.color = 'white';
                btnMain.style.borderColor = '#2ecc71';
                btnMain.classList.remove('btn-loading');

                setTimeout(() => {
                    btnMain.innerHTML = '<i class="fa fa-shopping-cart fa-sm"></i> Tambah ke Keranjang';
                    btnMain.style.background = '';
                    btnMain.style.color = '';
                    btnMain.style.borderColor = '';
                }, 2500);
            }
        } else {
            showToast(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
            if (btnMain) {
                btnMain.innerHTML = '<i class="fa fa-shopping-cart fa-sm"></i> Tambah ke Keranjang';
                btnMain.classList.remove('btn-loading');
            }
        }
    })
    .catch(() => {
        showToast('Gagal terhubung ke server.', 'error');
        if (btnMain) {
            btnMain.innerHTML = '<i class="fa fa-shopping-cart fa-sm"></i> Tambah ke Keranjang';
            btnMain.classList.remove('btn-loading');
        }
    });
}

// ── Toast Notification ───────────────────────────────────────────────
function showToast(message, type = 'success') {
    const toast   = document.getElementById('cart-toast');
    const msgEl   = document.getElementById('toast-message');
    const iconEl  = document.getElementById('toast-icon');

    msgEl.textContent = message;

    if (type === 'success') {
        toast.className = 'alert alert-success mb-0';
        iconEl.className = 'fa fa-check-circle';
        iconEl.style.color = '#2ecc71';
    } else {
        toast.className = 'alert alert-danger mb-0';
        iconEl.className = 'fa fa-times-circle';
        iconEl.style.color = '#e74c3c';
    }

    toast.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
@endsection


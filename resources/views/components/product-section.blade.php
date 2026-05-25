<!-- Start Product Section -->
<div class="product-section">
    <div class="container">
        <div class="row">

            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Dibuat dengan Cinta oleh Pengrajin UMKM Indonesia</h2>
                <p class="mb-4">Kami bangga mendukung produk lokal dari pengrajin Indonesia. Setiap produk dibuat
                    dengan ketelitian dan penuh dedikasi untuk kualitas yang terbaik.</p>
                <p><a href="{{ route('Produk.index') }}" class="btn">Jelajahi Produk Kami</a></p>
            </div>
            <!-- End Column 1 -->

            @php
                use App\Models\Produk;
                $produk = Produk::with('images')->latest()->paginate(3);
            @endphp

            @foreach ($produk as $carts)
                <!-- Start Column 2 -->
                <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                    <a class="product-item" href="{{ route('Produk.index') }}">
                        @if ($carts->images->isNotEmpty())
                            <img src="{{ $carts->images->first()->image_path }}" alt="IMG-PRODUCT"
                                class="img-fluid" height="300px" width="200px">
                        @else
                            <img src="{{ asset('path/to/default-image.jpg') }}" alt="Default Image" class="img-fluid">
                        @endif
                        <h3 class="product-title">{{ $carts->nama }}</h3>
                        <strong class="product-price">Rp {{ number_format($carts->harga, 0, ',', '.') }}</strong>

                        <span class="icon-cross">
                            <img src="{{ asset('asset/image/cross.svg') }}" class="img-fluid">
                        </span>
                    </a>
                </div>
                <!-- End Column 2 -->
            @endforeach

        </div>
    </div>
</div>
<!-- End Product Section -->

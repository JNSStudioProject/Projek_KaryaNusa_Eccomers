@foreach ($produk as $produk1)
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
        <!-- Block2 -->
        <div class="block2">
            <div class="block2-pic hov-img0">
                @php
                    // Mengambil gambar pertama jika ada
                    $image = $produk1->images->first();
                @endphp

                @if ($image)
                    <img src="{{ asset($image->image_path) }}" alt="IMG-PRODUCT" width="60px" height="300px">
                @endif

                <a href=""
                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                    Quick View
                </a>
            </div>

            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="/detail/{{ $produk1->id }}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                        Nama : {{ $produk1->nama }}
                    </a>

                    <span class="stext-105 cl3">
                        Harga : {{ $produk1->harga }}
                    </span>
                </div>

                <div class="block2-txt-child2 flex-r p-t-3">
                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                        <img class="icon-heart1 dis-block trans-04" src="{{ asset('image/icons/icon-heart-01.png') }}"
                            alt="ICON">
                        <img class="icon-heart2 dis-block trans-04 ab-t-l"
                            src="{{ asset('image/icons/icon-heart-02.png') }}" alt="ICON">
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach

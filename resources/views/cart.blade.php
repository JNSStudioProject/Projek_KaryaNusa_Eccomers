@extends('layoutUser.temp', ['title' => 'Cart UMKM '])
@section('content')
@section('hero-title')
    Keranjang
@endsection

<div class="untree_co-section before-footer-section">

    <div class="container">
        <h1 style="margin-left: 70px;margin-top: 0px;color: black;font-size:50px" class="d-block">Keranjang Anda</h1>
        <div class="row mb-5">
            <div class="site-blocks-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th> <input type="checkbox" id="checkAll">
                            </th>
                            <th class="product-thumbnail">Gambar</th>
                            <th class="product-name">Produk</th>
                            <th class="product-price">Harga</th>
                            <th class="product-quantity">Jumlah</th>
                            <th class="product-total">Total</th>
                            <th class="product-remove">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $carts)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_items[]" value="{{ $carts->id }}"
                                        class="check-item" id="ids">
                                </td>
                                @php
                                    $image = $carts->produk->images->first();
                                @endphp

                                <td class="product-thumbnail">
                                    <img src="{{ asset($image->image_path) }}" alt="IMG-PRODUCT"
                                        class="img-fluid">
                                </td>
                                <td class="product-name">
                                    <h2 class="h5 text-black">{{ $carts->produk->nama }}</h2>
                                </td>
                                <td>{{ $carts->produk->harga }}</td>
                                <td>
                                    <div class="input-group mb-3 d-flex align-items-center quantity-container"
                                        style="max-width: 120px;">

                                        <input type="text" class="form-control text-center quantity-amount"
                                            value="{{ $carts->quantity }}">

                                    </div>
                                </td>
                                <td>
                                    @php
                                        $total = $carts->quantity * floatval($carts->produk->harga);
                                    @endphp
                                    {{ $total }}
                                </td>
                                <td>
                                    <button class="btn btn-md rounded-circle bg-light border mt-4"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $carts->id }}">
                                        <i class="fa fa-pencil-alt " style="color: #6C370B"></i>
                                    </button>
                                    <form id="delete-item-{{ $carts->id }}"
                                        action="{{ route('cart.delete', $carts->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button
                                        class="btn btn-md rounded-circle bg-light border mt-4"onclick="confirmDelete({{ $carts->id }})">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                    {{-- <a href="#" onclick="confirmDelete({{ $carts->id }})"
                                        class="btn btn-danger">Hapus</a> --}}
                                </td>
                            </tr>
                            {{-- Modal edit --}}
                            <div class="modal fade" id="editModal{{ $carts->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $carts->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $carts->id }}">Edit
                                                Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('cart.update', $carts->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="jumlah{{ $carts->id }}"
                                                        class="form-label">Jumlah</label>
                                                    <input type="number" class="form-control"
                                                        id="jumlah{{ $carts->id }}" name="jumlah"
                                                        value="{{ $carts->id }}" min="1">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <a href="{{ route('Produk.index') }}" class="btn btn-outline-black btn-sm btn-block">Lanjut
                            berbelanja
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Form untuk Checkout -->
                <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                    @csrf
                    <!-- Input Hidden untuk Mengirim ID Produk yang Dipilih -->
                    <input type="hidden" name="selected_items" id="selectedItemsInput">

                    <button class="btn btn-black btn-lg py-3 btn-block" type="submit">
                        Prosses untuk Checkout
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    function confirmDelete(cartId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Item ini akan dihapus dari keranjang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-item-' + cartId).submit();
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        // Event untuk checkbox "Check All"
        $("#checkAll").click(function() {
            $(".check-item").prop("checked", this
                .checked); // Semua checkbox mengikuti status "Check All"
        });

        // Event untuk mengupdate status "Check All" berdasarkan checkbox individual
        $(".check-item").click(function() {
            if ($(".check-item:checked").length === $(".check-item").length) {
                $("#checkAll").prop("checked",
                    true); // Jika semua checkbox tercentang, "Check All" ikut tercentang
            } else {
                $("#checkAll").prop("checked",
                    false); // Jika ada yang tidak tercentang, "Check All" tidak tercentang
            }
        });

        // Ketika tombol "Proceed To Checkout" ditekan
        $('#checkoutForm').submit(function() {
            // Ambil semua checkbox yang tercentang
            var selectedItems = [];
            $("input[name='selected_items[]']:checked").each(function() {
                selectedItems.push($(this).val()); // Ambil value dari checkbox yang tercentang
            });

            // Cek apakah ada item yang dipilih
            if (selectedItems.length > 0) {
                // Masukkan data ke input hidden
                $('#selectedItemsInput').val(JSON.stringify(selectedItems)); // Mengirim data terpilih

                return true; // Lanjutkan form submit
            } else {
                // Jika tidak ada item yang dipilih, tampilkan alert
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih item untuk melanjutkan ke checkout!',
                });
                return false; // Batalkan submit jika tidak ada item yang dipilih
            }
        });
    });
</script>
@endsection

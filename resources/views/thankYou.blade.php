@extends('layoutUser.temp')
@section('content')
@section('hero-title')
    Pembayaran Berhasil
@endsection
<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center pt-5 pb-5">
                <div class="card shadow-sm border-0 rounded-lg p-4 p-md-5" style="border-radius: 1rem;">
                    <div class="card-body">
                        <!-- Ikon Sukses -->
                        <span class="d-inline-block text-success mb-4">
                            <svg width="5em" height="5em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </span>
                        
                        <h2 class="h3 font-weight-bold text-dark mb-3">Pembayaran Berhasil!</h2>
                        
                        @if(isset($order))
                            <div class="bg-light rounded p-3 mb-4 border">
                                <p class="mb-1 text-muted" style="font-size: 0.9rem;">Nomor Pesanan Anda</p>
                                <h4 class="mb-0 font-weight-bold" style="color: #222;">#{{ $order->id }}</h4>
                            </div>
                        @endif
                        
                        <p class="text-muted mb-4" style="line-height: 1.6; font-size: 0.95rem;">
                            Terima kasih telah berbelanja di tempat kami. Pesanan Anda akan segera kami proses.<br><br>
                            Nomor resi pengiriman akan kami perbarui setelah barang dikirimkan. <strong>Silakan cek kotak masuk email Anda secara berkala!</strong>
                        </p>
                        
                        <a href="{{ route('Produk.index') }}" class="btn btn-dark btn-lg px-5 mt-2" style="border-radius: 50px;">Kembali Belanja</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

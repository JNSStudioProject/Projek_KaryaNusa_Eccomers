@extends('layoutUser.temp')

@section('hero-title')
    Pesanan Saya
@endsection

@section('content')
<div class="untree_co-section">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h2 class="h3 mb-4 text-black text-center font-weight-bold">Status Pesanan</h2>
                
                @if($pemesanan->isEmpty())
                    <div class="alert alert-info text-center py-4 rounded shadow-sm border-0">
                        Belum ada pesanan. Silakan <a href="{{ route('Produk.index') }}" class="font-weight-bold text-decoration-none">Mulai Belanja</a>!
                    </div>
                @else
                    @foreach($pemesanan as $pesan)
                    <div class="card shadow-sm mb-4 border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted" style="font-size: 0.9rem;">Order ID:</span> 
                                <strong class="text-dark">#{{ $pesan->id }}</strong>
                            </div>
                            <div>
                                @if($pesan->status_pesan == 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Belum Dibayar</span>
                                @elseif($pesan->status_pesan == 'sukses')
                                    <span class="badge bg-info text-white px-3 py-2 rounded-pill">Sedang Diproses</span>
                                @elseif($pesan->status_pesan == 'dikirim')
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill">Sedang Dikirim</span>
                                @elseif($pesan->status_pesan == 'selesai')
                                    <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">Selesai</span>
                                @else
                                    <span class="badge bg-dark text-white px-3 py-2 rounded-pill">{{ ucfirst($pesan->status_pesan) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="font-weight-bold mb-2 text-dark">Total Biaya: Rp {{ number_format($pesan->total_biaya, 0, ',', '.') }}</h5>
                                    <p class="text-muted mb-1" style="font-size: 0.95rem;">Jumlah Item: <strong>{{ $pesan->total_item_pesanan }} barang</strong></p>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;"><i class="far fa-clock me-1"></i> {{ $pesan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    @if($pesan->status_pesan == 'pending' && $pesan->snap_item)
                                        <a href="{{ route('PaymentView', ['snapToken' => $pesan->snap_item]) }}" class="btn btn-dark btn-sm rounded-pill px-4 py-2 fw-bold">Lanjut Bayar</a>
                                    @else
                                        <a href="#" class="btn btn-outline-dark btn-sm rounded-pill px-4 py-2 fw-bold">Detail Pesanan</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($pesan->status_pesan == 'sukses' || $pesan->status_pesan == 'dikirim')
                        <div class="card-footer bg-light border-0 py-3 text-muted" style="font-size: 0.9rem;">
                            <i class="fas fa-truck text-success me-2"></i> 
                            @if($pesan->status_pesan == 'sukses')
                                Pesanan Anda sedang dipersiapkan oleh penjual dan akan segera diserahkan ke kurir.
                            @else
                                Pesanan Anda sedang dalam perjalanan. Nomor Resi: <strong class="text-dark">{{ $pesan->resi ?? 'Akan segera diupdate' }}</strong>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

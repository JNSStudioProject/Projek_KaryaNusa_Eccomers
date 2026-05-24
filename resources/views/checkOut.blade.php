@extends('layoutUser.temp', ['title' => 'Checkout Pemesanan'])
@section('content')
@section('hero-title')
    Checkout
@endsection

<div class="untree_co-section">
    <div class="container">
        <!-- Leaflet CSS diletakkan di dalam container agar pasti dimuat -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        <style>
            #map { height: 300px; width: 100%; border-radius: 8px; border: 1px solid #ccc; z-index: 10; }
            .map-container { margin-bottom: 25px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; background-color: #fafafa; }
        </style>

        <br><br>
        <div class="row">
            <!-- Bagian Kiri: Alamat & Ekspedisi -->
            <div class="col-md-7 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Detail Pengiriman</h2>
                <div class="p-3 p-lg-5 border bg-white">
                    
                    <!-- 1. Bagian Alamat -->
                    @if(Auth::user()->kota_id)
                        <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                            <h5 class="text-black mb-3"><i class="fa fa-map-marker-alt text-primary"></i> Alamat Tersimpan</h5>
                            <p class="mb-1"><strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->no_hp }})</p>
                            <p class="mb-1">{{ Auth::user()->alamat_lengkap }}</p>
                            <p class="mb-3">Kode Pos: {{ Auth::user()->kode_pos }}</p>
                            <button type="button" class="btn btn-sm text-white" id="btn-ubah-alamat" style="background-color: #6C370B; border-color: #6C370B;">Ubah Alamat</button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Silakan lengkapi alamat pengiriman Anda terlebih dahulu.
                        </div>
                    @endif

                    <!-- Form Alamat (Hidden jika sudah ada alamat, kecuali ditekan Ubah) -->
                    <div id="form-alamat" style="display: {{ Auth::user()->kota_id ? 'none' : 'block' }};">
                        
                        <!-- PETA INTERAKTIF ALA SHOPEE -->
                        <div class="map-container">
                            <h5 class="text-black mb-2"><i class="fa fa-map-marked-alt text-danger"></i> Pin Lokasi Pengiriman</h5>
                            <p class="text-muted small mb-3">Klik tombol "Deteksi Lokasi" atau geser pin merah di peta agar kurir mudah menemukan rumah Anda. Alamat lengkap akan terisi otomatis!</p>
                            <button class="btn btn-sm mb-3 text-white" id="btn-detect-location" type="button" style="background-color: #6C370B; border-color: #6C370B; font-weight:bold;">
                                <i class="fa fa-crosshairs"></i> Deteksi Lokasi GPS Saya
                            </button>
                            <div id="map"></div>
                        </div>

                        <form id="save-address-form" action="javascript:void(0);">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="text-black">Provinsi <span class="text-danger">*</span></label>
                                    <select id="provinsi" class="form-control" name="provinsi_id" required>
                                        <option value="">Pilih Provinsi</option>
                                        @if(isset($provinsi['rajaongkir']['status']['code']) && $provinsi['rajaongkir']['status']['code'] == 200)
                                            @foreach($provinsi['rajaongkir']['results'] as $pv)
                                                <option value="{{ $pv['province_id'] }}">{{ $pv['province'] }}</option>
                                            @endforeach
                                        @else
                                            <option value="">RajaOngkir Gangguan/Diblokir Internet Anda</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-black">Kota <span class="text-danger">*</span></label>
                                    <select id="kota" class="form-control" name="kota_id" required>
                                        <option value="">Pilih Kota</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-black">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="alamat_lengkap" class="form-control" rows="3" required placeholder="Nama jalan, gedung, no rumah">{{ Auth::user()->alamat_lengkap }}</textarea>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="text-black">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="kode_pos" value="{{ Auth::user()->kode_pos }}" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-black btn-sm">Simpan Alamat</button>
                            </div>
                        </form>
                        <hr>
                    </div>

                    <!-- 2. Bagian Pilihan Ekspedisi -->
                    <h5 class="text-black mt-4 mb-3">Opsi Pengiriman</h5>
                    <div class="form-group">
                        <label class="text-black">Pilih Kurir <span class="text-danger">*</span></label>
                        <select id="ekspidisi" class="form-control">
                            <option value="">-- Pilih Kurir --</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    
                    <!-- Loading Spinner Ongkir -->
                    <div id="loading-ongkir" class="text-center mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Menghitung ongkos kirim...</p>
                    </div>

                    <!-- Tempat Menampilkan Pilihan Paket Ongkir -->
                    <div id="opsi-paket" class="mt-4"></div>

                </div>
            </div>

            <!-- Bagian Kanan: Ringkasan & Pembayaran -->
            <div class="col-md-5">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Ringkasan Pesanan</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <th>Produk</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr>
                                            <td>{{ $item->produk->nama }} <strong class="mx-2">x</strong> {{ $item->quantity }}</td>
                                            <td>Rp{{ number_format($item->produk->harga * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Subtotal Produk</strong></td>
                                        <td class="text-black">Rp{{ number_format($subTotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Total Berat</strong></td>
                                        <td class="text-black">{{ $total_berat }} Gram</td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Ongkos Kirim</strong></td>
                                        <td class="text-black" id="display-ongkir">Rp0</td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Total Tagihan</strong></td>
                                        <td class="text-black font-weight-bold"><strong id="display-total" data-subtotal="{{ $subTotal }}">Rp{{ number_format($subTotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <form id="form-bayar">
                                @csrf
                                <input type="hidden" id="selected_ongkir" name="ongkir" value="0">
                                <button type="submit" class="btn btn-black btn-lg py-3 btn-block" id="btn-bayar" disabled>Buat Pesanan & Bayar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    // Inisialisasi Variabel Map
    var map;
    var marker;

    function initMap() {
        // Jika peta sudah ada, abaikan
        if (map) return;
        
        // Default lokasi (Pekanbaru Riau sebagai contoh)
        var defaultLoc = [0.5070677, 101.4477793];
        map = L.map('map').setView(defaultLoc, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker yang bisa di-drag
        marker = L.marker(defaultLoc, {draggable: true}).addTo(map);

        // Event saat marker selesai digeser
        marker.on('dragend', function (e) {
            var latlng = marker.getLatLng();
            fetchAddress(latlng.lat, latlng.lng);
        });
        
        // Memastikan ukuran map dirender sempurna
        setTimeout(function(){ map.invalidateSize(); }, 500);
    }

    // Mengambil Nama Jalan dari Koordinat (Reverse Geocoding)
    function fetchAddress(lat, lng) {
        var url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
        
        $('textarea[name="alamat_lengkap"]').val('Menerjemahkan lokasi peta ke alamat...');
        
        $.get(url, function(data) {
            if(data && data.display_name) {
                $('textarea[name="alamat_lengkap"]').val(data.display_name);
                if(data.address && data.address.postcode) {
                    $('input[name="kode_pos"]').val(data.address.postcode);
                }
            } else {
                $('textarea[name="alamat_lengkap"]').val('Titik belum dikenali sistem, silakan ketik alamat manual.');
            }
        }).fail(function() {
            $('textarea[name="alamat_lengkap"]').val('Gagal mengambil data peta karena koneksi. Ketik alamat manual.');
        });
    }

    $(document).ready(function() {
        
        // Panggil initMap setelah delay sedikit agar DOM benar-benar siap
        setTimeout(function() {
            initMap();
        }, 500);

        // Tombol Ubah Alamat diklik
        $('#btn-ubah-alamat').click(function() {
            $('#form-alamat').slideToggle(300, function() {
                if (map) {
                    map.invalidateSize();
                }
            });
        });

        // Tombol Deteksi GPS
        $('#btn-detect-location').click(function() {
            if (navigator.geolocation) {
                Swal.fire({
                    title: 'Mencari Lokasi',
                    text: 'Pastikan Anda mengizinkan akses GPS di browser saat muncul pop-up...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                navigator.geolocation.getCurrentPosition(function(position) {
                    Swal.close();
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    var latlng = new L.LatLng(lat, lng);
                    
                    map.setView(latlng, 17);
                    marker.setLatLng(latlng);
                    
                    // Tarik teks jalan
                    fetchAddress(lat, lng);
                }, function(error) {
                    Swal.fire('Akses Ditolak', 'Izin lokasi (GPS) diblokir oleh browser Anda atau gagal didapat.', 'error');
                }, { timeout: 10000 });
            } else {
                Swal.fire('Error', 'Browser Anda tidak mendukung deteksi lokasi.', 'error');
            }
        });

        // 1. Fetch Kota berdasarkan Provinsi
        $('#provinsi').change(function() {
            var provId = $(this).val();
            if(provId) {
                $('#kota').html('<option>Loading...</option>');
                $.get('<?= url('/kota/') ?>/' + provId, function(data) {
                    $('#kota').html(data);
                }).fail(function() {
                    $('#kota').html('<option value="">Gagal memuat kota</option>');
                });
            } else {
                $('#kota').html('<option value="">Pilih Kota</option>');
            }
        });

        // 2. Simpan Alamat User secara AJAX
        $('#save-address-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            formData += '&_token={{ csrf_token() }}';
            
            $.post('{{ route("save-address") }}', formData, function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Alamat berhasil disimpan!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload(); // Reload agar UI berubah ke mode alamat tersimpan
                    });
                } else {
                    Swal.fire('Gagal!', 'Gagal menyimpan alamat.', 'error');
                }
            }).fail(function() {
                Swal.fire('Error!', 'Terjadi kesalahan sistem saat menyimpan alamat.', 'error');
            });
        });

        // 3. Hitung Ongkir Dinamis
        $('#ekspidisi').change(function() {
            var kurir = $(this).val();
            var userKotaId = "{{ Auth::user()->kota_id }}";
            var totalBerat = {{ $total_berat }};

            if(!userKotaId) {
                Swal.fire('Peringatan', 'Silakan lengkapi dan simpan alamat pengiriman Anda terlebih dahulu di bagian atas!', 'warning');
                $(this).val('');
                return;
            }

            if(kurir) {
                $('#opsi-paket').empty();
                $('#loading-ongkir').show();

                $.ajax({
                    url: "{{ route('hitungOngkir') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ekspidisi: kurir,
                        berat: totalBerat
                    },
                    success: function(response) {
                        $('#loading-ongkir').hide();
                        if(response.success) {
                            var status = response.data.rajaongkir.status.code;
                            if(status == 200) {
                                var costs = response.data.rajaongkir.results[0].costs;
                                if(costs.length === 0) {
                                    $('#opsi-paket').html('<div class="alert alert-danger">Kurir tidak mendukung pengiriman ke daerah Anda.</div>');
                                    return;
                                }

                                var html = '<h6 class="mb-3">Pilih Paket Pengiriman:</h6>';
                                $.each(costs, function(i, by) {
                                    var price = by.cost[0].value;
                                    var etd = by.cost[0].etd;
                                    html += `
                                    <div class="card mb-2 border">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input radio-ongkir" type="radio" name="paket_ongkir" id="paket${i}" value="${price}">
                                                <label class="form-check-label w-100" for="paket${i}">
                                                    <strong>${by.service}</strong> (${by.description})<br>
                                                    <span class="text-primary">Rp${new Intl.NumberFormat('id-ID').format(price)}</span> | Estimasi: ${etd} hari
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;
                                });
                                $('#opsi-paket').html(html);
                            } else {
                                $('#opsi-paket').html('<div class="alert alert-danger">API RajaOngkir Gangguan.</div>');
                            }
                        } else {
                            $('#opsi-paket').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#loading-ongkir').hide();
                        $('#opsi-paket').html('<div class="alert alert-danger">Gagal menghubungi server ongkos kirim.</div>');
                    }
                });
            } else {
                $('#opsi-paket').empty();
            }
        });

        // 4. Update Total Belanja Saat Pilih Paket Ongkir
        $(document).on('change', '.radio-ongkir', function() {
            var ongkir = parseInt($(this).val());
            var subtotal = parseInt($('#display-total').data('subtotal'));
            var total = subtotal + ongkir;

            // Update UI
            $('#display-ongkir').text('Rp' + new Intl.NumberFormat('id-ID').format(ongkir));
            $('#display-total').text('Rp' + new Intl.NumberFormat('id-ID').format(total));
            
            // Set input hidden untuk dikirim ke Midtrans
            $('#selected_ongkir').val(ongkir);
            
            // Aktifkan tombol bayar
            $('#btn-bayar').prop('disabled', false);
        });

        // 5. Submit Pemesanan (Bayar)
        $('#form-bayar').submit(function(e) {
            e.preventDefault();
            var ongkirValue = $('#selected_ongkir').val();
            
            // Kunci tombol agar tidak dobel klik
            $('#btn-bayar').prop('disabled', true).text('Memproses...');

            $.ajax({
                url: "{{ route('PaymentUpdate') }}", // Panggil route Midtrans
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ongkir: ongkirValue,
                    selected_items: {!! $selectedItems !!}
                },
                success: function(response) {
                    var snapToken = response.paket.snapToken;
                    if (snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                window.location.href = '{{ route('sukses') }}';
                            },
                            onPending: function(result) {
                                Swal.fire('Menunggu', 'Menunggu penyelesaian pembayaran Anda.', 'info').then(() => {
                                    window.location.href = '{{ route('sukses') }}';
                                });
                            },
                            onError: function(result) {
                                Swal.fire('Gagal!', 'Pembayaran gagal diproses!', 'error');
                                $('#btn-bayar').prop('disabled', false).text('Buat Pesanan & Bayar');
                            },
                            onClose: function() {
                                $('#btn-bayar').prop('disabled', false).text('Buat Pesanan & Bayar');
                            }
                        });
                    } else {
                        Swal.fire('Error!', 'Gagal mendapatkan Token Pembayaran dari server.', 'error');
                        $('#btn-bayar').prop('disabled', false).text('Buat Pesanan & Bayar');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan jaringan saat memproses pesanan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 500) {
                        errorMsg = 'Internal Server Error. Pastikan API Key Midtrans Anda valid di file .env.';
                    }
                    Swal.fire('Gagal!', errorMsg, 'error');
                    $('#btn-bayar').prop('disabled', false).text('Buat Pesanan & Bayar');
                }
            });
        });

    });
</script>
@endsection

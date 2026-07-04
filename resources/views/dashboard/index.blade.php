@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            @if (Auth()->user()->role === 'admin')
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $countKaryawan }}</h3>
                                <p>Jumlah Karyawan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('karyawan.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $countJabatan }}</h3>
                                <p>Jumlah Jabatan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('data-jabatan.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalAbsenMasukToday }}/{{ $countKaryawan }}</h3>
                                <p>Jumlah Karyawan Absen</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $countAdmin }}</h3>
                                <p>Jumlah Admin</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('karyawan.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            @elseif (Auth()->user()->role === 'karyawan')
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <span class="h5 m-0">Absensi {{ app('formatTanggalIndo', [now()]) }}</span>
                            </div>
                            <div class="card-body">
                                <!-- Pesan Sukses -->
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- Pesan Error -->
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info text-center" role="alert">
                                            <h4 class="alert-heading">Jam Absensi</h4>
                                            <p>
                                                Jadwal jam masuk adalah {{ $jadwalAbsensi->jam_masuk }}, dan jadwal jam
                                                pulang
                                                adalah {{ $jadwalAbsensi->jam_pulang }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h2>{{ $attendance && $attendance->absen_masuk ? 'Sudah absen' : 'Belum absen' }}
                                                </h2>
                                                <span>Absen Masuk</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h2>{{ $attendance && $attendance->absen_pulang ? 'Sudah absen' : 'Belum absen' }}
                                                </h2>
                                                <span>Absen Pulang</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    @if (!$attendance || !$attendance->absen_masuk)
                                        <form action="{{ route('absen.masuk') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Absen Masuk</button>
                                        </form>

                                        <!-- Button for QR Code Absen Masuk -->
                                        <button type="button" class="btn btn-primary mt-2" data-toggle="modal"
                                            data-target="#qrScanModalMasuk">Scan QR Code Masuk</button>
                                    @endif

                                    @if ($attendance && !$attendance->absen_pulang)
                                        <form action="{{ route('absen.pulang') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Absen Pulang</button>
                                        </form>

                                        <!-- Button for QR Code Absen Pulang -->
                                        <button type="button" class="btn btn-primary mt-2" data-toggle="modal"
                                            data-target="#qrScanModalPulang">Scan QR Code Pulang</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal for QR Scan Absen Masuk -->
    <div class="modal fade" id="qrScanModalMasuk" tabindex="-1" aria-labelledby="qrScanLabelMasuk" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScanLabelMasuk">Scan QR Code Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader-masuk" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for QR Scan Absen Pulang -->
    <div class="modal fade" id="qrScanModalPulang" tabindex="-1" aria-labelledby="qrScanLabelPulang"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScanLabelPulang">Scan QR Code Pulang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader-pulang" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let html5QrCodeMasuk, html5QrCodePulang;

        // Initialize QR code reader for "Absen Masuk"
        $('#qrScanModalMasuk').on('shown.bs.modal', function() {
            html5QrCodeMasuk = new Html5Qrcode("qr-reader-masuk");
            html5QrCodeMasuk.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                },
                qrCodeMessage => {
                    html5QrCodeMasuk.stop().then(() => {
                        let form = $('<form>', {
                            method: 'POST',
                            action: '{{ route('absen.scan') }}'
                        });
                        form.append($('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        }));
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'qr_data',
                            value: qrCodeMessage
                        }));
                        form.appendTo('body').submit();
                    });
                },
                errorMessage => {
                    console.error("QR Code no longer in front of camera.", errorMessage);
                }
            ).catch(err => {
                console.error("Camera initialization failed", err);
                alert("Unable to access camera. Please check your permissions and browser compatibility.");
            });
        }).on('hidden.bs.modal', function() {
            if (html5QrCodeMasuk) {
                html5QrCodeMasuk.stop().then(() => {
                    html5QrCodeMasuk.clear();
                }).catch(err => {
                    console.error("Failed to stop the QR code reader", err);
                });
            }
        });

        // Initialize QR code reader for "Absen Pulang"
        $('#qrScanModalPulang').on('shown.bs.modal', function() {
            html5QrCodePulang = new Html5Qrcode("qr-reader-pulang");
            html5QrCodePulang.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                },
                qrCodeMessage => {
                    html5QrCodePulang.stop().then(() => {
                        let form = $('<form>', {
                            method: 'POST',
                            action: '{{ route('absen.scan.pulang') }}'
                        });
                        form.append($('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        }));
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'qr_data',
                            value: qrCodeMessage
                        }));
                        form.appendTo('body').submit();
                    });
                }
            ).catch(err => {
                console.error("Camera initialization failed", err);
                alert("Unable to access camera. Please check your permissions and browser compatibility.");
            });
        }).on('hidden.bs.modal', function() {
            if (html5QrCodePulang) {
                html5QrCodePulang.stop().then(() => {
                    html5QrCodePulang.clear();
                }).catch(err => {
                    console.error("Failed to stop the QR code reader", err);
                });
            }
        });
    </script>
@endsection

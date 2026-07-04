@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="h5 m-0">Edit Gaji {{ $user->name }}
                                ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }})</span>
                            <div class="card-tools">
                                <a href="{{ route('penggajian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header border-bottom-0 h5">Data Karyawan</div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td>NIP</td>
                                                        <td>:</td>
                                                        <td>{{ $user->nip }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama Karyawan</td>
                                                        <td>:</td>
                                                        <td>{{ $user->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jabatan</td>
                                                        <td>:</td>
                                                        <td>{{ $user->jabatan->nama_jabatan }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card text-white bg-primary">
                                                <div class="card-body text-center">
                                                    Total Hari Kerja
                                                    <h3>{{ $totalHariKerja }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card text-white bg-success">
                                                <div class="card-body text-center">
                                                    Total Kehadiran
                                                    <h3>{{ $totalKehadiran }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card text-white bg-warning">
                                                <div class="card-body text-center">
                                                    Total Terlambat
                                                    <h3>{{ $totalKeterlambatan }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card text-white bg-danger">
                                                <div class="card-body text-center">
                                                    Total Tidak Hadir
                                                    <h3>{{ $totalTidakHadir }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ route('penggajian.update', $salary->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="hidden" name="year" value="{{ $year }}">
                                                <input type="hidden" name="month" value="{{ $month }}">

                                                <div class="form-group">
                                                    <label for="gaji_pokok">Gaji Pokok</label>
                                                    <input type="number" class="form-control" id="gaji_pokok"
                                                        name="gaji_pokok" value="{{ $salary->gaji_pokok }}" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="tunjangan_jabatan">Tunjangan Jabatan</label>
                                                    <input type="number" class="form-control" id="tunjangan_jabatan"
                                                        name="tunjangan_jabatan" value="{{ $salary->tunjangan_jabatan }}"
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="bonus">Bonus</label>
                                                    <input type="number" class="form-control" id="bonus" name="bonus"
                                                        value="{{ $salary->bonus }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="potongan_absensi">Potongan Absensi</label>
                                                    <input type="number" class="form-control" id="potongan_absensi"
                                                        name="potongan_absensi" value="{{ $salary->potongan_absensi }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="potongan_keterlambatan">Potongan Keterlambatan</label>
                                                    <input type="number" class="form-control" id="potongan_keterlambatan"
                                                        name="potongan_keterlambatan" value="{{ $potonganKeterlambatan }}"
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="potongan_lainnya">Potongan Lainnya</label>
                                                    <input type="number" class="form-control" id="potongan_lainnya"
                                                        name="potongan_lainnya" value="{{ $salary->potongan_lainnya }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="total_gaji">Total Gaji (dihitung otomatis)</label>
                                                    <input type="number" class="form-control" id="total_gaji" readonly>
                                                </div>

                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function hitungTotalGaji() {
                const gajiPokok = parseFloat($('#gaji_pokok').val()) || 0;
                const tunjanganJabatan = parseFloat($('#tunjangan_jabatan').val()) || 0;
                const bonus = parseFloat($('#bonus').val()) || 0;
                const potonganAbsensi = parseFloat($('#potongan_absensi').val()) || 0;
                const potonganKeterlambatan = parseFloat($('#potongan_keterlambatan').val()) || 0;
                const potonganLainnya = parseFloat($('#potongan_lainnya').val()) || 0;

                const totalGaji = gajiPokok + tunjanganJabatan + bonus - potonganAbsensi - potonganKeterlambatan -
                    potonganLainnya;
                $('#total_gaji').val(totalGaji);
            }

            // Trigger hitungTotalGaji on input change
            $('input').on('input', hitungTotalGaji);

            // Call on page load to set the initial total
            hitungTotalGaji();
        });
    </script>
@endsection

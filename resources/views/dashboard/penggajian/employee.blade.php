@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Penggajian {{ $user->name }}
                                ({{ $year }})</h5>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Bulan</th>
                                        <th>Gaji Pokok</th>
                                        <th>Tunjangan Jabatan</th>
                                        <th>Bonus</th>
                                        <th>Potongan Absensi</th>
                                        <th>Potongan Keterlambatan</th>
                                        <th>Potongan Lainnya</th>
                                        <th>Total Gaji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($salaries->count() > 0)
                                        @foreach ($salaries as $salary)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->translatedFormat('F') }}
                                                </td>
                                                <td>{{ app('formatRupiah', [$salary->gaji_pokok]) }}</td>
                                                <td>{{ app('formatRupiah', [$salary->tunjangan_jabatan]) }}</td>
                                                <td>{{ app('formatRupiah', [$salary->bonus]) }}</td>
                                                <td>{{ app('formatRupiah', [$salary->potongan_absensi]) }}</td>
                                                <td>{{ app('formatRupiah', [$salary->potongan_keterlambatan]) }}</td>
                                                <td>{{ app('formatRupiah', [$salary->potongan_lainnya]) }}</td>
                                                <td>{{ is_numeric($salary->decrypted_salary) ? app('formatRupiah', [$salary->decrypted_salary]) : $salary->decrypted_salary }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center">Data gaji belum tersedia untuk tahun ini.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

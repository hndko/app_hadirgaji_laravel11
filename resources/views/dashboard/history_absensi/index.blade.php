@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="h5 m-0">Data Absensi</span>
                        </div>
                        <div class="card-body">
                            <!-- Form untuk memilih tahun, bulan, dan karyawan (jika admin) -->
                            <form action="{{ route('absensi.index') }}" method="GET" class="form-inline mb-3">
                                <div class="form-group mr-3">
                                    <select name="year" class="form-control">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mr-3">
                                    <select name="month" class="form-control">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Dropdown Karyawan, hanya untuk admin -->
                                @if ($karyawanList)
                                    <div class="form-group mr-3">
                                        <select name="karyawan_id" class="form-control">
                                            <option value="">Pilih Karyawan</option>
                                            @foreach ($karyawanList as $karyawan)
                                                <option value="{{ $karyawan->id }}"
                                                    {{ $selectedKaryawanId == $karyawan->id ? 'selected' : '' }}>
                                                    {{ $karyawan->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary">Cari</button>
                            </form>

                            <!-- Tabel data absensi hanya jika karyawan sudah dipilih -->
                            @if (!$karyawanList || ($karyawanList && $selectedKaryawanId))
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = \Carbon\Carbon::create($selectedYear, $selectedMonth, $day);
                                                $attendance = $attendances->get($date->toDateString());
                                                $isHoliday = $holidays->contains($date->toDateString());
                                                $isWeekend = $date->isWeekend();
                                            @endphp

                                            <tr class="{{ $isHoliday || $isWeekend ? 'bg-light' : '' }}">
                                                <td>{{ $date->translatedFormat('l, d F Y') }}</td>
                                                <td>{{ $attendance->absen_masuk ?? '-' }}</td>
                                                <td>{{ $attendance->absen_pulang ?? '-' }}</td>
                                                <td>{{ $attendance->catatan ?? '-' }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>

                                <!-- Jika tidak ada data absensi -->
                                @if ($attendances->isEmpty())
                                    <p class="text-center">Tidak ada data absensi pada bulan ini.</p>
                                @endif
                            @else
                                <p class="text-center">Silakan pilih karyawan untuk melihat data absensi.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

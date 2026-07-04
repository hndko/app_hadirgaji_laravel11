@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="h5 m-0">Data Gaji Karyawan</span>
                            <div class="card-tools d-flex">
                                <!-- Form pencarian data -->
                                <div class="form-inline">
                                    <select id="yearSelect" class="form-control form-control-sm mr-2">
                                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                            <option value="{{ $i }}"
                                                {{ request('year') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select id="monthSelect" class="form-control form-control-sm mr-2">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button id="searchButton" class="btn btn-sm btn-primary mr-2">Cari</button>
                                    <button id="pdfButton" class="btn btn-sm btn-success">Download</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jabatan</th>
                                        <th>Total Gaji</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawanList as $karyawan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $karyawan->nip }}</td>
                                            <td>{{ $karyawan->name }}</td>
                                            <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                                            <td>
                                                @if (isset($salaries[$karyawan->id]))
                                                    @php
                                                        try {
                                                            // Decrypt the salary
                                                            $decryptedSalary = Crypt::decrypt(
                                                                $salaries[$karyawan->id]->encrypted_salary,
                                                            );
                                                        } catch (\Exception $e) {
                                                            $decryptedSalary = 'Error decrypting';
                                                        }
                                                    @endphp
                                                    {{ is_numeric($decryptedSalary) ? app('formatRupiah', [$decryptedSalary]) : $decryptedSalary }}
                                                @else
                                                    Belum digaji
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($salaries[$karyawan->id]))
                                                    <!-- If salary is already inputted, show Edit button -->
                                                    <a href="{{ route('penggajian.edit', ['id' => $salaries[$karyawan->id]->id, 'tahun' => request('year', now()->year), 'bulan' => request('month', now()->month), 'user_id' => $karyawan->id]) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                @else
                                                    <!-- If no salary is inputted yet, show Create button -->
                                                    <a href="{{ route('penggajian.create', ['tahun' => request('year', now()->year), 'bulan' => request('month', now()->month), 'user_id' => $karyawan->id]) }}"
                                                        class="btn btn-sm btn-success">Create</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Script -->
    <script>
        $(document).ready(function() {
            $('#searchButton').click(function(e) {
                e.preventDefault(); // Prevent default button behavior

                // Get selected values for year and month
                var selectedYear = $('#yearSelect').val();
                var selectedMonth = $('#monthSelect').val();

                // Redirect with the selected year and month as query parameters
                var url = '{{ route('penggajian.index') }}' + '?year=' + selectedYear + '&month=' +
                    selectedMonth;
                window.location.href = url;
            });

            // Handle PDF download button click
            $('#pdfButton').click(function(e) {
                e.preventDefault();

                // Get selected year and month values
                var selectedYear = $('#yearSelect').val();
                var selectedMonth = $('#monthSelect').val();

                // Trigger the PDF download using jQuery
                var pdfUrl = '{{ route('penggajian.pdf') }}' + '?year=' + selectedYear + '&month=' +
                    selectedMonth;
                window.location.href = pdfUrl;
            });
        });
    </script>
@endsection

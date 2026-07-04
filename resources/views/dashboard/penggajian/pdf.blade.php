<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Gaji Karyawan - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: left;
        }

        th,
        td {
            padding: 8px;
        }
    </style>
</head>

<body>
    <h1>Data Gaji Karyawan ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }})</h1>
    <table style="width: 100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>NIP</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
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
            @foreach ($karyawanList as $karyawan)
                @php
                    $salary = $salaries[$karyawan->id] ?? null; // Retrieve the salary for the user
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $karyawan->nip }}</td>
                    <td>{{ $karyawan->name }}</td>
                    <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                    <td>{{ app('formatRupiah', [$salary->gaji_pokok ?? 0]) }}</td>
                    <td>{{ app('formatRupiah', [$salary->tunjangan_jabatan ?? 0]) }}</td>
                    <td>{{ app('formatRupiah', [$salary->bonus ?? 0]) }}</td>
                    <td>{{ app('formatRupiah', [$salary->potongan_absensi ?? 0]) }}</td>
                    <td>{{ app('formatRupiah', [$salary->potongan_keterlambatan ?? 0]) }}</td>
                    <td>{{ app('formatRupiah', [$salary->potongan_lainnya ?? 0]) }}</td>
                    <td>
                        @if (isset($salary))
                            @php
                                try {
                                    $decryptedSalary = Crypt::decrypt($salary->encrypted_salary);
                                } catch (\Exception $e) {
                                    $decryptedSalary = 'Error decrypting';
                                }
                            @endphp
                            {{ is_numeric($decryptedSalary) ? app('formatRupiah', [$decryptedSalary]) : $decryptedSalary }}
                        @else
                            Belum digaji
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

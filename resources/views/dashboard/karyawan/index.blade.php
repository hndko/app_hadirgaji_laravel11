@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Daftar Karyawan</span>
                            <div class="card-tools">
                                <a href="{{ route('karyawan.create') }}" class="btn btn-sm btn-primary">Tambah Karyawan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <table id="example2" class="table table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawans as $karyawan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($karyawan->photo)
                                                    <img src="{{ asset('photos/' . $karyawan->photo) }}"
                                                        alt="Photo Karyawan" width="50">
                                                @else
                                                    <span class="text-muted">Tidak ada photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $karyawan->nip }}</td>
                                            <td>{{ $karyawan->name }}</td>
                                            <td>{{ $karyawan->email }}</td>
                                            <td>{{ $karyawan->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('karyawan.edit', $karyawan->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah yakin ingin menghapus karyawan ini?')">Hapus</button>
                                                </form>
                                                <a href="{{ route('karyawan.generateQrCode', $karyawan->id) }}"
                                                    class="btn btn-sm btn-info">Generate QR</a>
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
@endsection

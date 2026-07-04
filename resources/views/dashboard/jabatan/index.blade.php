@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Daftar {{ $pages }}</span>
                            <div class="card-tools">
                                <a href="{{ route('data-jabatan.create') }}" class="btn btn-sm btn-primary">Tambah Data</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Jabatan</th>
                                        <th>Gaji Pokok</th>
                                        <th>Tunjangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jabatans as $jabatan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $jabatan->nama_jabatan }}</td>
                                            <td>{{ app('formatRupiah', [$jabatan->gaji_pokok]) }}</td>
                                            <td>{{ app('formatRupiah', [$jabatan->tunjangan]) }}</td>
                                            <td>
                                                <a href="{{ route('data-jabatan.edit', $jabatan->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('data-jabatan.destroy', $jabatan->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah yakin ingin menghapus data?')">Hapus</button>
                                                </form>
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

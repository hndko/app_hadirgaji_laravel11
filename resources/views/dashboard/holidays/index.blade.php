@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Daftar Hari Libur</span>
                            <div class="card-tools">
                                <a href="{{ route('holidays.create') }}" class="btn btn-sm btn-primary">Tambah Hari Libur</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <table id="example2" class="table table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($holidays as $holiday)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ app('formatTanggalIndo', [$holiday->tanggal]) }}</td>
                                            <td>{{ $holiday->keterangan }}</td>
                                            <td>
                                                <a href="{{ route('holidays.edit', $holiday->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah yakin ingin menghapus hari libur ini?')">Hapus</button>
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

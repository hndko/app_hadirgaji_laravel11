@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Daftar Denda Keterlambatan</span>
                            <div class="card-tools">
                                @if ($penalties->isEmpty())
                                    <a href="{{ route('penalties.create') }}" class="btn btn-sm btn-primary">Tambah Denda</a>
                                @endif
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
                                        <th>Jumlah Denda (Rp)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penalties as $penalty)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ app('formatRupiah', [$penalty->jumlah_denda]) }}</td>
                                            <td>
                                                <a href="{{ route('penalties.edit', $penalty->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('penalties.destroy', $penalty->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah yakin ingin menghapus denda ini?')">Hapus</button>
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

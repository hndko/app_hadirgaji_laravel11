@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Daftar Setting Absensi</span>
                            <div class="card-tools">
                                @if ($settings->isEmpty())
                                    <a href="{{ route('absensi-settings.create') }}" class="btn btn-sm btn-primary">Tambah
                                        Setting</a>
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
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Toleransi Keterlambatan (Menit)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $setting)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $setting->jam_masuk }}</td>
                                            <td>{{ $setting->jam_pulang }}</td>
                                            <td>{{ $setting->toleransi_keterlambatan }}</td>
                                            <td>
                                                <a href="{{ route('absensi-settings.edit', $setting->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('absensi-settings.destroy', $setting->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah yakin ingin menghapus setting ini?')">Hapus</button>
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

@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Tambah {{ $pages }}</span>
                            <div class="card-tools">
                                <a href="{{ route('data-jabatan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <form action="{{ route('data-jabatan.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama_jabatan">Nama Jabatan</label>
                                    <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror"
                                        id="nama_jabatan" name="nama_jabatan" value="{{ old('nama_jabatan') }}"
                                        placeholder="Masukkan Nama Jabatan">
                                    @error('nama_jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="gaji_pokok">Gaji Pokok</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('gaji_pokok') is-invalid @enderror" id="gaji_pokok"
                                        name="gaji_pokok" value="{{ old('gaji_pokok') }}" placeholder="Masukkan Gaji Pokok">
                                    @error('gaji_pokok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tunjangan">Tunjangan</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('tunjangan') is-invalid @enderror" id="tunjangan"
                                        name="tunjangan" value="{{ old('tunjangan') }}" placeholder="Masukkan Tunjangan">
                                    @error('tunjangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-info float-right">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

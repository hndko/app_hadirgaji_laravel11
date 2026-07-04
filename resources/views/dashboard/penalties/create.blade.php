@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Tambah Denda Keterlambatan</span>
                            <div class="card-tools">
                                <a href="{{ route('penalties.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <form action="{{ route('penalties.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="jumlah_denda">Jumlah Denda (Rp)</label>
                                    <input type="number" class="form-control @error('jumlah_denda') is-invalid @enderror"
                                        name="jumlah_denda" value="{{ old('jumlah_denda') }}"
                                        placeholder="Masukkan jumlah denda per menit">
                                    @error('jumlah_denda')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-info float-right">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

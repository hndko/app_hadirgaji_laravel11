@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Tambah Setting Absensi</span>
                            <div class="card-tools">
                                <a href="{{ route('absensi-settings.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <form action="{{ route('absensi-settings.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="jam_masuk">Jam Masuk</label>
                                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror"
                                        name="jam_masuk" value="{{ old('jam_masuk') }}">
                                    @error('jam_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jam_pulang">Jam Pulang</label>
                                    <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror"
                                        name="jam_pulang" value="{{ old('jam_pulang') }}">
                                    @error('jam_pulang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="toleransi_keterlambatan">Toleransi Keterlambatan (Menit)</label>
                                    <input type="number"
                                        class="form-control @error('toleransi_keterlambatan') is-invalid @enderror"
                                        name="toleransi_keterlambatan" value="{{ old('toleransi_keterlambatan') }}"
                                        placeholder="Masukkan toleransi keterlambatan">
                                    @error('toleransi_keterlambatan')
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

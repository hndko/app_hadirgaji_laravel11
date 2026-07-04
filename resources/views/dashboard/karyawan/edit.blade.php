@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Edit Karyawan</span>
                            <div class="card-tools">
                                <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                        name="nip" value="{{ old('nip', $karyawan->nip) }}" placeholder="Masukkan NIP">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $karyawan->name) }}"
                                        placeholder="Masukkan Nama Lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $karyawan->email) }}"
                                        placeholder="Masukkan Email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jabatan_id">Jabatan</label>
                                    <select name="jabatan_id"
                                        class="form-control @error('jabatan_id') is-invalid @enderror">
                                        @foreach ($jabatans as $jabatan)
                                            <option value="{{ $jabatan->id }}"
                                                {{ $jabatan->id == $karyawan->jabatan_id ? 'selected' : '' }}>
                                                {{ $jabatan->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror"
                                        name="photo">
                                    @if ($karyawan->photo)
                                        <small class="form-text text-muted">Photo saat ini: <img
                                                src="{{ asset('photos/' . $karyawan->photo) }}" alt="Photo Karyawan"
                                                width="100"></small>
                                    @endif
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-info float-right">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

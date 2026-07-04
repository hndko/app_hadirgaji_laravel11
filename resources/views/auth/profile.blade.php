@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="m-0 h5">Change Profile</span>
                        </div>
                        <form action="{{ route('account.update', $account->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                        name="nip" value="{{ old('nip', $account->nip) }}" placeholder="Masukkan NIP"
                                        readonly>
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $account->name) }}"
                                        placeholder="Masukkan Nama Lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $account->email) }}"
                                        placeholder="Masukkan Email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jabatan_id">Jabatan</label>
                                    <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror"
                                        name="nama_jabatan" value="{{ $account->jabatan->nama_jabatan }}"
                                        placeholder="Masukkan Jabatan" readonly>
                                    @error('nama_jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="Masukkan Password Baru">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror"
                                        name="photo">
                                    @if ($account->photo)
                                        <small class="form-text text-muted">Photo saat ini: <img
                                                src="{{ asset('photos/' . $account->photo) }}" alt="Photo account"
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

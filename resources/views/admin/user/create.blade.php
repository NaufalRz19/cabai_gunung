@extends('admin.layouts.app')
@section('title')
    Tambah Pengguna
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Tambah Pengguna</h4>
                </div>
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" required>
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number">No. Telp</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga">Alamat</label>
                            <textarea name="address" id="address" class="form-control" rows="5" style="height: 100px;">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jenis_cabai">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="admin" {{ old('status') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petani" {{ old('status') === 'petani' ? 'selected' : '' }}>Petani</option>
                                <option value="retail" {{ old('status') === 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="kurir" {{ old('status') === 'kurir' ? 'selected' : '' }}>Kurir</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('users.index') }}" class="btn btn-danger">Kembali</a>
                        <button class="btn btn-success" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection

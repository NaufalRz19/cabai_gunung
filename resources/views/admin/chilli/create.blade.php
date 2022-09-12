@extends('admin.layouts.app')
@section('title')
    Tambah Jenis Cabai
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Tambah Jenis Cabai</h4>
                </div>
                <form action="{{ route('chillis.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="type_of_chilli">Jenis Cabai</label>
                            <input type="text" class="form-control" id="type_of_chilli" name="type_of_chilli" required>
                        </div>
                        <div class="form-group">
                            <label for="fee">Biaya Penjualan</label>
                            <input type="number" class="form-control" id="fee" name="fee" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a type="button" href="{{ route('chillis.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection

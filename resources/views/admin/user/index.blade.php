@extends('admin.layouts.app')
@section('title')
    Pengguna
@endsection
@section('styles')
    <link rel="stylesheet" href="asset_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="asset_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <style>
        .modal-backdrop {
            position: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pengguna</h4>
                    <div class="card-header-action d-flex align-items-center">
                        <a href="{{ route('users.create') }}" class="btn text-white mr-2"
                            style="background-color: #FD5523; width: 200px;">Tambah Data</a>
                        <select name="role" id="role" class="form-control">
                            <option value="">Pilih Status</option>
                            <option value="admin">Admin</option>
                            <option value="petani">Petani</option>
                            <option value="retail">Retail</option>
                            <option value="kurir">Kurir</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Username</th>
                                    <th>No. Telp</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            {{ $user->phone_number }}
                                        </td>
                                        <td>
                                            {{ $user->address }}
                                        </td>
                                        <td>
                                            {{ strtoupper($user->status) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btnHapus"
                                                data-url="{{ route('users.destroy', $user->id) }}"><i
                                                    class="fas fa-trash"></i></a>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin menghapus data ini?</p>
                </div>
                <form action="" method="post" id="frmDeleteUser">
                    @csrf
                    @method('DELETE')
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="asset_modules/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="asset_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="asset_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js"></script>
    <script src="asset_modules/prismjs/prism.js"></script>
    <script>
        $("#table-1").dataTable();
        $(".btnHapus").click(function(e) {
            e.preventDefault();
            $('#exampleModal').modal('show');
            $('.modal-backdrop').removeClass('modal-backdrop');
            $('#frmDeleteUser').attr('action', $(this).data('url'));
        });
        $('#role').on('change', function() {
            var role = $(this).val();
            window.location.href = '/users?status=' + role;
        });
    </script>
@endsection

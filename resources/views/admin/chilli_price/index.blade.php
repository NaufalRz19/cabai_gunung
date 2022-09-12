@extends('admin.layouts.app')
@section('title')
    Harga Cabai
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
                <div class="card-header d-flex justify-content-between">
                    <h4>Data Harga Cabai</h4>
                    <a href="{{ route('chilli-price.create') }}" class="btn text-white"
                        style="background-color: #FD5523;">Tambah Data</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Cabai</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($chilliPrices as $index => $item)
                                    <tr>
                                        <td>
                                            {{ $i++ }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($index." 00:00:00")->formatLocalized('%d %B %Y') }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($item as $item2)
                                                    <li>{{ $item2->chilli->type_of_chilli }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($item as $item2)
                                                    <li>{{ 'Rp ' . number_format($item2->price, 2, ',', '.') }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{ route('chilli-price.edit', $index) }}" class="btn btn-warning"><i
                                                    class="fas fa-edit"></i></a>
                                            {{-- <a href="#" data-url="{{ route('chilli-price.destroy', $index) }}" class="btn btn-danger" name="btnHapus"><i
                                                    class="fas fa-trash"></i></a> --}}
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
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="" method="post" id="frmDelete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
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
        // $("a[name=btnHapus]").click(function(e) {
        //     e.preventDefault();
        //     $('#exampleModal').modal('show');
        //     $('#frmDelete').attr('action', $(this).data('url'));
        //     $('.modal-backdrop').removeClass('modal-backdrop');
        // });
    </script>
@endsection

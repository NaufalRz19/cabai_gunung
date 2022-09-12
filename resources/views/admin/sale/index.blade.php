@extends('admin.layouts.app')
@section('title')
    Penjualan
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
                    <h4>Data Penjualan</h4>
                    <div class="card-header-action d-flex align-items-center">
                        <a href="{{ route('sales.create') }}" class="btn text-white mr-2"
                            style="background-color: #FD5523; width: 200px;">Tambah Data</a>
                        <form action="{{ route('print_sale_by_date') }}" method="post" class="form-inline">
                            @csrf
                            <input type="date" name="date" class="form-control mr-2" placeholder="Pilih Tanggal">
                            <button type="submit" class="btn text-white" style="background-color: #FD5523;">Cetak</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>No. Transaksi</th>
                                    <th>Nama Retail</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $item)
                                    <tr>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            {{ $item->sales_number }}
                                        </td>
                                        <td>
                                            {{ $item->user->name }}
                                        </td>
                                        <td>
                                            {{ 'Rp ' . number_format(round($item->sales_total / 1000, 0) * 1000) }}
                                        </td>
                                        <td>
                                            {!! $item->is_success == 1
                                                ? '<span class="badge badge-success">Selesai</span>'
                                                : '<span class="badge badge-danger">Perjalanan</span>' !!}
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary btnDetail"
                                                data-url="{{ route('json_sale', $item->id) }}"><i
                                                    class="fas fa-book"></i></a>
                                            <a href="{{ route('sales.edit', $item->id) }}" class="btn btn-warning"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" data-url="{{ route('sales.destroy', $item->id) }}"
                                                class="btn btn-danger btnHapus"><i class="fas fa-trash"></i></a>
                                            @if (!$item->is_success && count($item->user->location) > 0)
                                                <a target="_blank"
                                                    href="https://www.google.com/maps/search/?api=1&query={{ $item->user->location[0]->latitude }},{{ $item->user->location[0]->longitude }}"
                                                    class="btn btn-info"><i class="fas fa-map-marker-alt"></i></a>
                                            @endif
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
                    <form action="" method="post" id="frmDeleteSale">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalCetak">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><span class="text-center">No. Transaksi : </span><span id="sale_number"></span></center>
                    <br>
                    <center><span class="text-center" id="created_at"></span></center>
                    <br>
                    <br>
                    <h4 id="user_name"></h4>
                    <span id="user_phone_number"></span>
                    <p id="user_address"></p>
                    <hr>
                    <div class="detail-sale"></div>
                    <hr>
                    <table class="table" style="border: none;">
                        <tr>
                            <td>Total</td>
                            <td class="text-right" id="total"></td>
                        </tr>
                        <tr>
                            <td>Pembulatan</td>
                            <td class="text-right" id="pembulatan"></td>
                        </tr>
                    </table>
                    <hr>
                    <table class="table" style="border: none;">
                        <tr>
                            <td>Total Transaksi</td>
                            <td class="text-right" id="total_transaction"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="btnPrint" data-id="" class="btn btn-success"><i class="fas fa-print">
                            Cetak</i></button>
                    <button id="btnBukti" data-id="" class="btn btn-info">Bukti Transaksi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalBukti">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

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
        let buktiTransfer = [];
        $("#table-1").dataTable();
        $(".btnHapus").click(function(e) {
            e.preventDefault();
            $('#exampleModal').modal('show');
            $('.modal-backdrop').removeClass('modal-backdrop');
            $('#frmDeleteSale').attr('action', $(this).data('url'));
        });
        $(".btnDetail").click(function(e) {
            e.preventDefault();
            axios.get($(this).data('url'))
                .then(res => {
                    buktiTransfer = [];
                    $('#sale_number').html(res.data.sales_number);
                    $('#created_at').html(res.data.created_at);
                    $('#user_name').html(res.data.user.name);
                    $('#user_phone_number').html(res.data.user.phone_number);
                    $('#user_address').html(res.data.user.address);
                    let total = 0;
                    let detailSale = '';
                    const packingPrice = 2500;
                    for (obj of res.data.sale_detail) {
                        const totalObj = obj.total * (obj.chilli.chilli_price[0].price + obj.chilli.fee + packingPrice);
                        total += totalObj;
                        detailSale += obj.chilli.type_of_chilli
                        detailSale += `<br>
                    <table class="table" style="border: none;">
                        <tr>
                            <td>` + obj.total + `</td>
                            <td>` + obj.chilli.chilli_price[0].price + `</td>
                            <td>` + (obj.selling_price + packingPrice) + ` /kg</td>
                            <td>` + totalObj + `</td>
                        </tr>
                    </table>
                `;
                    }
                    for (obj of res.data.proof_of_sale) {
                        $('#modalBukti .modal-body').append('<img src="' + obj.full_image_url +
                            '" style="width: 100%;">');
                    }

                    const finalTotal = Math.round(total / 1000) * 1000;
                    const pembulatan = finalTotal - total;
                    $('.detail-sale').html(detailSale);
                    $('#total').html(total)
                    $('#pembulatan').html(pembulatan)
                    $('#total_transaction').html(finalTotal)
                    $('#modalCetak').modal('show');
                    $('.modal-backdrop').removeClass('modal-backdrop');
                    $('#btnPrint').data('id', res.data.id);
                })
                .catch(err => {
                    console.error(err);
                })
        });
        $('#btnPrint').click(function(e) {
            e.preventDefault();
            window.open('{{ url('/') }}/sale/' + $(this).data('id') + '/print', '_blank');
        });
        $("#btnBukti").click(function(e) {
            e.preventDefault();
            $('#modalBukti').modal('show');
            $('.modal-backdrop').removeClass('modal-backdrop');
        });
    </script>
@endsection

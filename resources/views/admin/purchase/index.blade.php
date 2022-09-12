@extends('admin.layouts.app')
@section('title')
    Pembelian
@endsection
@section('styles')
    <link rel="stylesheet" href="asset_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="asset_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <style>
        .modal-backdrop{
            position: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pembelian</h4>
                    <div class="card-header-action d-flex align-items-center">
                        <a href="{{ route('purchases.create') }}" class="btn text-white mr-2" style="background-color: #FD5523; width: 200px;">Tambah Data</a>
                        <form action="{{ route('print_purchase_by_date') }}" method="post" class="form-inline">
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
                                    <th>Nama Petani</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $index => $item)
                                <tr>
                                    <td>
                                        {{ $index+1 }}
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        {{ $item->purchase_number }}
                                    </td>
                                    <td>
                                        {{ $item->user->name }}
                                    </td>
                                    <td>
                                        {{ strtoupper($item->payment_method) }}
                                    </td>
                                    <td>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($item->purchaseDetail as $detail)
                                        @php
                                            $total += $detail->chilliPrice->price*($detail->healthy_amount_of_chilies - $detail->number_of_damaged_chilies);
                                        @endphp
                                        @endforeach
                                        {{ 'Rp ' . number_format(round($total / 1000, 0) * 1000) }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary btnDetail" data-url="{{ route('json_purchase', $item->id) }}"><i class="fas fa-book"></i></a>
                                        <a href="{{ route('purchases.edit', $item->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="#" data-url="{{ route('purchases.destroy', $item->id) }}" class="btn btn-danger btnHapus"><i class="fas fa-trash"></i></a>
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
                    <form action="" method="post" id="frmDeletePurchase">
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
                    <center><span class="text-center">No. Transaksi : </span><span id="purchase_number"></span></center>
                    <br>
                    <center><span class="text-center" id="created_at"></span></center>
                    <br>
                    <br>
                    <h4 id="user_name"></h4>
                    <span id="user_phone_number"></span>
                    <p id="user_address"></p>
                    <hr>
                    <div class="detail-purchase"></div>
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
                    <button id="btnPrint" data-id="" class="btn btn-success"><i class="fas fa-print"> Cetak</i></button>
                    <button id="btnBukti" data-id="" class="btn btn-success"><i class="fas fa-print"> Lihat Bukti</i></button>
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
        $("#table-1").dataTable();
        $(".btnHapus").click(function (e) {
            e.preventDefault();
            $('#exampleModal').modal('show');
            $('.modal-backdrop').removeClass('modal-backdrop');
            $('#frmDeletePurchase').attr('action', $(this).data('url'));
        });
        $(".btnDetail").click(function (e) {
            e.preventDefault();
            axios.get($(this).data('url'))
            .then(res => {
                $('#purchase_number').html(res.data.purchase_number);
                $('#created_at').html(res.data.created_at);
                $('#user_name').html(res.data.user.name);
                $('#user_phone_number').html(res.data.user.phone_number);
                $('#user_address').html(res.data.user.address);
                let total = 0;
                let detailPurchase = '';
                for(obj of res.data.purchase_detail){
                    total += (obj.healthy_amount_of_chilies - obj.number_of_damaged_chilies)*obj.chilli_price.price;
                    detailPurchase+=obj.chilli_price.chilli.type_of_chilli
                    detailPurchase+=`<br>
                    <table class="table" style="border: none;">
                        <tr>
                            <td>`+obj.healthy_amount_of_chilies+`</td>
                            <td>`+obj.number_of_damaged_chilies+`</td>
                            <td>`+(obj.healthy_amount_of_chilies-obj.number_of_damaged_chilies)+`</td>
                            <td>`+obj.chilli_price.price+`</td>
                            <td>`+((obj.healthy_amount_of_chilies-obj.number_of_damaged_chilies)*obj.chilli_price.price)+`</td>
                        </tr>
                    </table>
                `;
                }
                for(obj of res.data.proof_of_purchase){
                    $('#modalBukti .modal-body').append('<img src="'+obj.full_image_url+'" style="width: 100%;">');
                }

                const finalTotal = Math.round(total / 1000) * 1000;
                const pembulatan = finalTotal - total;
                $('.detail-purchase').html(detailPurchase);
                $('#total').html(total)
                $('#pembulatan').html(pembulatan);
                $('#total_transaction').html(finalTotal)
                $('#modalCetak').modal('show');
                $('.modal-backdrop').removeClass('modal-backdrop');
                $('#btnPrint').data('id',res.data.id);
            })
            .catch(err => {
                console.error(err);
            })
        });
        $('#btnPrint').click(function (e) {
            e.preventDefault();
            window.open('{{url('/')}}/purchase/'+$(this).data('id')+'/print','_blank');
        });
        $("#btnBukti").click(function (e) {
            e.preventDefault();
            $('#modalBukti').modal('show');
            $('.modal-backdrop').removeClass('modal-backdrop');
        });
    </script>
@endsection

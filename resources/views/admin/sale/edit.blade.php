@extends('admin.layouts.app')
@section('title')
    Ubah Penjualan
@endsection
@section('content')
    <div class="row">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Ubah Penjualan</h4>
                </div>
                <form action="{{ route('sales.update', $sale->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Nama Retail</label>
                            <select name="user_id" id="user_id" class="form-control" disabled>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}" {{ $item->id === $sale->user_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sales_number">Nomor Penjualan</label>
                            <input type="text" class="form-control" id="sales_number" name="sales_number" value="{{ $sale->sales_number }}" disabled>
                        </div>
                        <div class="jenis-cabai">
                            @foreach ($sale->saleDetail as $index => $detailSale)
                            <div class="data barisCabai{{ $index }}">
                                <div class="form-group">
                                    <label for="chilli_id">Jenis Cabai</label>
                                    <select name="chilli_id[]" class="form-control chilli col-10" data-id="0" disabled>
                                        <option value="">Pilih Cabai</option>
                                        @foreach ($chillis as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $detailSale->chilli_id ? 'selected' : '' }}>{{ $item->type_of_chilli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="selling_price">Harga Jual</label>
                                    <input type="number" name="selling_price[]" class="form-control selling_price{{ $index }}" value="{{ $detailSale->selling_price }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="total">Jumlah Cabai</label>
                                    <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                                        <div class="input-group mr-2">
                                            <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="total[]" class="col-md-8 form-control" value="{{ $detailSale->total }}" disabled>
                                            <span class="input-group-text"> kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @foreach ($sale->proofOfSale as $indexProof => $itemProof)
                        <div class="form-group">
                            <label for="image_url">Bukti Pembayaran {{ $indexProof+1 }}</label><br>
                            <img class="img-fluid mt-2" src="/storage/sale/{{ $itemProof->image_url }}" alt="" srcset="" style="max-height: 150px;">
                        </div>
                        @endforeach
                        {{-- <div class="proof barisProof0">
                            <div class="form-group">
                                <label for="image_url">Bukti Pembayaran Baru</label>
                                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                                    <input type="file" name="image_url[]" class="mr-2 col-md-8 form-control">
                                    <div class="col-md-3">
                                        <button type="button" name="btnPushProof" id="btnPushProof0"  data-id="0" class="btn btn-primary">Tambah</button>
                                        <button type="button" name="btnPullProof" id="btnPullProof0"  data-id="0" class="btn btn-danger">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="is_success">Status Penjualan</label>
                            <select name="is_success" id="is_success" class="form-control" required>
                                <option value="0" {{ $sale->is_success == 0 ? 'selected' : '' }}>Proses</option>
                                <option value="1" {{ $sale->is_success == 1 ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a type="button" href="{{ route('sales.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        showLastBtnPush();
        showLastBtnPushProof()
    });
    function countData(){
        const count = $('.data').length;
        return count;
    }
    function countDataProof(){
        const count = $('.proof').length;
        return count;
    }
    function showLastBtnPush(){
        const count = countData();
        const last = count - 1;
        $('.data').each(function(index, element){
            if(index == last){
                $(this).find('button[name=btnPush]').css({'display': ''});
                if(last !== 0){
                    $(this).find('button[name=btnPull]').css({'display': ''});
                }else{
                    $(this).find('button[name=btnPull]').attr('style','display:none !important');
                }
            }
        });
    }
    function showLastBtnPushProof(){
        const count = countDataProof();
        const last = count - 1;
        $('.proof').each(function(index, element){
            if(index == last){
                $(this).find('button[name=btnPushProof]').css({'display': ''});
                if(last !== 0){
                    $(this).find('button[name=btnPullProof]').css({'display': ''});
                }else{
                    $(this).find('button[name=btnPullProof]').attr('style','display:none !important');
                }
            }
        });
    }
    $('.jenis-cabai').on('click', 'button[name=btnPush]',function(e){
        e.preventDefault();
        const index = $(this).data('id');
        const newIndex = parseInt(index)+1;
        $('.jenis-cabai').append(`
        <div class="data barisCabai`+newIndex+`">
            <hr>
            <div class="form-group">
                <label for="chilli_id">Jenis Cabai</label>
                <select name="chilli_id[]" class="form-control chilli col-10" data-id="`+newIndex+`" required>
                    <option value="">Pilih Cabai</option>
                    @foreach ($chillis as $item)
                        <option value="{{ $item->id }}">{{ $item->type_of_chilli }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="selling_price">Harga Jual</label>
                <input type="number" name="selling_price[]" class="form-control selling_price`+newIndex+`" readonly>
            </div>
            <div class="form-group">
                <label for="total">Jumlah Cabai</label>
                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                    <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="total[]" class="mr-2 col-md-8 form-control" required>
                    <div class="col-md-3">
                        <button type="button" name="btnPush" id="btnPush`+newIndex+`"  data-id="`+newIndex+`" class="btn btn-primary">Tambah</button>
                        <button type="button" name="btnPull" id="btnPull`+newIndex+`"  data-id="`+newIndex+`" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        `);
        $('#btnPull'+index).attr('style','display:block !important');
        $('#btnPush'+index).attr('style','display:none !important');
    });
    $('.jenis-cabai').on('click', 'button[name=btnPull]',function(e){
        e.preventDefault();
        console.log(e);
        const index = $(this).data('id');
        $('.barisCabai'+index).remove();
        showLastBtnPush();
    });
    $('.card-body').on('click', 'button[name=btnPushProof]',function(e){
        e.preventDefault();
        const index = $(this).data('id');
        const newIndex = parseInt(index)+1;
        $('.card-body').append(`
        <div class="proof barisProof`+newIndex+`">
            <hr>
            <div class="form-group">
                <label for="image_url">Bukti Pembayaran</label>
                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                    <input type="file" name="image_url[]" class="mr-2 col-md-8 form-control" required>
                    <div class="col-md-3">
                        <button type="button" name="btnPushProof" id="btnPushProof`+newIndex+`"  data-id="`+newIndex+`" class="btn btn-primary">Tambah</button>
                        <button type="button" name="btnPullProof" id="btnPullProof`+newIndex+`"  data-id="`+newIndex+`" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        `);
        $('#btnPullProof'+index).attr('style','display:block !important');
        $('#btnPushProof'+index).attr('style','display:none !important');
    });
    $('.card-body').on('click', 'button[name=btnPullProof]',function(e){
        e.preventDefault();
        const index = $(this).data('id');
        $('.barisProof'+index).remove();
        showLastBtnPushProof();
    });
    $('.jenis-cabai').on('change', '.chilli', function(){
        let index = $(this).data('id');
        let id = $(this).val();
        let url = "{{ route('chilli.getPrice', ':id') }}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                $('.selling_price'+index).val(data);
            }
        });
    });

</script>
@endsection

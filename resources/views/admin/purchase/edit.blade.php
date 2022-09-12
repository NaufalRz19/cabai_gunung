@extends('admin.layouts.app')
@section('title')
    Edit Pembelian
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
                    <h4>Edit Pembelian</h4>
                </div>
                <form action="{{ route('purchases.update', $purchase->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Nama Petani</label>
                            <select name="user_id" id="user_id" class="form-control" disabled>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}" {{ $item->id === $purchase->user_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="purchase_number">Nomor Pembelian</label>
                            <input type="integer" class="form-control" id="purchase_number" name="purchase_number" value="{{ $purchase->purchase_number }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control" disabled>
                                <option value="cod" {{ $purchase->payment_method === 'cod' ? 'selected' : '' }}>Pembayaran Ditempat</option>
                                <option value="transfer" {{ $purchase->payment_method === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                        </div>
                        <div class="jenis-cabai">
                            @foreach ($purchase->purchaseDetail as $indexPDetail => $pdetail)
                            <div class="data barisCabai{{ $indexPDetail }}">
                                <div class="form-group">
                                    <label for="chilli_price_id">Jenis Cabai</label>
                                    <select name="chilli_price_id[]" class="form-control col-10" disabled>
                                        @foreach ($chilliPrices as $item)
                                            <option value="{{ $item->id }}" {{ $item->id === $pdetail->chilli_price_id ? 'selected' : '' }}>{{ $item->created_at.' | '.$item->chilli->type_of_chilli.' | '.$item->price }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="healthy_amount_of_chilies">Jumlah Cabai Sehat</label>
                                    <div class="input-group">
                                        <input type="number" name="healthy_amount_of_chilies[]" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" class="form-control" value="{{ $pdetail->healthy_amount_of_chilies }}" disabled>
                                        <span class="input-group-text"> kg</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="number_of_damaged_chilies">Jumlah Cabai Rusak</label>
                                    <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                                        <div class="input-group mr-2">
                                            <input type="number" name="number_of_damaged_chilies[]" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" class="col-md-8 form-control" value="{{ $pdetail->number_of_damaged_chilies }}" disabled>
                                            <span class="input-group-text"> kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @foreach ($purchase->proofOfPurchase as $indexProof => $itemProof)
                        <div class="form-group">
                            <label for="image_url">Bukti Pembayaran {{ $indexProof+1 }}</label><br>
                            <img class="img-fluid mt-2" src="/storage/purchase/{{ $itemProof->image_url }}" alt="" srcset="" style="max-height: 150px;">
                        </div>
                        @endforeach
                        <div class="proof barisProof0">
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
                        </div>
                    </div>
                    <div class="card-footer">
                        <a type="button" href="{{ route('purchases.index') }}" class="btn btn-danger">Kembali</a>
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
                <label for="chilli_price_id">Jenis Cabai</label>
                <select name="chilli_price_id[]" class="form-control col-10" required>
                    @foreach ($chilliPrices as $item)
                        <option value="{{ $item->id }}">{{ $item->created_at.' | '.$item->chilli->type_of_chilli.' | '.$item->price }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="healthy_amount_of_chilies">Jumlah Cabai Sehat</label>
                <div class="input-group">
                    <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="healthy_amount_of_chilies[]" class="form-control" formnovalidate="formnovalidate" required>
                    <span class="input-group-text"> kg</span>
                </div>
            </div>
            <div class="form-group">
                <label for="number_of_damaged_chilies">Jumlah Cabai Rusak</label>
                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                    <div class="input-group mr-2">
                        <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="number_of_damaged_chilies[]" class="col-md-8 form-control" formnovalidate="formnovalidate" required>
                        <span class="input-group-text"> kg</span>
                    </div>
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
                    <input type="file" name="image_url[]" class="mr-2 col-md-8 form-control">
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

</script>
@endsection

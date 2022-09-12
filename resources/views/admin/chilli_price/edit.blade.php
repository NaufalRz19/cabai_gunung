@extends('admin.layouts.app')
@section('title')
    Ubah Harga Cabai
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Ubah Harga Cabai - {{ $date }}</h4>
                </div>
                <form action="{{ route('chilli-price.update', $date) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @foreach ($chilliPrices as $index => $item)
                        <div class="data baris{{ $index }}">
                            <div class="form-group">
                                <label for="chilli_id">Jenis Cabai</label>
                                <select name="chilli_id[]" class="form-control col-10" required>
                                    @foreach ($chillis as $itemChilli)
                                        <option value="{{ $itemChilli->id }}" {{ $itemChilli->id === $item->chilli_id ? 'selected' : '' }}>{{ $itemChilli->type_of_chilli }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                                    <input type="number" name="price[]" class="mr-2 col-md-8 form-control" value="{{ $item->price }}" required>
                                    <div class="col-md-3">
                                        <button type="button" name="btnPush" id="btnPush{{ $index }}"  data-id="{{ $index }}" class="btn btn-primary" style="display: none !important">Tambah</button>
                                        <button type="button" name="btnPull" id="btnPull{{ $index }}"  data-id="{{ $index }}" class="btn btn-danger">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <a type="button" href="{{ route('chilli-price.index') }}" class="btn btn-danger">Kembali</a>
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
    });
    function countData(){
        const count = $('.data').length;
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
    $('.card-body').on('click', 'button[name=btnPush]',function(e){
        e.preventDefault();
        const index = $(this).data('id');
        const newIndex = parseInt(index)+1;
        $('.card-body').append(`
        <div class="data baris`+newIndex+`">
            <hr>
            <div class="form-group">
                <label for="chilli_id">Jenis Cabai</label>
                <select name="chilli_id[]" class="form-control col-10" required>
                    @foreach ($chillis as $item)
                        <option value="{{ $item->id }}">{{ $item->type_of_chilli }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="price">Harga</label>
                <div class="row" style="margin-left: 0 !important; margin-right: 0 !important;">
                    <input type="number" name="price[]" class="mr-2 col-md-8 form-control" required>
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
    $('.card-body').on('click', 'button[name=btnPull]',function(e){
        e.preventDefault();
        const index = $(this).data('id');
        $('.baris'+index).remove();
        showLastBtnPush();
    });

</script>
@endsection

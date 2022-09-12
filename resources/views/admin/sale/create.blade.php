@extends('admin.layouts.app')
@section('title')
    Tambah Pembelian
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
                    <h4>Tambah Penjualan</h4>
                </div>
                <form action="{{ route('sales.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Nama Retail</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sales_number">Pilih Lokasi</label>
                            <div id="map"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="lat" name="lat" required value="{{old('alamat')}}" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="long" name="long" required value="{{old('alamat')}}" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label for="description">Alamat Vendor</label>
                            <textarea type="text" class="form-control" id="alamat" name="alamat" required value="{{old('alamat')}}"></textarea>
                        </div> --}}
                        <div class="jenis-cabai">
                            <div class="data barisCabai0">
                                <div class="form-group">
                                    <label for="chilli_id">Jenis Cabai</label>
                                    <select name="chilli_id[]" class="form-control chilli col-10" data-id="0" required>
                                        <option value="">Pilih Cabai</option>
                                        @foreach ($chillis as $item)
                                            <option value="{{ $item->id }}">{{ $item->type_of_chilli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="selling_price">Harga Jual</label>
                                    <input type="number" name="selling_price[]" class="form-control selling_price0" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="total">Jumlah Cabai</label>
                                    <div class="d-flex flex-row" style="margin-left: 0 !important; margin-right: 0 !important;">
                                        <div class="input-group col-md-8 mr-2">
                                            <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="total[]" class="form-control" required>
                                            <span class="input-group-text"> kg</span>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" name="btnPush" id="btnPush0"  data-id="0" class="btn btn-primary">Tambah</button>
                                            <button type="button" name="btnPull" id="btnPull0"  data-id="0" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_success">Status Penjualan</label>
                            <select name="is_success" id="is_success" class="form-control" required>
                                <option value="0">Proses</option>
                                <option value="1">Selesai</option>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
  #map { height: 500px; }
</style>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
   <script>
 
    var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW5qYXJhbmFzMjEyIiwiYSI6ImNrb29hMzJrbjA3emwyb3VpMnowamVqZGIifQ._hJ8hlsIsEfa58seZ0s8Lg', {
    
    id: 'mapbox/streets-v11'
  });

  var peta2 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW5qYXJhbmFzMjEyIiwiYSI6ImNrb29hMzJrbjA3emwyb3VpMnowamVqZGIifQ._hJ8hlsIsEfa58seZ0s8Lg', {
    
    id: 'mapbox/satellite-v9'
  });


  var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    
  });

  var peta4 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=ppk.eyJ1IjoiYW5qYXJhbmFzMjEyIiwiYSI6ImNrb29hMzJrbjA3emwyb3VpMnowamVqZGIifQ._hJ8hlsIsEfa58seZ0s8Lg', {
    
    id: 'mapbox/dark-v10'
  });
  
  
  var map = L.map('map', {
    center: [-0.2471269, 121.3865129],
    zoom: 5,
    layers: [peta1
      ]
  });
  var baseMaps = {
    "Grayscale": peta1,
    "Streets": peta3,
    "Dark" : peta4,
    "Satellite": peta2
  };
  L.control.layers(baseMaps).addTo(map);
  var marker;
  map.on('click', function(e) {
    
    if(marker)
        map.removeLayer(marker);
        
    //console.log(e.latlng.lat); // e is an event object (MouseEvent in this case)
    var lat=document.getElementById("lat");
    var long=document.getElementById("long");
    // var alamat=document.getElementById("alamat");
    var lathasil=e.latlng.lat;
    var longhasil=e.latlng.lng;
    //console.log(e.latlng);
    // var rep=lathasil.replace("L","")
    lat.value=lathasil;
    long.value=longhasil;
    // alamat.value=
    marker = L.marker(e.latlng).addTo(map);
    // $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lathasil+'&lon='+longhasil, function(data){
    //   console.log(data.display_name);
    //   alamat.value=data.display_name;
    // });
    
  });
  L.Control.geocoder().addTo(map);
  </script>
<script>
    $(document).ready(function(){
        $('#btnPull0').hide();
        $('#btnPullProof0').hide();
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
                <div class="d-flex flex-row" style="margin-left: 0 !important; margin-right: 0 !important;">
                    <div class="input-group col-md-8 mr-2">
                        <input type="number" pattern="[0-9]+([,\.][0-9]+)?" step="0.01" name="total[]" class="form-control" required>
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

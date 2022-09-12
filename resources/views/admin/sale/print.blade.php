Detail Transaksi
<center><span class="text-center">No. Transaksi : </span>{{ $sale->sales_number }}</center>
<br>
<center><span class="text-center">{{ $sale->created_at }}</span></center>
<br>
<br>
<h4 id="user_name" style="font-size: 20px;">{{ $sale->user->name }}</h4>
<span id="user_phone_number">{{ $sale->user->phone_number }}</span>
<p id="user_address">{{ $sale->user->address }}</p>
<hr>
@php
$total = 0;
$packingPrice = 2500;
@endphp
<table style="border: none; width: 100%;">
    <tr>
        <th style="width: 20%; text-align: left;">Jenis Cabai</th>
        <th style="width: 15%; text-align: center;">Total Cabai (Kg)</th>
        <th style="width: 15%; text-align: center;">Harga Dasar</th>
        <th style="width: 15%; text-align: center;">Harga Jual</th>
        <th style="width: 20%; text-align: right;">Total Harga</th>
    </tr>
    @foreach ($sale->saleDetail as $item)
        @php
            $calcTotal = $item->total * ($item->chilli->chilliPrice[0]->price + $item->chilli->fee + $packingPrice);
            $total += $calcTotal;
        @endphp
        <tr>
            <td style="width: 20%; text-align: left;">{{ $item->chilli->type_of_chilli }} </td>
            <td style="width: 15%; text-align: center;">{{ $item->total }}</td>
            <td style="width: 15%; text-align: center;">{{ 'Rp ' . number_format($item->chilli->chilliPrice[0]->price, 2, ',', '.') }}</td>
            <td style="width: 15%; text-align: center;">{{ 'Rp ' . number_format(($item->selling_price + $packingPrice), 2, ',', '.') }}</td>
            <td style="width: 20%; text-align: right;">{{ 'Rp ' . number_format($calcTotal, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    @php
        $finalTotal = round($total / 1000, 0) * 1000;
        $pembulatan = $finalTotal - $total;
    @endphp
</table>
<hr>
<table class="table" style="border: none; width: 100%;">
    <tr>
        <td style="width: 50%">Total</td>
        <td style="width: 50%; text-align: right;" class="text-right" id="total">{{ 'Rp ' . number_format($total, 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td style="width: 50%;">Pembulatan</td>
        <td style="width: 50%; text-align: right;" class="text-right" id="pembulatan">{{ 'Rp ' . number_format($pembulatan, 2, ',', '.') }}</td>
    </tr>
</table>
<hr>
<table class="table" style="border: none;width:100%">
    <tr>
        <td style="width: 50%;">Total Transaksi</td>
        <td class="text-right" id="total_transaction" style="width: 50%; text-align:right;">{{ 'Rp ' . number_format($finalTotal, 2, ',', '.') }}</td>
    </tr>
</table>

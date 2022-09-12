@foreach ($purchases as $purchase)
    <div style="height: 100%;">
        Detail Transaksi
        <center><span class="text-center">No. Transaksi : </span>{{ $purchase->purchase_number }}</center>
        <br>
        <center><span class="text-center">{{ $purchase->created_at }}</span></center>
        <br>
        <br>
        <h4 id="user_name" style="font-size: 20px;">{{ $purchase->user->name }}</h4>
        <span id="user_phone_number">{{ $purchase->user->phone_number }}</span>
        <p id="user_address">{{ $purchase->user->address }}</p>
        <hr>
        @php
            $total = 0;
        @endphp
        <table style="border: none; width: 100%;">
            <tr>
                <th style="width: 20%; text-align: left;">Jenis Cabai</th>
                <th style="width: 15%; text-align: center;">Cabai Sehat</th>
                <th style="width: 15%; text-align: center;">Cabai Rusak</th>
                <th style="width: 15%; text-align: center;">Total Cabai</th>
                <th style="width: 15%; text-align: center;">Harga Satuan</th>
                <th style="width: 20%; text-align: right;">Total Harga</th>
            </tr>
            @foreach ($purchase->purchaseDetail as $item)
                @php
                    $total += $item->chilliPrice->price * $item->healthy_amount_of_chilies;
                @endphp
                <tr>
                    <td style="width: 20%; text-align: left;">{{ $item->chilliPrice->chilli->type_of_chilli }} </td>
                    <td style="width: 15%; text-align: center;">{{ $item->healthy_amount_of_chilies }}</td>
                    <td style="width: 15%; text-align: center;">{{ $item->number_of_damaged_chilies }}</td>
                    <td style="width: 15%; text-align: center;">
                        {{ $item->healthy_amount_of_chilies + $item->number_of_damaged_chilies }}</td>
                    <td style="width: 15%; text-align: center;">
                        {{ 'Rp ' . number_format($item->chilliPrice->price, 2, ',', '.') }}</td>
                    <td style="width: 20%; text-align: right;">
                        {{ 'Rp ' . number_format($item->healthy_amount_of_chilies * $item->chilliPrice->price, 2, ',', '.') }}
                    </td>
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
                <td style="width: 50%; text-align: right;" class="text-right" id="total">
                    {{ 'Rp ' . number_format($total, 2, ',', '.') }}</td>
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
                <td class="text-right" id="total_transaction" style="width: 50%; text-align:right;">
                    {{ 'Rp ' . number_format($finalTotal, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
@endforeach

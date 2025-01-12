<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        table.bordered {
            border-collapse: collapse;
        }

        table.bordered th,
        table.bordered td {
            border: 1px solid #000;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>

<body>

    <table width="100%">
        <tr>
            {{-- <td valign="top"><img src="{{ $outlet->image_path }}" width="80" height="80" /></td> --}}
            <td align="right">
                <h3>{{ getCompanyInfo()->name }}</h3>
                <pre>
              {{ $outlet->name }}
              {{ $outlet->address }}
              {{ $outlet->phone_number }}
            </pre>
            </td>
        </tr>

    </table>

    {{-- <table width="100%">
        <tr>
            <td><strong>From:</strong> Linblum - Barrio teatral</td>
            <td><strong>To:</strong> Linblum - Barrio Comercial</td>
        </tr>
    </table> --}}

    <table width="100%">
        <tr>
            <td>Laporan Pendapatan: {{ $validatedData['start_date'] }} s/d {{ $validatedData['end_date'] }}</td>
        </tr>
    </table>

    <table class="bordered" width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th rowspan="2" width="3%">#</th>
                <th rowspan="2">ID Pesanan</th>
                <th rowspan="2">Oleh</th>
                <th align="center" colspan="6">Item</th>
                <th rowspan="2">Tgl Pemesanan</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th colspan="2">Name</th>
                <th>Catatan</th>
                <th width="1%">Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td scope="row" align="center" rowspan="{{ count($order->items) }}">{{ $index + 1 }}</td>
                    <td rowspan="{{ count($order->items) }}">
                        {{ $order->id }}
                    </td>
                    <td align="center" rowspan="{{ count($order->items) }}">{{ $order->user->name }}</td>
                    @foreach ($order->items as $itemIndex => $item)
                        @if ($itemIndex > 0)
                </tr>
                <tr>
            @endif
            <td align="center" width="2%">{{ $itemIndex + 1 }}</td>
            <td>{{ $item->menu->name }}</td>
            <td>{!! strip_tags($item->note) ? $item->note : '-' !!}</td>
            <td align="center" width="3%">{{ $item->quantity }}</td>
            <td>{{ formatRupiah($item->price) }}</td>
            <td>{{ formatRupiah($item->quantity * $item->price) }}</td>
            @if ($itemIndex == 0)
                <td rowspan="{{ count($order->items) }}">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('d F Y H:i') }}
                </td>
                <td align="center" rowspan="{{ count($order->items) }}">
                    {{ formatRupiah($order->total) }}
                </td>
            @endif
            @endforeach
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="no-border"></td>
                <td colspan="2" align="right">Total</td>
                <td align="right" class="gray">
                    {{ formatRupiah($orders->sum(function ($order) {return $order->items->sum(function ($item) {return $item->quantity * $item->price;});})) }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>

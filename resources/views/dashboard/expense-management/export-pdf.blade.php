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
            <td>Laporan Pengeluaran: {{ $validatedData['start_date'] }} s/d {{ $validatedData['end_date'] }}</td>
        </tr>
    </table>

    <table class="bordered" width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th rowspan="2" width="3%">#</th>
                <th rowspan="2">Pengeluaran</th>
                <th rowspan="2">Oleh</th>
                <th align="center" colspan="6">Item</th>
                <th rowspan="2">Tgl Pengeluaran</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th colspan="2">Name</th>
                <th>Keterangan</th>
                <th width="1%">Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $index => $expense)
                <tr>
                    <td scope="row" align="center" rowspan="{{ count($expense->items) }}">{{ $index + 1 }}</td>
                    <td rowspan="{{ count($expense->items) }}">
                        {{ $expense->name }}
                        <br>
                        {!! strip_tags($expense->description) ? $expense->description : '' !!}
                    </td>
                    <td align="center" rowspan="{{ count($expense->items) }}">{{ $expense->user->name }}</td>
                    @foreach ($expense->items as $itemIndex => $item)
                        @if ($itemIndex > 0)
                </tr>
                <tr>
            @endif
            <td align="center" width="2%">{{ $itemIndex + 1 }}</td>
            <td>{{ $item->name }}</td>
            <td>{!! strip_tags($item->description) ? $item->description : '-' !!}</td>
            <td align="center" width="3%">{{ $item->quantity }}</td>
            <td>{{ formatRupiah($item->price) }}</td>
            <td>{{ formatRupiah($item->quantity * $item->price) }}</td>
            @if ($itemIndex == 0)
                <td rowspan="{{ count($expense->items) }}">
                    {{ \Carbon\Carbon::parse($expense->date_out)->format('d F Y H:i') }}
                </td>
                <td align="center" rowspan="{{ count($expense->items) }}">
                    {{ formatRupiah($expense->total) }}
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
                    {{ formatRupiah($expenses->sum(function ($expense) {return $expense->items->sum('subtotal');})) }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>

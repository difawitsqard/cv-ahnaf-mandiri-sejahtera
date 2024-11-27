<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $order->id }}</title>

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        body.resi-costum .sheet {
            width: 58mm;
            height: auto;
        }

        /** Padding area **/
        .sheet.padding-3mm {
            padding: 3mm;
        }


        /** For screen preview **/
        @media screen {
            body {
                background: #e0e0e0;
            }

            .sheet {
                background: white;
                box-shadow: 0 0.5mm 2mm rgba(0, 0, 0, 0.3);
                margin: 5mm auto;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.resi-costum .sheet {
                width: 58mm;
            }
        }
    </style>

    <style>
        .receipt {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 2px 0;
        }

        .table td:first-child {
            text-align: left;
        }

        .table td:last-child {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="resi-costum">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-3mm">

        <!-- Write HTML just like a web page -->
        <div class="receipt">
            <!-- Judul Outlet -->
            <h3 class="center">{{ $outlet->name }}</h3>
            <p class="center">{{ $outlet->address }}{{ $outlet->phone_number ? ", $outlet->phone_number" : '' }}</p>

            <!-- Informasi Kasir dan Pembeli -->
            <table class="table">
                <tr>
                    <td>Bill No</td>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>x</td>
                </tr>
                <tr>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>{{ $order->created_at->format('H:i') }}</td>
                </tr>
            </table>
            <div class="line"></div>

            @if ($order->name)
                <table class="table">
                    <td>Pelanggan</td>
                    <td>{{ $order->name }}</td>
                    </tr>
                </table>
                <div class="line"></div>
            @endif

            <!-- Rincian Pesanan -->
            <table class="table">
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->quantity }} {{ $item->menu->name }}</td>
                        <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
            <div class="line"></div>

            <!-- Total Harga -->
            <table class="table">
                <tr>
                    <td>Subtotal</td>
                    <td>{{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td>-{{ number_format(0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak</td>
                    <td>{{ number_format(0, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td>{{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="line"></div>

            <!-- Pesan Terima Kasih -->
            <p class="center">Silahkan datang kembali</br>Terima Kasih</p>
        </div>

        <script>
            // Cetak otomatis ketika halaman dimuat
            window.onload = function() {
                window.print();
            };
        </script>

    </section>

</body>

</html>

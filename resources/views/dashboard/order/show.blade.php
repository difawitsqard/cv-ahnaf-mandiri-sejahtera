@extends('layouts.app')
@section('title')
    {{ __('Order') }}
@endsection

@section('content')
    <x-page-title title="Pesanan" subtitle="Detail Pesanan" />

    <div class="card rounded-4 mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-lg-row flex-column align-items-start align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="detail-icon fs-2">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <div class="detail-info">
                        <h4 class="fw-bold mb-1">{{ $outlet->name }}</h4>
                        <p class="mb-0">{{ $outlet->address }}</p>
                    </div>
                </div>
                <div class="ms-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-outline-primary print"
                                data-url="{{ url()->current() }}/print">
                                <i class="bi bi-printer-fill me-2"></i>Print
                            </button>
                        </div>
                        @if ($order->can_be_canceled)
                        <div class="btn-group position-static">
                            <form
                                action="{{ roleBasedRoute('order.cancel', ['id' => $order->id, 'outlet' => $outlet->slug]) }}"
                                method="POST" class="border-0">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-primary rounded-0">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Batalkan
                                </button>
                            </form>

                            {{-- <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else
                                        here</a></li>
                            </ul> --}}
                        </div>
                        @endif
                        <div class="btn-group position-static">
                            <a href="{{ roleBasedRoute('order.create', ['outlet' => $outlet->slug]) }}"
                                class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Pesanan Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <x-alert-message type="success" :messages="session('success')" />
    @endif

    @if ($errors->any())
        <x-alert-message type="danger" :messages="$errors->all()" />
    @endif

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3 fw-bold">Pesanan #{{ $order->id }}</h5>
                            <div class="product-table">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="product-box">
                                                                <img src="{{ $item->menu->menuImages->first()->image_url ?? asset('build/images/placeholder-image.webp') }}"
                                                                    style="width: 70px; height: 53px; object-fit: cover;"
                                                                    class="rounded-3" alt="{{ $item->menu->name }}">
                                                            </div>
                                                            <div class="product-info">
                                                                <div class="product-title">{{ $item->menu->name }}</div>
                                                                <p class="mb-0">{{ $item->note }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ formatRupiah($item->price) }}</td>
                                                    <td>{{ formatRupiah($item->subtotal) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                @php
                                    $sub_total = $order->items->sum('subtotal');
                                    $total = $sub_total;
                                @endphp
                                <p class="mb-0 fw-bold">Subtotal :</p>
                                <p class="mb-0 fw-bold">{{ formatRupiah($sub_total) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 d-flex">
            <div class="w-100">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between align-items-center mb-4">
                            <div class="">
                                <h4 class="card-title fw-bold">Ringkasan</h4>
                            </div>
                            @php
                                $status = $order->status;

                                switch ($status) {
                                    case 'pending':
                                        $color = 'warning';
                                        $status = 'Menunggu';
                                        break;
                                    case 'completed':
                                        $color = 'success';
                                        $status = 'Sukses';
                                        break;
                                    case 'canceled':
                                        $color = 'danger';
                                        $status = 'Dibatalkan';
                                        break;

                                    default:
                                        $color = 'secondary';
                                        break;
                                }
                            @endphp
                            <span
                                class="lable-table bg-{{ $color }}-subtle text-{{ $color }} rounded border border-{{ $color }}-subtle fw-bold px-3">{{ $status }}</span>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between">
                                <p class="fw-semi-bold">Tanggal Waktu</p>
                                <p class="fst-italic">
                                    <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                </p>
                            </div>

                            @if ($order->name)
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semi-bold">Nama Order</p>
                                    <p class="fw-semi-bold">{{ $order->name }}</p>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <p class="fw-semi-bold">Subtotal <strong>{{ $order->items->sum('quantity') }}</strong>
                                    Produk :</p>
                                <p class="fw-semi-bold">{{ formatRupiah($sub_total) }}</p>
                            </div>

                            @if ($order->discount > 0)
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semi-bold">Diskon :</p>
                                    <p class="fw-semi-bold">
                                        @php
                                            $discount = ($total * $order->discount) / 100;
                                            $total = $total - $discount;
                                        @endphp
                                        <span class="text-danger discount-value">-{{ formatRupiah($discount) }}</span>
                                        <span class="discount-percent">({{ ($order->discount / 100) * 100 }}%)</span>
                                    </p>
                                </div>
                            @endif
                            @if ($order->tax > 0)
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semi-bold">Pajak :</p>
                                    <p class="fw-semi-bold">
                                        @php
                                            $tax = ($total * $order->tax) / 100;
                                            $discount = $discount ?? 0;
                                            $total = $sub_total - $discount + $tax;
                                        @endphp
                                        <span class="tax-value">{{ formatRupiah($tax) }}</span>
                                        <span class="tax-percent">({{ ($order->tax / 100) * 100 }}%)</span>
                                    </p>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <p class="fw-bold">Total :</p>
                                <p class="fw-bold">Rp.{{ formatRupiah($total) }}</p>
                            </div>

                            <div class="d-flex justify-content-between border-top pt-3">
                                <p class="fw-semi-bold">Total Bayar</p>
                                <p class="fw-semi-bold">{{ formatRupiah($order->paid) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="fw-semi-bold">Kembalian</p>
                                <p class="fw-semi-bold">{{ formatRupiah($order->change) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="fw-semi-bold">Metode Pembayaran</p>
                                <p class="fw-semi-bold">
                                    @switch($order->payment_method)
                                        @case('cash')
                                            {{ 'Tunai' }}
                                        @break

                                        @case('credit_card')
                                            {{ 'Kartu Kredit' }}
                                        @break

                                        @case('bank_transfer')
                                            {{ 'Transfer Bank' }}
                                        @break

                                        @case('other')
                                            {{ 'Lainnya' }}
                                        @break

                                        @default
                                            {{ $order->payment_method }}
                                    @endswitch
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="MyModal" tabindex="-1" aria-labelledby="MyModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah</h5>
                    <button type="button" class="btn-close" data-add-url="{{ route('unit.store') }}"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('unit.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="itemId" name="id">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Satuan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="..."
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        $(document).ready(function() {

            // Function to show feedback messages
            function showFeedback(title, text, icon) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonColor: '#0d6efd'
                });
            }

            // Function to print on PC
            function pc_print(data) {
                var socket = new WebSocket("ws://127.0.0.1:40213/");
                socket.bufferType = "arraybuffer";
                socket.onerror = function(error) {
                    showFeedback('Error', 'Pencetakan resi gagal: ' + error.message, 'error');
                };

                socket.onopen = function() {
                    socket.send(data);
                    socket.close(1000, "Work complete");
                };

                socket.onclose = function(event) {
                    if (event.wasClean) {
                        showFeedback('Selesai', 'Cetak resi selesai.', 'success');
                    } else {
                        showFeedback('Error', `Koneksi terputus (kode: ${event.code}).`, 'error');
                    }
                };
            }

            // Function to print on Android
            function mobile_print(data) {
                window.location.href = data;
                showFeedback('Selesai', 'Cetak resi selesai.', 'success');
            }

            function receiptPrint(url) {
                $.get(url, function(data) {
                    if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                        mobile_print(data);
                    } else {
                        pc_print(data);
                    }
                }).fail(function() {
                    showFeedback('Error', 'Gagal mengambil data untuk cetak resi.', 'error');
                });
            }

            // Click event for print button
            $('.print').on('click', function() {
                var url = $(this).data('url');
                receiptPrint(url);
            });
        });
    </script>
@endpush

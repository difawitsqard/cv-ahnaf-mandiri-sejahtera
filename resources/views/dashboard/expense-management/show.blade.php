@extends('layouts.app')
@section('title')
    {{ __('Pengeluaran') }}
@endsection

@section('content')
    <x-page-title title="Pengeluaran" subtitle="Detail Pengeluaran" />

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
                        {{-- <div class="btn-group position-static">
                            <button type="button" class="btn btn-outline-primary print"
                                data-url="{{ url()->current() }}/print">
                                <i class="bi bi-printer-fill me-2"></i>Print
                            </button>
                        </div> --}}
                        <div class="btn-group position-static">
                            <a href="{{ roleBasedRoute('expense.create', ['outlet' => $outlet->slug]) }}"
                                class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Pengeluaran Baru
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
        <div class="col-12 col-lg-4 d-flex">
            <div class="w-100">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between align-items-center mb-4">
                            <div class="">
                                <h4 class="card-title fw-bold">Ringkasan</h4>
                            </div>
                            @php
                                $status = $Expense->status;

                                switch ($status) {
                                    case 'submitted':
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

                        <div class="d-flex justify-content-between">
                            <p class="fw-semi-bold">Nama Pengeluaran</p>
                            <p class="fw-bold">{{ $Expense->name }}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="fw-semi-bold">Tanggal Pengeluaran</p>
                            <p class="fst-italic">
                                <span>{{ \Carbon\Carbon::parse($Expense->date_out)->format('d M Y H:i') }}</span>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="fw-semi-bold">Oleh ( <i class="text-muted">{{ ucfirst($Expense->user->roles->first()->name) }}</i> )</p>
                            <p class="fw-bold">{{ $Expense->user->name }}</p>
                        </div>

                        <div class="card card-body mb-3">
                            @php
                                $description = strip_tags($Expense->description);
                            @endphp

                            @if (strlen($description) > 0)
                                {!! $Expense->description !!}
                            @else
                                <p class="text-muted">Tidak ada keterangan.</p>
                            @endif
                        </div>
                        <div>

                            <div class="d-flex justify-content-between border-top pt-3">
                                <p class="fw-semi-bold">Subtotal <strong>{{ $Expense->items->sum('quantity') }}</strong>
                                    Produk :</p>
                                <p class="fw-semi-bold">{{ formatRupiah($Expense->items->sum('subtotal')) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="fw-bold">Total :</p>
                                <p class="fw-bold">Rp.{{ formatRupiah($Expense->total) }}</p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3 fw-bold">Daftar Item</h5>
                            <div class="product-table">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Keterangan</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Expense->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="product-box">
                                                                <img src="{{ $item->image_path ? $item->image_url : ($item->stockItem->image_path ?  $item->stockItem->image_url :  asset('build/images/placeholder-image.webp')) }}"
                                                                    style="width: 70px; height: 53px; object-fit: cover;"
                                                                    class="rounded-3" alt="{{ $item->name }}">
                                                            </div>
                                                            <div class="product-info">
                                                                <div class="product-title">{{ $item->name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $description = strip_tags($item->description);
                                                        @endphp

                                                        {!! str()->limit($description ?: '-', 8, '...') !!}

                                                        @if (strlen($description) > 8)
                                                            <a data-bs-toggle="collapse" href="#desc-{{ $item->id }}"
                                                                role="button" aria-expanded="false"
                                                                aria-controls="desc-{{ $item->id }}"
                                                                class="ms-2">Selengkapnya</a>

                                                            <div class="collapse multi-collapse"
                                                                id="desc-{{ $item->id }}">
                                                                {!! $item->description !!}
                                                            </div>
                                                        @endif

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
                                    $sub_total = $Expense->items->sum('subtotal');
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
                            <label for="name" class="form-label">Nama Satuan <span class="text-danger">*</span></label>
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

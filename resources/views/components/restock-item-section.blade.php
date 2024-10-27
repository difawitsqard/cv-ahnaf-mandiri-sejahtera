<div id="restock-item-section" @if (isset($outlet->slug)) data-outlet-id="{{ $outlet->slug }}" @endif
    @if (isset($stockItem->id)) data-item-id="{{ $stockItem->id }}" @endif>
    <h5 class="mb-3">Restock</h5>
    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <table class="ms-lg-3 mb-3">
                <thead>
                    <tr>
                        <th style="width: 200px;"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-1000 py-1 text-nowrap">Stock Saat Ini:</td>
                        <td class="text-700 fw-semi-bold py-1 text-nowrap">
                            <span id="stock-now">-</span>
                            <button class="btn p-0 ms-2" type="button">
                                <i class="bi bi-arrow-clockwise" id="reload-submit"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-1000 py-1 text-nowrap">Terakhir Restock:</td>
                        <td class="text-700 fw-semi-bold py-1 text-nowrap" id="last-restock">-</td>
                    </tr>
                    <tr>
                        <td class="text-1000 py-1 text-nowrap">Total stok sepanjang masa:</td>
                        <td class="text-700 fw-semi-bold py-1 text-nowrap" id="stock-lifetime">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12 col-lg-5 d-flex flex-column align-items-end">
            <div class="mb-2" id="text-message"></div>
            <input class="form-control mb-2" type="number" name="qty" id="qty" placeholder="Jumlah">
            <button type="button" class="btn btn-outline-primary" id="restock-submit">
                <i class="bi bi-check2 me-2"></i>reStock
            </button>
        </div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const restockItemModal = document.getElementById('restockItemModal');
            const restockSectionPage = document.getElementById('restock-item-section');

            function initializeRestockSection(restockSection, outletId, itemId) {
                if (!restockSection) return;

                const inputs = restockSection.querySelectorAll('input, select, textarea, button, a');

                const textMessageElement = restockSection.querySelector('#text-message');
                const stockNowElement = restockSection.querySelector('#stock-now');
                const lastRestockElement = restockSection.querySelector('#last-restock');
                const stockLifetimeElement = restockSection.querySelector('#stock-lifetime');
                const reloadSubmitButton = restockSection.querySelector('#reload-submit');
                const restockSubmitButton = restockSection.querySelector('#restock-submit');

                function setFieldsReadOnly(isReadOnly) {
                    inputs.forEach(function(input) {
                        input.readOnly = isReadOnly;
                        input.disabled = isReadOnly;
                    });
                }

                function loadStockData() {
                    setFieldsReadOnly(true);
                    textMessageElement.textContent = '';
                    const url = `{!! auth()->user()->getRoleNames()->first() == 'superadmin' ? '/outlet/${outletId}/stock-item/${itemId}/fetch' : '/stock-item/${itemId}/fetch' !!}`;

                    if (!outletId || !itemId) {
                        console.error('Error:', 'Outlet ID atau Item ID tidak ditemukan.');
                        setFieldsReadOnly(false);
                        return;
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {

                                if (restockItemModal) restockItemModal.querySelector('.modal-title')
                                    .textContent = data.data.name;

                                stockNowElement.textContent = `${data.data.stock} ${data.data.unit.name}`;
                                lastRestockElement.textContent = data.data.last_restock || 'belum';
                                stockLifetimeElement.textContent = data.data.lifetime_stock || 'belum';

                            } else {
                                console.error('Error:', 'Gagal memuat data stok.');
                            }
                            setFieldsReadOnly(false);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }


                restockSubmitButton.addEventListener('click', function() {
                    setFieldsReadOnly(true);
                    restockSubmitButton.disabled = true;

                    const qty = restockSection.querySelector('#qty').value;

                    if (!qty) {
                        textMessageElement.textContent = 'Masukkan jumlah yang valid !';
                        setFieldsReadOnly(false);
                        restockSubmitButton.disabled = false; // Aktifkan kembali tombol
                        return;
                    }

                    textMessageElement.textContent = '';

                    fetch(`{!! auth()->user()->getRoleNames()->first() == 'superadmin' ? '/outlet/${outletId}/stock-item/${itemId}/restock' : '/stock-item/${itemId}/restock' !!}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                _method: 'PUT',
                                qty: qty
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                // textMessageElement.textContent = data.message ? data.message : '';
                                restockSection.querySelector('#qty').value = '';
                                // loadStockData();

                                location.reload();
                            } else {
                                console.error('Error:', 'Terjadi kesalahan saat merestock item.');
                                setFieldsReadOnly(false);
                            }
                            restockSubmitButton.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            restockSubmitButton.disabled = false;
                        });
                });

                loadStockData(); // Load initial data
            }

            if (restockItemModal)
                document.getElementById('restockItemModal').addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget; // Button yang membuka modal
                    const outletId = button.getAttribute('data-outlet-id');
                    const itemId = button.getAttribute('data-item-id');
                    const restockSection = document.getElementById('restock-item-section');

                    initializeRestockSection(restockSection, outletId, itemId);
                });

            if (restockSectionPage) {
                const outletId = restockSectionPage.getAttribute('data-outlet-id');
                const itemId = restockSectionPage.getAttribute('data-item-id');

                initializeRestockSection(restockSectionPage, outletId, itemId);
            }
        });
    </script>
@endpush

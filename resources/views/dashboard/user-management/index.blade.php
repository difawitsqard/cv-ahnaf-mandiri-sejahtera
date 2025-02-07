@extends('layouts.app')
@section('title')
    {{ __('Pengguna') }}
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    @hasrole('superadmin')
        @if (importOnce('css-select2'))
            <link href="{{ URL::asset('build/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
            <link href="{{ URL::asset('build/plugins/select2/css/select2-bootstrap-5.min.css') }}" rel="stylesheet" />
        @endif
    @endhasrole
@endpush

@section('content')
    <x-page-title title="Pengguna" subtitle="Manajemen Pengguna" />

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
                    <button class="btn btn-primary px-4 add-button" data-bs-toggle="modal" data-bs-target="#MyModal"
                        data-add-url="{{ roleBasedRoute('user.store', ['outlet' => $outlet->slug]) }}">
                        <i class="bi bi-plus-lg me-2"></i>
                        Pengguna baru
                    </button>
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

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-auto">
                    <div class="input-group">
                        <select class="form-select" id="table-stock-item-length">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                        <label class="input-group-text" for="table-stock-item-length">Entri per halaman</label>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-auto">
                    <div class="position-relative mb-3">
                        <input class="form-control px-5" type="search" id="table-stock-item-search" placeholder="Cari...">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>


            <div class="table-responsive white-space-nowrap">
                <table class="table align-middle" id="table-user">
                    <thead class="bg-light">
                        <tr>
                            <th width="2%">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor Hp</th>
                            <th>Role</th>
                            <th>Verify</th>
                            <th>Tgl Bergabung</th>
                            <th>Status</th>
                            <th class="no-export">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $num => $user)
                            <tr>
                                <td class="text-center fw-bold" width="2%">{{ $num + 1 }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center pe-0">
                                        <img src="{{ URL::asset('build/images/default-avatar.jpg') }}"
                                            alt="{{ $user->name }}" width="36px" height="36px"
                                            class="rounded-circle shadow-sm" style="object-fit: cover;">
                                        <b class="d-none d-md-block ps-2">{{ $user->name }} </b> {!! $user->id == auth()->id() ? '&nbsp;(You)' : '' !!}
                                    </div>
                                </td>
                                <td>
                                    {{ $user['email'] }}
                                </td>
                                <td>
                                    {{ $user->mobile_phone_number ?? '-' }}
                                </td>
                                <td>

                                    @php
                                        $myRole = isset($user->roles[0]->name) ? $user->roles[0]->name : 'Unknown';

                                        switch ($myRole) {
                                            case 'staff':
                                                $color = 'secondary';
                                                $myRole = 'Staff';
                                                break;
                                            case 'admin':
                                                $color = 'primary';
                                                $myRole = 'Admin';
                                                break;
                                            case 'superadmin':
                                                $color = 'warning';
                                                $myRole = 'Superadmin';
                                                break;

                                            default:
                                                $color = 'secondary';
                                                $myRole = 'Unknown';
                                                break;
                                        }
                                    @endphp

                                    <span
                                        class="lable-table bg-{{ $color }}-subtle text-{{ $color }} rounded border border-{{ $color }}-subtle font-text2 fw-bold">{{ $myRole }}</span>
                                </td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at->format('d M Y h:i') }}
                                </td>
                                <td>

                                    @php
                                        switch ($user->disabled_account) {
                                            case '1':
                                                $color = 'danger';
                                                $status = 'Nonaktif';
                                                break;

                                            default:
                                                $color = 'success';
                                                $status = 'Aktif';
                                                break;
                                        }
                                    @endphp

                                    <span
                                        class="lable-table bg-{{ $color }}-subtle text-{{ $color }} rounded border border-{{ $color }}-subtle font-text2 fw-bold">{{ $status }}</span>
                                </td>
                                <td class="no-export">
                                    @if ($user->can_be_edited_or_deleted)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                <i class="material-icons-outlined d-flex">more_horiz</i>
                                            </button>
                                            <ul class="dropdown-menu"
                                                style="position: fixed; top: auto; left: auto; transform: translate(0, 0); z-index: 1050;"
                                                data-bs-popper="none">
                                                {{-- <li>
                                                <a type="button" class="dropdown-item"
                                                    href="{{ roleBasedRoute('stock-item.show', ['outlet' => $outlet->slug, 'stock_item' => $user->id]) }}">
                                                    Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a type="button" class="dropdown-item edit-button"
                                                    href="{{ roleBasedRoute('stock-item.edit', ['outlet' => $outlet->slug, 'stock_item' => $user->id]) }}">
                                                    Edit
                                                </a>
                                            </li> --}}
                                                {{-- @if (!$user->hasVerifiedEmail())
                                                    <li>
                                                        <form
                                                            action="{{ roleBasedRoute('user.resend-verification', ['outlet' => $outlet->slug, 'id' => $user->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item">Resend Email
                                                                Verifikasi</button>
                                                        </form>
                                                    </li>
                                                @endif --}}
                                                <li>
                                                    <button class="dropdown-item edit-button" data-id="{{ $user->id }}">Edit</button>
                                                </li>
                                                <hr class="dropdown-divider">
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item text-{{ !$user->disabled_account ? 'danger' : 'primary' }}"
                                                        data-id="{{ $user['id'] }}"
                                                        data-msg="Ingin {{ !$user->disabled_account ? 'Menonaktifkan' : 'Mengaktifkan' }} Pengguna <b>{{ $user['name'] }}</b> ?"
                                                        data-url="{{ roleBasedRoute('user.disabled-enabled', ['outlet' => $outlet->slug, 'user' => $user->id]) }}"
                                                        onclick="confirmDeleteDisabled(this)"
                                                        data-action="disabled_enabled">{{ !$user->disabled_account ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        data-id="{{ $user['id'] }}"
                                                        data-msg="Ingin menghapus Pengguna <b>{{ $user['name'] }}</b> ?"
                                                        data-url="{{ roleBasedRoute('user.destroy', ['outlet' => $outlet->slug, 'user' => $user->id]) }}"
                                                        onclick="confirmDeleteDisabled(this)"
                                                        data-action="delete">Hapus</button>
                                                </li>
                                                <form id="action-form-{{ $user['id'] }}"
                                                    action="{{ roleBasedRoute('user.destroy', ['outlet' => $outlet->slug, 'user' => $user->id]) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<!-- Modal -->
@push('modals')
    <div class="modal fade" id="MyModal" tabindex="-1" aria-labelledby="MyModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MyModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card mb-1 d-none">
                    <ul class="list-group block">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-5">
                            Bergabung
                            <span class="badge bg-dark rounded-pill" id="profile-join"></span>
                        </li>
                    </ul>
                </div>
                <form action="{{ roleBasedRoute('user.store', ['outlet' => $outlet->slug]) }}" method="POST">
                    <div class="modal-body ">
                        <div class="row g-3">
                            @csrf
                            @method('POST')
                            <input type="hidden" id="itemId" name="id">
                            @hasrole('superadmin')
                                <div class="col-12">
                                    <h6>Outlet <span class="text-danger">*</span></h6>
                                    <select class="form-select select2-single select2-stock-item" id="outlet_id"
                                        name="outlet_id" required>
                                        @foreach ($outlets as $rowOutlet)
                                            <option value="{{ $rowOutlet->id }}"
                                                data-image-url="{{ $rowOutlet->image_url }}"
                                                data-address="{{ $rowOutlet->address }}{{ $rowOutlet->phone_number ? ', ' . $rowOutlet->phone_number : '' }}"
                                                {{ old('outlet_id') == $rowOutlet->id || $outlet->id == $rowOutlet->id ? 'selected' : '' }}>
                                                {{ $rowOutlet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endhasrole
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 for="name" class="form-label">Nama <span class="text-danger">*</span></h6>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 for="role" class="form-label">Role <span class="text-danger">*</span></h6>
                                    <select id="role" class="form-select" name="role">
                                        <option disabled>Pilih Role...</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role') == $role->id ? 'selected' : '' }}>
                                                {{ ucwords($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <div class="mb-3">
                                    <h6 for="email" class="form-label">Email <span class="text-danger">*</span></h6>
                                    <div class="input-group">
                                        <input type="email" name="email" class="ignore form-control border-end-0"
                                            id="email" value="{{ old('email') }}" placeholder="..." required>
                                        <div class="input-group-text bg-transparent">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <div class="mb-3">
                                    <label for="mobile_phone_number" class="form-label">Nomor Hp</label>
                                    <input type="number" class="form-control" id="mobile_phone_number"
                                        name="mobile_phone_number" placeholder="628XX/08XX"
                                        value="{{ old('mobile_phone_number') }}">
                                </div>
                            </div>
                            <div class="mb-3 mt-0">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" name="address" placeholder="..." rows="3">{{ old('address') }}</textarea>
                            </div>
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
    @hasrole('superadmin')
        @if (importOnce('js-select2'))
            <script src="{{ URL::asset('build/plugins/select2/js/select2.min.js') }}"></script>
        @endif
    @endhasrole
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#table-user').DataTable({
                lengthChange: false,
                lengthMenu: [10, 25, 50, 100, -1],
                order: [
                    [0, 'asc']
                ]
            });

            @hasrole('superadmin')
                const modalForm = $('#MyModal');

                // Inisialisasi Select2
                $('#outlet_id').select2({
                    dropdownParent: modalForm,
                    theme: "bootstrap-5",
                    templateResult: formatOption,
                    templateSelection: formatOption,
                    escapeMarkup: function(markup) {
                        return markup; // Biarkan HTML ter-render
                    },
                    matcher: function(params, data) {
                        // Jika tidak ada parameter pencarian, tampilkan semua data
                        if ($.trim(params.term) === '') {
                            return data;
                        }

                        const term = params.term.toLowerCase();
                        const text = data.text.toLowerCase(); // Nama outlet
                        const address = $(data.element).data('address')?.toLowerCase() ||
                            ""; // Alamat outlet

                        // Pencarian pada nama atau alamat
                        if (text.includes(term) || address.includes(term)) {
                            return data;
                        }

                        // Jika tidak cocok, kembalikan null
                        return null;
                    }
                }).on('select2:open', function() {
                    $('.select2-container--bootstrap-5 .select2-selection--single').css('height', 'auto');
                });

                // Set initial height for Select2
                $('.select2-container--bootstrap-5 .select2-selection--single').css('height', 'auto');

                // Fungsi untuk menampilkan dropdown dengan gambar, nama, dan deskripsi
                function formatOption(option) {
                    const imageUrl = $(option.element).data('image-url') || "";
                    const address = $(option.element).data('address');
                    const name = option.text.trim();

                    return `<div class="d-flex align-items-center">
                            <img src="${imageUrl}" alt="${name}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <div class="fw-bold">${name}</div>
                                <div class="small">${address}</div>
                            </div>
                        </div>`;
                }

                // Extend Select2 to search by address
                $.fn.select2.amd.require(['select2/selection/search'], function(Search) {
                    const oldRemoveChoice = Search.prototype.searchRemoveChoice;
                    Search.prototype.searchRemoveChoice = function(decorated, item) {
                        const $search = this.$search;
                        const searchText = $search.val().toLowerCase();
                        const address = $(item.element).data('address') ? $(item.element).data(
                            'address').toLowerCase() : '';
                        const name = item.text.toLowerCase();
                        if (address.includes(searchText) || name.includes(searchText)) {
                            return;
                        }
                        oldRemoveChoice.apply(this, arguments);
                    };
                });
            @endhasrole

            $('#table-stock-item-length').on('change', function() {
                var selectedValue = $(this).val();
                table.page.len(selectedValue).draw();
            });

            // Hide default search box
            $('.dataTables_filter').hide();

            $('#table-stock-item-search').on('input', function() {
                table.search($(this).val()).draw();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var Modal = document.querySelector('#MyModal');
            var modalFooter = Modal.querySelector('.modal-footer');
            var modalForm = Modal.querySelector('form');
            var inputs = modalForm.querySelectorAll('input, textarea, select');
            var methodInput = modalForm.querySelector('input[name="_method"]');
            var itemIdInput = modalForm.querySelector('input[name="id"]');
            var nameInput = modalForm.querySelector('input[name="name"]');
            var roleInput = modalForm.querySelector('select[name="role"]');
            var emailInput = modalForm.querySelector('input[name="email"]');
            var phone_number = modalForm.querySelector('input[name="mobile_phone_number"]');
            var addressTextarea = modalForm.querySelector('textarea[name="address"]');
            var submitButton = modalForm.querySelector('button[type="submit"]');
            var iconEmail = [
                `<i class="bi bi-envelope"></i>`,
                `<i class="bi bi-check-circle-fill text-success"></i>`,
                `<i class="bi bi-x-circle-fill text-danger"></i>`,
            ];

            @hasrole('superadmin')
                let outletIdElement = document.getElementById('outlet_id');
            @endhasrole

            document.querySelectorAll('.add-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    submitButton.style.display = 'block';
                    methodInput.value = 'POST';
                    Modal.querySelector('#MyModalLabel').textContent = 'Tambah Pengguna';
                    modalForm.action = button.getAttribute('data-add-url');
                    modalForm.reset();
                    itemIdInput.value = '';
                });
            });

            @hasrole('superadmin')
                var triggerOutletSelect = function(id) {
                    outletIdElement.value = id;

                    let event = new Event('change');
                    outletIdElement.dispatchEvent(event);
                };

                var disabledSelectOutlet = function(value) {
                    if (value) {
                        outletIdElement.disabled = false;
                        outletIdElement.closest('.col-12').classList.remove('d-none');
                    } else {
                        outletIdElement.disabled = true;
                        outletIdElement.closest('.col-12').classList.add('d-none');
                    }
                };
            @endhasrole

            roleInput.addEventListener('change', function() {
                let selectedRole = this.options[this.selectedIndex];
                @hasrole('superadmin')
                    if (selectedRole.textContent.trim() == 'Superadmin') {
                        disabledSelectOutlet(false);
                    } else {
                        disabledSelectOutlet(true);
                    }

                    outletIdElement.dispatchEvent(new Event('change'));
                @endhasrole
            });

            modalForm.addEventListener('reset', function() {
                @hasrole('superadmin')
                    triggerOutletSelect('{{ $outlet->id }}');
                @endhasrole
                var verificationButton = modalForm.querySelector('.verification-button');
                if (verificationButton) {
                    verificationButton.closest('form').remove();
                }
                emailInput.removeAttribute('readonly');
                emailInput.closest('.input-group').querySelector('.input-group-text').innerHTML = iconEmail[
                    0];
                roleInput.dispatchEvent(new Event('change'));

            });

            //membuat button form submit resend email verifikasi
            var createResendButton = function(itemId) {
                var formResend = document.createElement('form');
                formResend.action =
                    `{{ roleBasedRoute('user.resend-verification', ['outlet' => $outlet->slug, 'id' => ':id']) }}`
                    .replace(':id', itemId);
                formResend.method = 'POST';
                formResend.classList.add('d-inline');

                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';

                var btnResend = document.createElement('button');
                btnResend.type = 'submit';
                btnResend.classList.add('btn', 'btn-primary', 'verification-button');
                btnResend.textContent = 'Resend Email Verifikasi';

                formResend.appendChild(csrfInput);
                formResend.appendChild(methodInput);
                formResend.appendChild(btnResend);

                return formResend;
            };

            document.querySelectorAll('.edit-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var isEdit = button.classList.contains('edit-button');
                    var itemId = button.getAttribute('data-id');

                    modalForm.reset();

                    if (isEdit) {
                        modalForm.action =
                            `{{ roleBasedRoute('user.update', ['outlet' => $outlet->slug, 'user' => ':id']) }}`
                            .replace(':id', itemId);
                        methodInput.value = 'PUT';
                        submitButton.style.display = 'block';
                    } else {
                        methodInput.value = '';
                        modalForm.action = '';
                        submitButton.style.display = 'none';
                    }

                    Modal.querySelector('#MyModalLabel').textContent = isEdit ? 'Edit Pengguna' :
                        'Detail';

                    $.ajax({
                        url: `{{ roleBasedRoute('user.fetch', ['outlet' => $outlet->slug, 'id' => ':id']) }}`.replace(':id', itemId),
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                nameInput.value = response.data.name;
                                roleInput.value = response.data.roles[0].id;
                                roleInput.dispatchEvent(new Event('change'));
                                emailInput.value = response.data.email;
                                phone_number.value = response.data.mobile_phone_number;
                                addressTextarea.value = response.data.address;
                                itemIdInput.value = response.data.id;

                                @hasrole('superadmin')
                                    triggerOutletSelect(response.data.outlet.id);
                                @endhasrole

                                if (!response.data.email_verified_at) {
                                    submitButton.insertAdjacentElement('beforebegin', createResendButton(itemId));
                                    emailInput.closest('.input-group').querySelector('.input-group-text').innerHTML = iconEmail[2];
                                } else {
                                    emailInput.setAttribute('readonly', true);
                                    emailInput.closest('.input-group').querySelector('.input-group-text').innerHTML = iconEmail[1];
                                }

                                if (isEdit) {

                                }

                                // Show modal
                                setTimeout(() => {
                                    $('#MyModal').modal('show');
                                }, 100);
                            } else {
                                console.error('Error:', 'Gagal memuat data.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });

                });
            });
        });

        function confirmDeleteDisabled(button) {
            const uniqueId = button.getAttribute('data-id');
            const msg = button.getAttribute('data-msg');
            const url = button.getAttribute('data-url');
            const action = button.getAttribute('data-action');

            const form = document.getElementById('action-form-' + uniqueId);

            form.action = url;
            form.querySelector('input[name="_method"]').value = action == 'delete' ? 'DELETE' : 'PUT';

            Swal.fire({
                title: 'Apa kamu yakin?',
                html: `${msg}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#fc185a',
                confirmButtonText: 'Ya !',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endpush

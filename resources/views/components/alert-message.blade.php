<div>
    @if (!empty($messages))
        <div class="alert alert-{{ $type }} border-0 bg-{{ $type }} alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white">
                    <span class="material-icons-outlined fs-2">
                        @if ($type == 'danger')
                            report_gmailerrorred
                        @else
                            check_circle
                        @endif
                    </span>
                </div>
                <div class="ms-3">
                    <h5 class="mb-0 text-white">
                        @if ($type == 'danger')
                            Uups!
                        @else
                            Sukses!
                        @endif
                    </h5>
                    <div class="text-white">
                        @if (is_array($messages) && count($messages) > 1 && $type == 'danger')
                            An error occurred while entering data.
                            <ul class="mb-0">
                                @foreach ($messages as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ is_array($messages) ? $messages[0] : $messages }}
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

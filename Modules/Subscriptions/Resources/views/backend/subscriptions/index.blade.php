@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('content')
    <div class="card-main mb-5">
        <x-backend.section-header>
            <div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-dark" data-modal="export">
                    <i class="ph ph-export align-middle"></i> {{ __('messages.export') }}
                </button>
            </div>

            <x-slot name="toolbar">

                <div class="input-group flex-nowrap">
                    <span class="input-group-text pe-0" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="{{__('placeholder.lbl_search')}}" aria-label="Search"
                        aria-describedby="addon-wrapping">

                </div>

            </x-slot>
        </x-backend.section-header>
        <table id="datatable" class="table table-responsive"></table>
    </div>

    @if (session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success"
                    onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="{{ asset('js/form-modal/index.js') }}" defer></script>
    <script src="{{ asset('js/form/index.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" defer>
        const columns = [
            {
                name: 'check',
                data: 'check',
                title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="subscriptions"  onclick="selectAllTable(this)">',
                width: '0%',
                exportable: false,
                orderable: false,
                searchable: false,
            },    
            {
                data: 'user_id',
                name: 'user_id',
                title: "{{ __('messages.user') }}"
            },
            {
                data: 'name',
                name: 'name',
                title: "{{ __('messages.plan') }}"
            },

            {
                data: 'start_date',
                name: 'start_date',
                title: "{{ __('messages.start_date') }}"
            },
            {
                data: 'end_date',
                name: 'end_date',
                title: "{{ __('messages.end_date') }}"
            },
            {
                data: 'amount',
                name: 'amount',
                title: "{{ __('messages.price') }}"
            },
            {
                data: 'tax_amount',
                name: 'tax_amount',
                title: "{{ __('messages.tax_amount') }}"
            },
            {
                data: 'total_amount',
                name: 'total_amount',
                title: "{{ __('messages.total_amount') }}"
            },
            {
                data: 'status',
                name: 'status',
                title: "{{ __('messages.lbl_status') }}",
                render: function (data, type, row) {
                    let capitalizedData = data.charAt(0).toUpperCase() + data.slice(1);
                    let className = data == 'active' ? 'badge bg-success-subtle p-2' : 'badge bg-danger-subtle p-2';
                    return '<span class="' + className + '">' + capitalizedData + '</span>';
                }
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                title: "{{ __('messages.update_at') }}",
                orderable: true,
                visible: false,
            }
        ];

        const actionColumn = [];


        const finalColumns = [...columns, ...actionColumn];

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                orderColumn: [
                    [6, "desc"]
                ],
                search: {
                    selector: '.dt-search',
                    smart: true
                }
            });
        });

        function resetQuickAction() {
            const actionValue = $('#quick-action-type').val();
            $('#quick-action-apply').attr('disabled', actionValue == '');
            $('.quick-action-field').addClass('d-none');
            if (actionValue == 'change-status') {
                $('#change-status-action').removeClass('d-none');
            }
        }

        $('#quick-action-type').change(resetQuickAction);
    </script>
@endpush

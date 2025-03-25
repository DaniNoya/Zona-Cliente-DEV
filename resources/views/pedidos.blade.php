@extends('adminlte::page')

@section('title', 'Panel de Pedidos')

@section('preloader')
    <img src="{{ asset('vendor/adminlte/dist/img/GlobalIcon.png') }}" class="animation__shake" width="120" height="120">
    <h4 class="mt-4 text-dark">Cargando panel de pedidos...</h4>
@stop

{{-- Activate the necessary DataTables plugins --}}
@section('plugins.Datatables', true)
@section('plugins.DatatablesButtons', true)

@section('content_header')
    <h1>Panel de Pedidos</h1>
@stop

@php
    $heads = [
        'ID',
        'Proveedor',
        'Centro',
        'Fecha Creación Pedido',
        'Fecha Confirmación Pedido',
        'Status',
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow order-details-btn" title="Details" data-order-id="{order_id}">
                        <i class="fa fa-lg fa-fw fa-file-alt"></i>
                   </button>';

    // 20 orders (10 original + 10 additional) for pagination testing.
    $ordersData = [
        [22, 'Global Electronics Ltd', 'Madrid Centro', '2024-01-15', '2024-01-16', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '22', $btnDetails)],
        [19, 'Tech Solutions S.L.', 'Barcelona Norte', '2024-01-14', '2024-01-15', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '19', $btnDetails)],
        [3, 'Innovate Systems', 'Valencia Sur', '2024-01-13', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '3', $btnDetails)],
        [45, 'Digital Imports', 'Sevilla Este', '2024-01-12', '2024-01-13', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '45', $btnDetails)],
        [31, 'Smart Devices Corp', 'Bilbao Centro', '2024-01-11', '2024-01-12', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '31', $btnDetails)],
        [17, 'Tech Warehouse', 'Málaga Oeste', '2024-01-10', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '17', $btnDetails)],
        [52, 'Electronics Plus', 'Zaragoza Norte', '2024-01-09', '2024-01-10', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '52', $btnDetails)],
        [28, 'Global Tech S.A.', 'Alicante Centro', '2024-01-08', '2024-01-09', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '28', $btnDetails)],
        [14, 'Innovative Solutions', 'Córdoba Sur', '2024-01-07', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '14', $btnDetails)],
        [39, 'Tech Imports S.L.', 'Vigo Este', '2024-01-06', '2024-01-07', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '39', $btnDetails)],

        // Additional orders for pagination
        [60, 'NextGen Electronics', 'Madrid Sur', '2024-01-05', '2024-01-06', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '60', $btnDetails)],
        [61, 'Future Tech', 'Barcelona Centro', '2024-01-04', '2024-01-05', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '61', $btnDetails)],
        [62, 'Innovative Minds', 'Valencia Centro', '2024-01-03', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '62', $btnDetails)],
        [63, 'Digital World', 'Sevilla Centro', '2024-01-02', '2024-01-03', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '63', $btnDetails)],
        [64, 'Smart Solutions', 'Bilbao Norte', '2024-01-01', '2024-01-02', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '64', $btnDetails)],
        [65, 'Tech Innovators', 'Málaga Centro', '2023-12-31', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '65', $btnDetails)],
        [66, 'ElectroHub', 'Zaragoza Centro', '2023-12-30', '2023-12-31', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '66', $btnDetails)],
        [67, 'Gadget World', 'Alicante Norte', '2023-12-29', '2023-12-30', 
         '<i class="fa fa-truck-fast text-warning"></i>', str_replace('{order_id}', '67', $btnDetails)],
        [68, 'NextLevel Tech', 'Córdoba Centro', '2023-12-28', null, 
         '<i class="fa fa-clipboard text-info"></i>', str_replace('{order_id}', '68', $btnDetails)],
        [69, 'Modern Electronics', 'Vigo Centro', '2023-12-27', '2023-12-28', 
         '<i class="fa fa-check-circle text-success"></i>', str_replace('{order_id}', '69', $btnDetails)],
    ];

    $config = [
        'data'       => $ordersData,
        'order'      => [[0, 'asc']],
        'pageLength' => 10,
        // Use the default DOM for full DataTables features (buttons, search, length, info & pagination)
        'dom'        => "Bfrtip",
        'buttons'    => ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
    ];
@endphp

@section('content')
    <!-- First row: Filter Options -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filter Options</h3>
                </div>
                <div class="card-body">
                    <!-- Your existing filter form (including between dates, etc.) goes here -->
                    <form id="filter-form" method="GET">
                        <div class="row">
                            <!-- Example filter: Proveedor -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="provider">Proveedor</label>
                                    <select name="provider" id="provider" class="form-control">
                                        <option value="">All Providers</option>
                                        <option value="Global Electronics Ltd">Global Electronics Ltd</option>
                                        <option value="Tech Solutions S.L.">Tech Solutions S.L.</option>
                                        <!-- More options as needed -->
                                    </select>
                                </div>
                            </div>
                            <!-- Example filter: Centro -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="center">Centro</label>
                                    <select name="center" id="center" class="form-control">
                                        <option value="">All Centers</option>
                                        <option value="Madrid Centro">Madrid Centro</option>
                                        <option value="Barcelona Norte">Barcelona Norte</option>
                                        <!-- More options as needed -->
                                    </select>
                                </div>
                            </div>
                            <!-- Example filter: Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="pending">Pending</option>
                                        <!-- More options as needed -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Between Dates Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Second row: Column Visibility Options -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-columns mr-2"></i>Column Visibility</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Column visibility toggles with modern toggle switches -->
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="0" id="col0" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">ID</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="1" id="col1" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Proveedor</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="2" id="col2" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Centro</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="3" id="col3" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Fecha Creación Pedido</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="4" id="col4" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Fecha Confirmación Pedido</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="5" id="col5" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Status</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="6" id="col6" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Actions</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Third row: DataTable -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Pedidos</h3>
                </div>
                <div class="card-body">
                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" 
                        head-theme="light" hoverable bordered striped beautify>
                        @foreach($ordersData as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <!-- Export Buttons (if needed) -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary">Export to CSV</button>
                            <button type="button" class="btn btn-secondary">Export to PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        /* DataTable pagination styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            border-radius: 4px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef;
            border: 1px solid #dee2e6;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #007bff;
            border-color: #007bff;
            color: white !important;
        }
        
        /* Toggle Switch Styling */
        .toggle-container {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .toggle-container:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            margin-right: 12px;
            margin-bottom: 0;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        
        input:checked + .slider {
            background-color: #17a2b8;
        }
        
        input:focus + .slider {
            box-shadow: 0 0 1px #17a2b8;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .slider.round {
            border-radius: 24px;
        }
        
        .slider.round:before {
            border-radius: 50%;
        }
        
        .toggle-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function(){
            // Initialize DataTable with column visibility state
            var table = $('#table1').DataTable();

            // Attach change event to toggle visibility of each column
            $('.toggle-vis').on('change', function() {
                var column = table.column($(this).attr('data-column'));
                column.visible($(this).prop('checked'));
                
                // Add animation effect to the toggle container
                $(this).closest('.toggle-container').addClass('pulse-effect');
                setTimeout(function() {
                    $('.toggle-container').removeClass('pulse-effect');
                }, 500);
            });
        });
    </script>

    <script>
        // Add a small animation when toggling columns
        $(document).ready(function(){
            $('.toggle-container').hover(
                function() {
                    $(this).find('.toggle-label').css('color', '#17a2b8');
                },
                function() {
                    $(this).find('.toggle-label').css('color', '#495057');
                }
            );
        });
    </script>
@endpush

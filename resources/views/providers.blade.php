@extends('adminlte::page')

@section('title', 'Panel de proveedores')

@section('preloader')
    <img src="{{ asset('vendor/adminlte/dist/img/GlobalIcon.png') }}" class="animation__shake" width="120" height="120">
    <h4 class="mt-4 text-dark">Cargando panel de proveedores...</h4>
@stop

{{-- Activate the necessary DataTables plugins --}}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.jsPDF', true)

@section('content_header')
    <h1>Panel de gestion de proveedores</h1>
@stop

@php
    use App\Models\Provider;
    use App\Models\Location;

    $heads = [
        'ID',
        'Proveedor',
        'Ubicación',
        'Email',
        'Teléfono',
        'Status',
        'Puntuación',
        ['label' => 'Top Products', 'no-export' => true, 'width' => 5],
        ['label' => 'Actions', 'no-export' => true, 'width' => 5],
    ];

    $btnProducts = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="View Top Products">
                        <i class="fa fa-lg fa-fw fa-box"></i>
                   </button>';
    $btnContact = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Contact Provider">
                        <i class="fa fa-lg fa-fw fa-phone"></i>
                   </button>';

    $providers = Provider::all();
    $providersData = [];

    foreach ($providers as $provider) {
        $status = match(strtolower($provider->status->name)) {
            'activo' => '<i class="fa fa-circle text-success"></i> Activo',
            'pendiente' => '<i class="fa fa-circle text-warning"></i> Pendiente',
            'inactivo' => '<i class="fa fa-circle text-danger"></i> Inactivo',
            default => '<i class="fa fa-circle text-secondary"></i> Desconocido',
        };

        $providersData[] = [
            $provider->id,
            $provider->name,
            $provider->location,
            $provider->email,
            $provider->phone,
            $status,
            number_format($provider->rating, 1),
            '<nobr>'.$btnProducts.'</nobr>',
            '<nobr>'.$btnContact.'</nobr>'
        ];
    }

    $config = [
        'data'         => $providersData,
        'order'        => [[0, 'asc']],
        'pageLength'   => 10,
        'columnDefs'   => [
            ['targets' => -1, 'className' => 'no-export']
        ],
        'dom'          => '<"d-flex justify-content-end mb-3"B>frtip',
        'buttons'      => [
            [
                'text'    => '<i class="fas fa-file-csv mr-1"></i>CSV',
                'className' => 'btn btn-outline-secondary',
                'attr'     => ['id' => 'exportCSV']
            ],
            [
                'text'    => '<i class="fas fa-file-pdf mr-1"></i>PDF',
                'className' => 'btn btn-outline-secondary',
                'attr'     => ['id' => 'exportPDF']
            ]
        ],
        'destroy'      => true
    ];

    // Hidden input to store export type
    echo '<input type="hidden" id="exportType" value="">';
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
                    <form id="filter-form" method="GET">
                        <div class="row">
                            <!-- Filter: Proveedor -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="provider">Proveedor</label>
                                    <select name="provider" id="provider" class="form-control">
                                        <option value="">Todos los proveedores</option>
                                        @foreach(Provider::all() as $provider)
                                            <option value="{{ $provider->name }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Filter: Ubicación -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="location">Ubicación</label>
                                    <select name="location" id="location" class="form-control">
                                        <option value="">Todas las ubicaciones</option>
                                        @foreach(Location::all() as $location)
                                            <option value="{{ $location->name }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Filter: Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="active">Activo</option>
                                        <option value="pending">Pendiente</option>
                                        <option value="inactive">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Score Range Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_score">Puntuación Mínima</label>
                                    <input type="number" name="min_score" id="min_score" class="form-control" min="0" max="5" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_score">Puntuación Máxima</label>
                                    <input type="number" name="max_score" id="max_score" class="form-control" min="0" max="5" step="0.1">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
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
                    <h3 class="card-title">Column Visibility</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="0" checked>
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
                                <span class="toggle-label">Ubicación</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="3" id="col3" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Status</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="4" id="col4" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-label">Puntuación</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="toggle-container">
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-vis" data-column="5" id="col5" checked>
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
                    <h3 class="card-title">Listado de Proveedores</h3>
                </div>
                <div class="card-body">
                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" 
                        head-theme="light" hoverable bordered striped beautify>
                        @foreach($providersData as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
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

        /* Additional DataTable Styling */
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 1rem;
        }
        .dt-button {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            color: #495057 !important;
            padding: 0.375rem 0.75rem !important;
            border-radius: 0.25rem !important;
            transition: all 0.15s ease-in-out !important;
        }
        .dt-button:hover {
            background-color: #e9ecef !important;
            border-color: #ddd !important;
            color: #2b2b2b !important;
        }
        .dt-button.active {
            background-color: #007bff !important;
            border-color: #0056b3 !important;
            color: #fff !important;
        }
        .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
        }
        .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .toggle-container {
                margin-bottom: 0.5rem;
            }
            .dt-buttons {
                text-align: center;
                margin-bottom: 1rem;
            }
            .dataTables_filter {
                text-align: center;
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with configuration
            var table = $('#table1').DataTable();

            // Handle column visibility toggle
            $('.toggle-vis').on('change', function(e) {
                e.preventDefault();
                var column = table.column($(this).attr('data-column'));
                column.visible(!column.visible());
                
                // Add animation effect to the toggle container
                $(this).closest('.toggle-container').addClass('pulse-effect');
                setTimeout(function() {
                    $('.toggle-container').removeClass('pulse-effect');
                }, 500);
            });

            // Handle filter form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                console.log('Filter form submitted');
                let provider = $('#provider').val();
                let location = $('#location').val();
                let status = $('#status').val();
                let minScore = parseFloat($('#min_score').val()) || 0;
                let maxScore = parseFloat($('#max_score').val()) || 5;

                // Custom filtering function
                $.fn.dataTable.ext.search = [];
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        if (settings.nTable.id !== 'table1') {
                            return true;
                        }

                        let rowProvider = data[1];
                        let rowLocation = data[2];
                        let rowStatus = data[5];
                        let rowScore = parseFloat(data[6]) || 0;

                        // Check if the row matches all filter criteria
                        if (
                            (provider === "" || rowProvider === provider) &&
                            (location === "" || rowLocation === location) &&
                            (status === "" || rowStatus.includes(status)) &&
                            (rowScore >= minScore && rowScore <= maxScore)
                        ) {
                            return true;
                        }
                        return false;
                    }
                );

                table.draw();
            });

            // Export functionality
            $('#exportButton').on('click', function() {
                $('#exportColumnsModal').modal('show');
            });

            // Handle export type selection
            $('#exportCSV, #exportPDF').on('click', function() {
                $('#exportType').val($(this).attr('id'));
                $('#exportColumnsModal').modal('show');
            });

            // Handle custom range display
            $('input[name="exportScope"]').change(function() {
                if ($(this).val() === 'custom') {
                    $('#customRangeContainer').show();
                } else {
                    $('#customRangeContainer').hide();
                }
            });

            // Update pages value display
            $('#exportPagesSlider').on('input', function() {
                $('#exportPagesValue').text($(this).val());
            });

            // Handle export buttons
            $('#exportCSVBtn, #exportPDFBtn').on('click', function() {
                let exportType = $(this).attr('id').includes('CSV') ? 'csv' : 'pdf';
                let selectedColumns = [];
                $('.export-column:checked').each(function() {
                    selectedColumns.push($(this).data('column'));
                });

                let exportScope = $('input[name="exportScope"]:checked').val();
                let customPages = exportScope === 'custom' ? parseInt($('#exportPagesSlider').val()) : 0;

                // Get filtered data
                let data = [];
                if (exportScope === 'current') {
                    data = table.page.info().page;
                } else if (exportScope === 'all') {
                    data = table.rows({ filter: 'applied' }).data().toArray();
                } else {
                    // Custom range
                    let startPage = table.page.info().page;
                    let endPage = startPage + customPages;
                    for (let i = startPage; i <= endPage; i++) {
                        table.page(i).draw('page');
                        data = data.concat(table.rows({ page: 'current' }).data().toArray());
                    }
                    // Return to original page
                    table.page(startPage).draw('page');
                }

                // Filter columns
                data = data.map(row => selectedColumns.map(col => row[col]));

                if (exportType === 'csv') {
                    // Export to CSV
                    let csv = data.map(row => row.join(',')).join('\n');
                    let blob = new Blob([csv], { type: 'text/csv' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'providers_export.csv';
                    link.click();
                } else {
                    // Export to PDF
                    let doc = new jsPDF();
                    doc.autoTable({
                        head: [selectedColumns.map(col => table.column(col).header().textContent)],
                        body: data
                    });
                    doc.save('providers_export.pdf');
                }

                $('#exportColumnsModal').modal('hide');
            });

            // Add hover animation for toggle containers
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
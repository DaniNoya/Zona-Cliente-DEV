@extends('adminlte::page')

@section('title', 'Panel de usuarios')

@section('preloader')
    <img src="{{ asset('vendor/adminlte/dist/img/GlobalIcon.png') }}" class="animation__shake" width="120" height="120">
    <h4 class="mt-4 text-dark">Cargando panel de usuarios...</h4>
@stop

{{-- Activate the necessary DataTables plugins --}}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1>Panel de gestion de usuarios</h1>
@stop

@php
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Status;

    $heads = [
        ['label' => 'ID', 'width' => 5],
        ['label' => 'Nombre'],
        ['label' => 'Usuario'],
        ['label' => 'Email'],
        ['label' => 'Rol'],
        ['label' => 'Ubicación'],
        ['label' => 'Fecha de Registro'],
        ['label' => 'Status'],
        ['label' => 'Actions', 'no-export' => true, 'width' => 5, 'className' => 'no-export'],
    ];

    $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow user-details-btn" title="Details" data-user-id="{user_id}" data-profile-picture="{profile_picture}">
                        <i class="fa fa-lg fa-fw fa-user"></i>
                   </button>';

    $users = User::with(['role', 'status', 'location'])->get();
    $usersData = [];

    foreach ($users as $user) {
        // Get status with icon and color from the relationship
        $status = '<i class="fa ' . $user->status->icon_class . ' ' . $user->status->color_class . '"></i> ' . $user->status->name;

        // Add user data to array with relationships
        $usersData[] = [
            $user->id,
            $user->name,
            $user->user,
            $user->email,
            $user->role->name,
            $user->location ? $user->location->name : 'N/A',
            $user->created_at->format('Y-m-d'),
            $status,
            str_replace(['{user_id}', '{profile_picture}'], [$user->id, $user->profile_picture], $btnDetails)
        ];
    }

    $config = [
        'data'         => $usersData,
        'order'        => [[0, 'asc']],
        'pageLength'   => 10,
        'columnDefs'   => [
            ['targets' => 6, 'className' => 'no-export']
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

@section('plugins.jsPDF', true)

@section('content')
    <!-- First row: Filter Options -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>Filter Options
                    </h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" method="GET">
                        <div class="row">
                            <!-- Filter: Rol -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Rol</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Todos los roles</option>
                                        @foreach(Role::all() as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
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
                                        @foreach(Status::all() as $status)
                                            <option value="{{ $status->name }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Filter: Registration Date Range -->
                            <div class="col-md-8"> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_date">Fecha de Registro (Desde)</label>
                                            <input type="date" name="from_date" id="from_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_date">Fecha de Registro (Hasta)</label>
                                            <input type="date" name="to_date" id="to_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Column Selection Modal -->
    <div class="modal fade" id="exportColumnsModal" tabindex="-1" role="dialog" aria-labelledby="exportColumnsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportColumnsModalLabel">Seleccionar Columnas para Exportar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Selección de columnas -->
                    <div class="row">
                        @foreach(['ID', 'Nombre', 'Usuario', 'Email', 'Rol', 'Ubicación', 'Fecha de Registro', 'Status'] as $index => $column)
                            <div class="col-md-4 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input export-column" id="export-col-{{ $index }}" data-column="{{ $index }}" checked>
                                    <label class="custom-control-label" for="export-col-{{ $index }}">{{ $column }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Opciones de alcance para exportar -->
                    <div class="form-group">
                        <label>Seleccionar alcance de exportación</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="scopeCurrent" name="exportScope" class="custom-control-input" value="current" checked>
                            <label class="custom-control-label" for="scopeCurrent">Página actual</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="scopeAll" name="exportScope" class="custom-control-input" value="all">
                            <label class="custom-control-label" for="scopeAll">Todos los registros filtrados</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="scopeCustom" name="exportScope" class="custom-control-input" value="custom">
                            <label class="custom-control-label" for="scopeCustom">Personalizado</label>
                        </div>
                        <!-- Slider para la opción personalizada -->
                        <div id="customRangeContainer" style="display:none; margin-top: 10px;">
                            <label for="exportPagesSlider">Número de páginas a exportar (0 = solo página actual): <span id="exportPagesValue">0</span></label>
                            <input type="range" class="custom-range" id="exportPagesSlider" min="0" max="10" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary mr-2" id="exportCSVBtn">
                        <i class="fas fa-file-csv mr-2"></i> Export to CSV
                    </button>
                    <button type="button" class="btn btn-danger" id="exportPDFBtn">
                        <i class="fas fa-file-pdf mr-2"></i> Export to PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Third row: DataTable -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Listado de Usuarios</h3>
                    <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#newUserModal">
                        <i class="fas fa-user-plus mr-2"></i>Nuevo Usuario
                    </button>
                </div>
                <div class="card-body">
                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="light" hoverable bordered striped beautify>
                        @foreach($usersData as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <!-- Botón de exportación -->
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-primary px-3 py-2" id="exportButton">
                            <i class="fas fa-file-export mr-2"></i> Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New User Modal -->
    <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newUserModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="newUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="user">Usuario</label>
                                    <input type="text" class="form-control" id="user" name="user" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Rol</label>
                                    <select class="form-control" id="role" name="role" required>
                                        @foreach(Role::all() as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Estado</label>
                                    <select class="form-control" id="status" name="status" required>
                                        @foreach(Status::all() as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="profile_picture">Foto de Perfil</label>
                                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">Detalles del Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- User Details View -->
                    <div id="userDetailsView">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img id="userProfilePicture" class="profile-user-img img-fluid img-circle mb-3" src="" alt="User profile picture" style="width: 100px; height: 100px;">
                                        <h3 id="userName" class="profile-username text-center"></h3>
                                        <p id="userRole" class="text-muted text-center"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Información Personal</h3>
                                    </div>
                                    <div class="card-body">
                                        @foreach(['ID' => 'userId', 'Username' => 'userUsername' , 'Email' => 'userEmail', 'Fecha de Registro' => 'userRegistrationDate', 'Estado' => 'userStatus', 'Ubicación' => 'userLocation'] as $label => $id)
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <strong>{{ $label }}</strong>
                                                </div>
                                                <div class="col-sm-8" id="{{ $id }}"></div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User Edit Form -->
                    <div id="userEditForm" style="display: none;">
                        <form action="{{ route('users.update', ['user' => ':user_id']) }}" method="POST" enctype="multipart/form-data" id="editUserForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_user_id" name="user_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_name">Nombre</label>
                                        <input type="text" class="form-control" id="edit_name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_email">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_password">Nueva Contraseña (dejar en blanco si no cambia)</label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_role">Rol</label>
                                        <select class="form-control" id="edit_role" name="role" required>
                                            @foreach(Role::all() as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_status">Estado</label>
                                        <select class="form-control" id="edit_status" name="status" required>
                                            @foreach(Status::all() as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_user">Usuario</label>
                                        <input type="text" class="form-control" id="edit_user" name="user" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_profile_picture">Nueva Foto de Perfil</label>
                                        <input type="file" class="form-control-file" id="edit_profile_picture" name="profile_picture">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" onclick="$('#userEditForm').hide(); $('#userDetailsView').show();">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteUserBtn">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar Usuario
                    </button>
                    <button type="button" class="btn btn-primary" id="editUserBtn">
                        <i class="fas fa-edit mr-2"></i>Editar Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        /* Export button styling */
        .btn i.fas {
            font-size: 1rem;
            vertical-align: middle;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 30px;
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
    // Document Ready Handler
    $(document).ready(function() {
        // Constants and Initializations
        const table = initializeDataTable();
        const modals = {
            newUser: $('#newUserModal'),
            details: $('#userDetailsModal'),
            edit: $('#userEditForm'),
            export: $('#exportColumnsModal')
        };

        // DataTable Initialization
        function initializeDataTable() {
            if (!$.fn.DataTable.isDataTable('#table1')) {
                return $('#table1').DataTable({
                    dom: 'Bfrtip',
                    buttons: []
                });
            }
            return $('#table1').DataTable();
        }

        // Helper Functions
        const helpers = {
            // Form Handling
            handleFormResponse: (response, table, isNew = false) => {
                const user = response.user;
                const rowData = [
                    user.id,
                    user.name,
                    user.user,
                    user.email,
                    user.role.name,
                    (user.location && user.location.name) || 'N/A',
                    user.created_at.split('T')[0],
                    `<i class="fa ${user.status.icon_class} ${user.status.color_class}"></i> ${user.status.name}`,
                    `<button class="btn btn-xs btn-default text-teal mx-1 shadow user-details-btn" 
                        title="Details" 
                        data-user-id="${user.id}" 
                        data-profile-picture="${user.profile_picture || ''}">
                        <i class="fa fa-lg fa-fw fa-user"></i>
                    </button>`
                ];

                if (isNew) {
                    table.row.add(rowData).draw();
                } else {
                    table.rows().every(function() {
                        if (this.data()[0] === user.id) {
                            this.data(rowData);
                            return false;
                        }
                    });
                }
            },

            // Error Handling
            showFormErrors: (xhr) => {
                const errors = xhr.responseJSON.errors;
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                $.each(errors, (field, messages) => {
                    const input = $(`[name="${field}"]`);
                    input.addClass('is-invalid')
                        .after(`<div class="invalid-feedback">${messages[0]}</div>`);
                });
            },

            // Confirmation Dialogs
            confirmAction: (config) => {
                return Swal.fire({
                    title: config.title,
                    text: config.text,
                    icon: config.icon,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: config.confirmText,
                    cancelButtonText: 'Cancelar'
                });
            },

            // Modificado para retornar la promesa y aceptar opciones adicionales
            showSuccess: (title, text, options = {}) => {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'success',
                    allowOutsideClick: false,
                    confirmButtonText: 'Recargar'
                });
            },

            showError: (text) => {
                Swal.fire({
                    title: 'Error',
                    text: text,
                    icon: 'error'
                });
            },

            selectDropdownValue: (selector, value) => {
                $(`${selector} option`).each(function() {
                    if ($(this).text().trim() === value) {
                        $(this).prop('selected', true);
                        return false; // Exit loop early
                    }
                });
            }
        };

        // Event Handlers
        // Delete User
        $('#deleteUserBtn').click(function() {
            const userId = $('#userId').text();
            const userName = $('#userName').text();

            helpers.confirmAction({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar al usuario ${userName}?`,
                icon: 'warning',
                confirmText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/users/${userId}`,
                        type: 'DELETE',
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: () => {
                            modals.details.modal('hide');
                            table.row(`:contains(${userId})`).remove().draw();
                            // Mostrar el mensaje de éxito con el botón "Recargar"
                            helpers.showSuccess('¡Eliminado!', 'El usuario ha sido eliminado correctamente. \nRecarga para que los cambios sean efectivos', {
                                confirmButtonText: 'Recargar'
                            }).then((result) => {
                                // Recargar la página solo si el usuario hace clic en "Recargar"
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: () => helpers.showError('Error al eliminar el usuario')
                    });
                }
            });
        });

        // Edit User
        $('#editUserBtn').click(function() {
            const userId = $('#userId').text();
            const statusText = $('#userStatus').text().trim();
            
            modals.details.find('#userDetailsView').hide();
            modals.edit.show();
            
            const form = $('#editUserForm');
            form.attr('action', form.attr('action').replace(':user_id', userId));
            
            // Form Population
            $('#edit_user_id').val(userId);
            $('#edit_name').val($('#userName').text());
            $('#edit_email').val($('#userEmail').text());
            $('#edit_user').val($('#userUsername').text());
            
            helpers.selectDropdownValue('#edit_role', $('#userRole').text());
            helpers.selectDropdownValue('#edit_status', statusText.replace(/^[^A-Za-z]+/, '').trim());
        });

        // Form Submissions
        const handleFormSubmit = (formId, isNew = false) => {
            $(formId).on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(this);

                helpers.confirmAction({
                    title: '¿Estás seguro?',
                    text: isNew ? 'Se creará un nuevo usuario' : 'Se actualizará la información del usuario',
                    icon: 'question',
                    confirmText: isNew ? 'Sí, crear' : 'Sí, actualizar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (response) => {
                                modals[isNew ? 'newUser' : 'details'].modal('hide');
                                helpers.handleFormResponse(response, table, isNew);
                                helpers.showSuccess(
                                    isNew ? '¡Creado!' : '¡Actualizado!',
                                    `El usuario ha sido ${isNew ? 'creado' : 'actualizado'} correctamente. \nRecarga para que los cambios sean efectivos`,
                                    { 
                                        confirmButtonText: 'Recargar'
                                    }
                                ).then((result) => {
                                    // Recargar la página solo si el usuario hace clic en "Recargar"
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            },
                            error: (xhr) => helpers.showFormErrors(xhr)
                        });
                    }
                });
            });
        };

        // Initialize Form Handlers
        handleFormSubmit('#newUserForm', true);
        handleFormSubmit('#editUserForm');

        // Export Functionality
        const exportUtils = {
            initSlider: () => {
                $(document)
                    .on('change', 'input[name="exportScope"]', function() {
                        const isCustom = $(this).val() === 'custom';
                        $('#customRangeContainer').toggle(isCustom);
                        if (isCustom) {
                            const pageInfo = table.page.info();
                            $('#exportPagesSlider').attr('max', pageInfo.pages - pageInfo.page - 1);
                        }
                    })
                    .on('input change', '#exportPagesSlider', function() {
                        $('#exportPagesValue').text($(this).val());
                    });
            },

            handleExports: () => {
                // Add this for main export button
                $(document).on('click', '#exportButton', () => {
                    $('#exportColumnsModal').modal('show');
                });

                $(document).on('click', '#exportCSVBtn, #exportPDFBtn', function() {
                    // Remove both 'export' and 'Btn' from the ID
                    const exportType = $(this).attr('id')
                        .replace(/^export/, '') // Remove only the leading 'export'
                        .replace('Btn', '') // Remove 'Btn' from the end
                        .toUpperCase();

                    console.log(`Calling: export${exportType}`); 

                    if (typeof exportUtils[`export${exportType}`] !== "function") {
                        console.error(`exportUtils[export${exportType}] is undefined!`);
                        return;
                    }

                    const selectedColumns = $('.export-column:checked')
                        .map((i, el) => parseInt($(el).data('column')))
                        .get();

                    if (!selectedColumns.length) {
                        alert('Selecciona al menos una columna para exportar');
                        return;
                    }

                    const scope = $('input[name="exportScope"]:checked').val();
                    exportUtils[`export${exportType}`](selectedColumns, scope);
                    $('#exportColumnsModal').modal('hide');
                });

            },

            getTableData: (selectedColumns, scope) => {
                let headers = [];
                let data = [];

                // Obtener los encabezados de las columnas seleccionadas
                $('#table1 thead tr th').each(function(index) {
                    if (selectedColumns.indexOf(index) !== -1 && !$(this).hasClass('no-export')) {
                        headers.push($(this).text().trim());
                    }
                });

                let rowsData = [];
                if (scope === 'all') {
                    // Todos los registros filtrados
                    rowsData = table.rows({ search: 'applied' }).data().toArray();
                } else if (scope === 'custom') {
                    // Exportar desde la página actual hasta el número de páginas definido en el slider
                    let pageInfo = table.page.info();
                    let currentPage = pageInfo.page; // Página actual (0-indexada)
                    let recordsPerPage = pageInfo.length;
                    // El slider indica cuántas páginas adicionales exportar; 0 significa solo la página actual
                    let pagesToExport = parseInt($('#exportPagesSlider').val());
                    let startRecord = currentPage * recordsPerPage;
                    let endRecord = startRecord + ((pagesToExport + 1) * recordsPerPage);
                    let allData = table.rows({ search: 'applied' }).data().toArray();
                    rowsData = allData.slice(startRecord, endRecord);
                } else {
                    // Solo la página actual
                    rowsData = table.rows({ page: 'current' }).data().toArray();
                }

                // Recorrer cada fila y filtrar las columnas seleccionadas
                rowsData.forEach(function(row) {
                    let filteredRow = [];
                    // Se asume que cada fila es un array
                    row.forEach(function(cell, index) {
                        if (selectedColumns.indexOf(index) !== -1) {
                            // Si es la columna de status (índice 6), extraer solo el texto
                            if (index === 6) {
                                let tmp = document.createElement("div");
                                tmp.innerHTML = cell;
                                filteredRow.push(tmp.textContent || tmp.innerText || "");
                            } else {
                                filteredRow.push(cell);
                            }
                        }
                    });
                    data.push(filteredRow);
                });

                return { headers: headers, data: data };
            },

            // Funciones de exportación
            exportCSV: function(selectedColumns, scope) {
                const tableData = this.getTableData(selectedColumns, scope);
                const csvContent = [
                    tableData.headers.join(','),
                    ...tableData.data.map(row => row.join(','))
                ].join('\n');
                
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'users_export.csv';
                link.click();
            },

            exportPDF: function(selectedColumns, scope) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                const tableData = this.getTableData(selectedColumns, scope);
                
                doc.autoTable({
                    head: [tableData.headers],
                    body: tableData.data,
                    theme: 'grid',
                    styles: { fontSize: 8 },
                    headStyles: { fillColor: [66, 139, 202] }
                });
                
                doc.save('users_export.pdf');
            }
        };

        // Modal Management
        const modalUtils = {
            resetModals: () => {
                $([modals.newUser, modals.details]).each(function() {
                    this.on('hidden.bs.modal', () => {
                        this.find('form').trigger('reset');
                        $('#userDetailsView').show();
                        modals.edit.hide();
                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');
                    });
                });
            },

            setupUserDetails: () => {
                $(document).on('click', '.user-details-btn', function() {
                    const row = $(this).closest('tr').find('td');
                    const profilePicture = $(this).data('profile-picture') || 
                        '{{ asset("img/defaultpfp.png") }}';

                    $('#userId').text(row.eq(0).text());
                    $('#userName').text(row.eq(1).text());
                    $('#userUsername').text(row.eq(2).text());
                    $('#userEmail').text(row.eq(3).text());
                    $('#userRole').text(row.eq(4).text());
                    $('#userLocation').text(row.eq(5).text());
                    $('#userRegistrationDate').text(row.eq(6).text());
                    $('#userStatus').html(row.eq(7).html());
                    $('#userProfilePicture').attr('src', 
                        profilePicture.startsWith('http') ? profilePicture : 
                        '{{ asset("storage") }}/' + profilePicture
                    );
                    modals.details.modal('show');
                });
            }
        };

        // Initialization
        exportUtils.initSlider();
        exportUtils.handleExports();
        modalUtils.resetModals();
        modalUtils.setupUserDetails();

        // Filter Form
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
                console.log('Filter form submitted');
                let role = $('#role').val();
                let status = $('#status').val();
                let fromDate = $('#from_date').val();
                let toDate = $('#to_date').val();

                // Get the DataTable instance directly
                let table = $('#table1').DataTable();
                
                // Custom filtering function for date range
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        if (settings.nTable.id !== 'table1') {
                            return true;
                        }
                        
                        let dateStr = data[6]; // Date column index
                        
                        // If no date filters are set, include the row
                        if (!fromDate && !toDate) {
                            return true;
                        }
                        
                        let rowDate = new Date(dateStr);
                        let startDate = fromDate ? new Date(fromDate) : null;
                        let endDate = toDate ? new Date(toDate) : null;
                        
                        // Check if the date is within range
                        if (startDate && endDate) {
                            return rowDate >= startDate && rowDate <= endDate;
                        } else if (startDate) {
                            return rowDate >= startDate;
                        } else if (endDate) {
                            return rowDate <= endDate;
                        }
                        return true;
                    }
                );
                
                // Apply role filter
                if (role) {
                    table.column(4).search(role).draw();
                } else {
                    table.column(4).search('');
                }
                
                // Apply status filter
                if (status) {
                    table.column(7).search(status).draw();
                } else {
                    table.column(7).search('');
                }
                
                // Redraw the table to apply all filters
                table.draw();
                
                // Remove the custom date filter after drawing
                $.fn.dataTable.ext.search.pop();
        });

        // Hover Animations
        $(document)
            .on('mouseenter', '.toggle-container', function() {
                $(this).find('.toggle-label').css('color', '#17a2b8');
            })
            .on('mouseleave', '.toggle-container', function() {
                $(this).find('.toggle-label').css('color', '#495057');
            });
    });
</script>

<!-- External Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush


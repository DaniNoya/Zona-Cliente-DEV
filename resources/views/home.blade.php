@extends('adminlte::page')

@section('preloader')
    <img src="{{ asset('vendor/adminlte/dist/img/GlobalIcon.png') }}" class="animation__shake" width="120" height="120">
    <h4 class="mt-4 text-dark">Cargando panel de usuario...</h4>
@stop

{{-- Customize layout sections --}}
@section('title', 'Dashboard')


{{-- Setup data for datatables --}}
@php
$heads = [
    'ID',
    'Name',
    ['label' => 'Phone', 'width' => 40],
    'Status',  // Nueva columna 'Status'
    ['label' => 'Actions', 'no-export' => true, 'width' => 5],
];

$btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow order-details-btn" title="Details" data-order-id="{order_id}">
                   <i class="fa fa-lg fa-fw fa-file-alt"></i>
               </button>';

// Configuración de los datos con estados específicos
$config = [
    'data' => [
        [22, 'John Bender', '+02 (123) 123456789', 
         '<i class="fa fa-check-circle text-success"></i>', // Delivered
         '<nobr>'.str_replace('{order_id}', '22', $btnDetails).'</nobr>'],
        
        [19, 'Sophia Clemens', '+99 (987) 987654321', 
         '<i class="fa fa-truck-fast text-warning"></i>', // In transit
         '<nobr>'.str_replace('{order_id}', '19', $btnDetails).'</nobr>'],
        
        [3, 'Peter Sousa', '+69 (555) 12367345243', 
         '<i class="fa fa-clipboard text-info"></i>', // Processing/Just ordered
         '<nobr>'.str_replace('{order_id}', '3', $btnDetails).'</nobr>'],
        
        [15, 'Anna Johnson', '+01 (333) 987654321', 
         '<i class="fa fa-times-circle text-danger"></i>', // Cancelled
         '<nobr>'.str_replace('{order_id}', '15', $btnDetails).'</nobr>'],
        
        [8, 'James Smith', '+99 (555) 123456789', 
         '<i class="fa fa-check-circle text-success"></i>', // Delivered
         '<nobr>'.str_replace('{order_id}', '8', $btnDetails).'</nobr>'],
    ],
    'order' => [[1, 'asc']],
    'columns' => [null, null, null, null, ['orderable' => false]],
];
@endphp


{{-- Content body: main page content --}}
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="welcome-container">
                        <div class="welcome-icon">
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="welcome-logo img-circle" style="max-width: 100px;">
                        </div>
                        <div class="welcome-text">
                            <h1 class="m-0"><strong>Bienvenido de nuevo, {{ auth()->user()->name }}</strong></h1>
                            <div class="welcome-actions mt-2">
                                <a href="{{ route('profile') }}" class="btn btn-sm btn-info mr-2">
                                    <i class="fas fa-user-edit mr-1"></i> Ver/Editar Perfil
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Zona Cliente</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Separator between sections -->
    <!-- Section selectors in a row -->
    <div class="container-fluid mb-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="section-selector text-center section-toggle" data-target="quickAccessPanel">
                    <div class="section-icon section-toggle" data-target="quickAccessPanel">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4 class="mt-3 section-toggle" data-target="quickAccessPanel">Panel de acceso rapido</h4>
                </div>
            </div>
            <div class="col-md-5">
                <div class="section-selector text-center section-toggle" data-target="statsPanel">
                    <div class="section-icon section-toggle" data-target="statsPanel">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="mt-3 section-toggle" data-target="statsPanel">Información y Estadísticas</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse section-content" id="quickAccessPanel">
        <br>
        <div class='dashboard-components'>
            <div class='row justify-content-center'>
                <div class="col-md-3 mb-4">
                    <a href="{{ route('productos') }}" class="text-decoration-none quick-access-link">
                        <x-adminlte-small-box title="Productos" text="Consulte los productos disponibles"
                            theme="purple" icon="fas fa-box-open fa-2x" class="quick-access-box"/>
                    </a>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="{{ route('pedidos') }}" class="text-decoration-none quick-access-link">
                        <x-adminlte-small-box title="Pedidos" text="Consulte todos sus pedidos"
                            theme="teal" icon="fas fa-file-text fa-2x" class="quick-access-box"/>
                    </a>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="{{ route('proveedores') }}" class="text-decoration-none quick-access-link">
                        <x-adminlte-small-box title="Proveedores" text="Consulte los proveedores con los que trabajamos"
                            theme="orange" icon="fas fa-truck-loading fa-2x" class="quick-access-box"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
        
    <div class="collapse section-content" id="statsPanel">
        <!-- Replace the multiple rows with a single container for all cards -->
        <div class="grid" id="statsCards">    
            <div class="grid-sizer"></div>
            <div class="gutter-sizer"></div>        

            <!-- Card 1 -->
            <div class="grid-item" data-card-id="1">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: #87fa91;">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title"><strong>Compras realizadas</strong></h3>
                            <!--<a href="javascript:void(0);">Expandir informacion</a>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-dark"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times text-dark"></i>
                                </button>
                            </div>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Compras en el tiempo</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> 33.1%
                                </span>
                                <span class="text-muted">Since last month</span>
                            </p>
                        </div>
        
                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200" width="401" style="display: block; width: 401px; height: 200px;" class="chartjs-render-monitor"></canvas>
                        </div>
        
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This year
                            </span>
        
                            <span>
                                <i class="fas fa-square text-gray"></i> Last year
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="grid-item" data-card-id="2">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: lightblue;">
                        <h1 class="card-title"><strong>Últimos pedidos realizados</strong></h1>
                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="card-body table-responsive p-0">
                        <x-adminlte-datatable class='w-100 table-hover-custom' id="table1" :heads="$heads" head-theme='info'>
                            @foreach($config['data'] as $row)
                                <tr class="table-row-hover">
                                    @foreach($row as $key => $cell)
                                        <td @if($key == 0) style="width: 50px;" @endif>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Nuevo pedido</a>
                            <a href="{{ route('pedidos') }}" class="btn btn-sm btn-secondary float-right">Ver todos los pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="grid-item" data-card-id="3">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-header border-0 text-black" style="background-color: rgb(244, 110, 248);">
                        <h3 class="card-title"><strong>Productos Top</strong></h3>

                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <li class="item" data-product-id="1">
                                <div class="product-img">
                                    <img src="https://thumb.pccomponentes.com/w-530-530/articles/1084/10843920/1598-samsung-tq65q60dauxxc-65-qled-ultrahd-4k-hdr10-tizen.jpg" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">Samsung TV
                                        <span class="badge badge-warning float-right">1800 Puntos</span></a>
                                    <span class="product-description">
                                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                                    </span>
                                </div>
                            </li>
                            <div class="product-expanded" id="product-expanded-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="https://thumb.pccomponentes.com/w-530-530/articles/1084/10843920/1598-samsung-tq65q60dauxxc-65-qled-ultrahd-4k-hdr10-tizen.jpg" alt="Product Image" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>Samsung TV - Detalles</h4>
                                        <p>Disfruta de una calidad de imagen excepcional con esta Smart TV Samsung de 32 pulgadas. Con resolución 1080p y tecnología LED, ofrece colores vibrantes y un contraste nítido.</p>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Modelo:</strong> Samsung 32J5205</p>
                                                <p><strong>Resolución:</strong> 1080p Full HD</p>
                                                <p><strong>Conectividad:</strong> Wi-Fi, HDMI, USB</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Puntos necesarios:</strong> 1800</p>
                                                <p><strong>Disponibilidad:</strong> En stock</p>
                                                <button class="btn btn-warning btn-sm inspect-product" data-product-id="1">Inspeccionar producto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <li class="item" data-product-id="2">
                                <div class="product-img">
                                    <img src="https://contents.mediadecathlon.com/p2579112/k$953dede5738b886e3a1127136a2888fa/sq/bicicleta-de-montana-26-pulgadas-aluminio-rockrider-st-500-azul-9-12-anos.jpg?format=auto&f=969x969" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">Bicicleta
                                        <span class="badge badge-info float-right">700 Puntos</span></a>
                                    <span class="product-description">
                                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                    </span>
                                </div>
                            </li>
                            <div class="product-expanded" id="product-expanded-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="https://contents.mediadecathlon.com/p2579112/k$953dede5738b886e3a1127136a2888fa/sq/bicicleta-de-montana-26-pulgadas-aluminio-rockrider-st-500-azul-9-12-anos.jpg?format=auto&f=969x969" alt="Product Image" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>Bicicleta - Detalles</h4>
                                        <p>Bicicleta de montaña de alta calidad con 7 velocidades, perfecta para terrenos variados. Construcción robusta y duradera con un diseño elegante en azul marino.</p>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Modelo:</strong> Mongoose Dolomite</p>
                                                <p><strong>Tamaño:</strong> 26 pulgadas</p>
                                                <p><strong>Velocidades:</strong> 7</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Puntos necesarios:</strong> 700</p>
                                                <p><strong>Disponibilidad:</strong> En stock</p>
                                                <button class="btn btn-info btn-sm inspect-product" data-product-id="2">Inspeccionar producto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <li class="item" data-product-id="3">
                                <div class="product-img">
                                    <img src="https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/82afef17-15e1-428d-90c9-779de0b5881f-1_7862d190-ec1e-4a64-b264-4d36b91d1862.jpg" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">
                                        Xbox One <span class="badge badge-danger float-right">
                                        350 Puntos
                                    </span>
                                    </a>
                                    <span class="product-description">
                                        Xbox One Console Bundle with Halo Master Chief Collection.
                                    </span>
                                </div>
                            </li>
                            <div class="product-expanded" id="product-expanded-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/82afef17-15e1-428d-90c9-779de0b5881f-1_7862d190-ec1e-4a64-b264-4d36b91d1862.jpg" alt="Product Image" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>Xbox One - Detalles</h4>
                                        <p>Consola Xbox One con el bundle de Halo Master Chief Collection. Disfruta de los mejores juegos de la saga Halo remasterizados en alta definición.</p>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Modelo:</strong> Xbox One S</p>
                                                <p><strong>Almacenamiento:</strong> 1TB</p>
                                                <p><strong>Incluye:</strong> Halo Master Chief Collection</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Puntos necesarios:</strong> 350</p>
                                                <p><strong>Disponibilidad:</strong> Últimas unidades</p>
                                                <button class="btn btn-danger btn-sm inspect-product" data-product-id="3">Inspeccionar producto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <li class="item" data-product-id="4">
                                <div class="product-img">
                                    <img src="https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/ae29148c-0fe7-4b12-baf0-0ae44208e169-1_9daa420c-48da-451e-a0e6-fb8e7fb5e11f.jpg" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                                        <span class="badge badge-success float-right">399 Puntos</span></a>
                                    <span class="product-description">
                                        PlayStation 4 500GB Console (PS4)
                                    </span>
                                </div>
                            </li>
                            <div class="product-expanded" id="product-expanded-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/ae29148c-0fe7-4b12-baf0-0ae44208e169-1_9daa420c-48da-451e-a0e6-fb8e7fb5e11f.jpg" alt="Product Image" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h4>PlayStation 4 - Detalles</h4>
                                        <p>Consola PlayStation 4 con 500GB de almacenamiento. Disfruta de los mejores juegos exclusivos de Sony con gráficos de alta calidad.</p>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Modelo:</strong> PlayStation 4 Slim</p>
                                                <p><strong>Almacenamiento:</strong> 500GB</p>
                                                <p><strong>Incluye:</strong> 1 mando DualShock 4</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Puntos necesarios:</strong> 399</p>
                                                <p><strong>Disponibilidad:</strong> En stock</p>
                                                <button class="btn btn-success btn-sm inspect-product" data-product-id="4">Inspeccionar producto</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <!-- /.card-footer -->
                    <div class="card-footer text-center">
                        <a href="javascript:void(0)" class="uppercase">Ver todos los productos TOP</a>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="grid-item" data-card-id="5">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: lightblue;">
                        <h1 class="card-title"><strong>Últimos pedidos realizados CARD 4</strong></h1>
                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="card-body table-responsive p-0">
                        <x-adminlte-datatable class='w-100 table-hover-custom' id="table1" :heads="$heads" head-theme='info'>
                            @foreach($config['data'] as $row)
                                <tr class="table-row-hover">
                                    @foreach($row as $key => $cell)
                                        <td @if($key == 0) style="width: 50px;" @endif>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Nuevo pedido</a>
                            <a href="{{ route('pedidos') }}" class="btn btn-sm btn-secondary float-right">Ver todos los pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="grid-item" data-card-id="5">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: lightblue;">
                        <h1 class="card-title"><strong>Últimos pedidos realizados CARD 5</strong></h1>
                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="card-body table-responsive p-0">
                        <x-adminlte-datatable class='w-100 table-hover-custom' id="table1" :heads="$heads" head-theme='info'>
                            @foreach($config['data'] as $row)
                                <tr class="table-row-hover">
                                    @foreach($row as $key => $cell)
                                        <td @if($key == 0) style="width: 50px;" @endif>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Nuevo pedido</a>
                            <a href="{{ route('pedidos') }}" class="btn btn-sm btn-secondary float-right">Ver todos los pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="grid-item" data-card-id="6">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: lightblue;">
                        <h1 class="card-title"><strong>Últimos pedidos realizados CARD 6</strong></h1>
                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="card-body table-responsive p-0">
                        <x-adminlte-datatable class='w-100 table-hover-custom' id="table1" :heads="$heads" head-theme='info'>
                            @foreach($config['data'] as $row)
                                <tr class="table-row-hover">
                                    @foreach($row as $key => $cell)
                                        <td @if($key == 0) style="width: 50px;" @endif>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Nuevo pedido</a>
                            <a href="{{ route('pedidos') }}" class="btn btn-sm btn-secondary float-right">Ver todos los pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 7 -->
            <div class="grid-item" data-card-id="7">
                <div class="card">
                    <div class="card-header border-0 text-black" style="background-color: lightblue;">
                        <h1 class="card-title"><strong>Últimos pedidos realizados CARD 7</strong></h1>
                        <!--<div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times text-dark"></i>
                            </button>
                        </div>-->
                    </div>
                    <div class="card-body table-responsive p-0">
                        <x-adminlte-datatable class='w-100 table-hover-custom' id="table1" :heads="$heads" head-theme='info'>
                            @foreach($config['data'] as $row)
                                <tr class="table-row-hover">
                                    @foreach($row as $key => $cell)
                                        <td @if($key == 0) style="width: 50px;" @endif>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                        <div class="card-footer clearfix">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Nuevo pedido</a>
                            <a href="{{ route('pedidos') }}" class="btn btn-sm btn-secondary float-right">Ver todos los pedidos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Product Inspection Modal -->
    <div class="modal fade" id="productInspectionModal" tabindex="-1" role="dialog" aria-labelledby="productInspectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productInspectionModalLabel">Detalles del Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded">
                        </div>
                        <div class="col-md-6">
                            <h3 id="modalProductTitle"></h3>
                            <p id="modalProductDescription" class="text-muted"></p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5>Especificaciones</h5>
                                    <div id="modalProductSpecs"></div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Información de Canje</h5>
                                    <div id="modalProductInfo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="modalRedeemButton">Canjear por puntos</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Detalles del Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Información del Pedido</h3>
                                </div>
                                <div class="card-body">
                                    <p><strong>Número de Pedido:</strong> <span id="orderNumber"></span></p>
                                    <p><strong>Cliente:</strong> <span id="orderCustomer"></span></p>
                                    <p><strong>Fecha:</strong> <span id="orderDate"></span></p>
                                    <p><strong>Estado:</strong> <span id="orderStatus"></span></p>
                                    <p><strong>Método de Pago:</strong> <span id="orderPayment"></span></p>
                                    <p><strong>Dirección de Envío:</strong> <span id="orderShipping"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Resumen del Pedido</h3>
                                </div>
                                <div class="card-body">
                                    <p><strong>Subtotal:</strong> <span id="orderSubtotal"></span></p>
                                    <p><strong>Envío:</strong> <span id="orderShippingCost"></span></p>
                                    <p><strong>Impuestos:</strong> <span id="orderTaxes"></span></p>
                                    <p><strong>Total:</strong> <span id="orderTotal" class="text-bold"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Productos</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unitario</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orderProductsList">
                                            <!-- Products will be inserted here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="trackOrderButton">Seguimiento</button>
                </div>
            </div>
        </div>
    </div>

@stop

{{-- Push extra CSS --}}
@push('css')
    <style>
        /* ======================================
           GRID STYLES
           ====================================== */
        .grid {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            position: relative;
            padding: 5px;
            margin: 0 auto;
        }

        /* Grid items */
        .grid-item {
            width: 25%;
            margin-bottom: 10px;
            /*background-color: #f39c12;*/
            border-radius: 8px;
            text-align: center;
            color: rgb(0, 0, 0);
            /*font-weight: bold;*/
            cursor: grab;
            user-select: none;
        }

        /* Dragging states */
        .grid-item.is-dragging,
        .grid-item.is-positioning-post-drag {
            z-index: 2;
            cursor: grabbing;
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background: rgba(204, 153, 0, 0.8);
        }

        /* Packery drop placeholder */
        .packery-drop-placeholder {
            outline: 3px dashed hsla(0, 0%, 0%, 0.5);
            outline-offset: -6px;
            transition: transform 0.2s;
        }

        /* Hide scrollbars */
        ::-webkit-scrollbar {
            display: none;
        }
        
        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Animations */
        @keyframes cardAppear {
            from { transform: translateY(10px); opacity: 0.8; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* ======================================
           WELCOME SECTION STYLES
           ====================================== */
        .welcome-container {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,240,240,0.9) 100%);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 5px solid #17a2b8;
        }
        
        .welcome-container:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .welcome-icon {
            margin-right: 20px;
            background: rgba(23, 162, 184, 0.1);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .welcome-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
            transition: all 0.3s ease;
        }
        
        .welcome-container:hover .welcome-icon {
            transform: rotate(360deg);
            background: rgba(23, 162, 184, 0.2);
        }
        
        .welcome-container:hover .welcome-logo {
            transform: scale(1.1);
        }
        
        .welcome-text h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 5px;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.1);
        }
        
        .welcome-subtitle {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 1rem;
        }
        
        .welcome-actions {
            margin-top: 10px;
        }
        
        .welcome-actions .btn {
            transition: all 0.3s ease;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .welcome-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .welcome-actions .btn-info {
            background: linear-gradient(45deg, #17a2b8, #20c997);
            border: none;
        }
        
        .welcome-actions .btn-outline-danger {
            border: 1px solid #dc3545;
            color: #dc3545;
            background: transparent;
        }
        
        .welcome-actions .btn-outline-danger:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        /* ======================================
           FORM ELEMENTS STYLES
           ====================================== */
        .custom-select-lg {
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
        }

        .custom-select-lg:hover {
            border-color: #17a2b8;
            box-shadow: 0 0 10px rgba(23, 162, 184, 0.2);
        }

        .custom-select-lg:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 15px rgba(23, 162, 184, 0.3);
        }

        /* ======================================
           SECTION HEADERS & DIVIDERS
           ====================================== */
        .section-header {
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-header h2 {
            font-weight: 600;
            font-size: 1.8rem;
            color: #343a40;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .section-divider {
            position: relative;
            height: 4px;
            margin-bottom: 25px;
        }
        
        .section-toggle {
            cursor: pointer;
        }
        
        /* Section separators */
        .section-separator {
            position: relative;
            padding: 20px 0;
            margin: 30px 0;
        }
        
        .separator-content {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .separator-line {
            height: 3px;
            width: 100px;
            background: linear-gradient(90deg, rgba(23, 162, 184, 0.2), rgba(23, 162, 184, 0.8));
            display: inline-block;
        }
        
        .separator-line:first-child {
            background: linear-gradient(90deg, rgba(23, 162, 184, 0.8), rgba(23, 162, 184, 0.2));
        }
        
        .separator-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #17a2b8, #20c997);
            border-radius: 50%;
            margin: 0 15px;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }
        
        .separator-icon:hover {
            transform: rotate(360deg);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        
        .separator-icon.active {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            transform: rotate(180deg);
        }
        
        .separator-text {
            font-weight: 600;
            color: #495057;
            font-size: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .separator-text.active {
            color: #17a2b8;
        }
        
        .section-separator:hover .separator-text {
            color: #17a2b8;
        }
        
        /* Clickable section indicator */
        .section-separator:hover .separator-icon:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: pulse 1.5s infinite;
        }
        
        /* Section content animations */
        .section-content {
            transition: all 0.5s ease;
            overflow: hidden;
        }
        
        .section-content.collapsing {
            transition-duration: 0.5s;
        }
        
        /* Divider line */
        .divider-line {
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #6f42c1, #20c997);
            border-radius: 2px;
        }

        /* Card styling */
        .grid-item .card {
            margin-bottom: 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: auto;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: none;
            animation: cardAppear 0.4s ease-out;
            cursor: default;
        }

        .grid-item .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .grid-item .card .card-body {
            transition: all 0.3s ease;
            height: auto;
            overflow: hidden;
        }

        /* Card header styling - drag handle */
        .grid-item .card-header {
            cursor: grab;
            transition: background-color 0.3s ease;
        }
        
        /* Visual feedback when dragging */
        .grid-item.is-dragging .card {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }
        
        .grid-item.is-dragging .card-header {
            background-color: rgba(0, 123, 255, 0.1) !important;
            cursor: grabbing;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .grid-item {
                width: calc(50% - 10px);
            }
        }

        @media (max-width: 576px) {
            .grid-item {
                width: calc(100% - 10px);
            }
        }
            z-index: 2;
            cursor: grabbing;
        }

        .grid-item .card-header {
            cursor: grab;
            user-select: none;
        }
        
        /* Edit mode styles */
        #statsPanel.edit-mode {
            background-color: rgba(0, 123, 255, 0.05);
            border: 2px dashed rgba(0, 123, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-card {
                flex: 1 0 100%; /* Full width on mobile */
            }
        }

        /* ======================================
           TABLE STYLES
           ====================================== */
        #table1_wrapper {
            overflow: visible !important;
        }
        
        /* Hide pagination and info */
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_info {
            display: none !important;
        }
        
        /* Table hover effects */
        .table-hover-custom tr.table-row-hover {
            transition: all 0.3s ease;
            position: relative;
            transform-origin: center;
        }
        
        .table-hover-custom tr.table-row-hover:hover {
            transform: scale(1.03) translateY(-8px);
            background-color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            border-radius: 8px;
            position: relative;
        }
        
        .table-hover-custom tr.table-row-hover:hover td {
            background-color: rgba(0, 123, 255, 0.1);
            border-top: none;
            border-bottom: none;
            position: relative;
        }

        /* ======================================
           PRODUCTS LIST STYLES
           ====================================== */

        /* Ensure parent container respects height changes */
        .products-list {
            display: flex;
            flex-direction: column;
            gap: 2px; /* Ensure spacing is maintained */
        }

        .products-list .item {
            transition: all 0.3s ease;
            position: relative;
            transform-origin: center;
            z-index: 1;
            cursor: pointer;
        }
        
        .products-list .item:hover {
            transform: scale(1.03) translateY(-8px);
            background-color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            z-index: 100;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .products-list .item:hover .product-info {
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        /* Active product state */
        .products-list .item.active {
            border-radius: 8px 8px 0 0;
            transform: scale(1.03);
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 100;
            margin: 10px 0 0 0;
        }
        
        /* Expanded product card */
        /* Ensure the expanded product takes up space */
        .product-expanded {
            height: 0;
            overflow: hidden;
            transition: all 0.5s ease;
            background-color: #f8f9fa;
            border-radius: 0 0 8px 8px;
            margin-top: 0; /* Remove negative margin */
            position: relative;
            z-index: 998;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 0 15px;
            display: block; /* Change from 'none' to 'block' */
        }

        /* Active state should push content down */
        .product-expanded.active {
            height: auto;
            padding: 20px 15px;
            margin-bottom: 20px;
            display: block;
        }

        /* ======================================
           MODAL STYLES
           ====================================== */
        #productInspectionModal .modal-header,
        #orderDetailsModal .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }
        
        #productInspectionModal .modal-footer,
        #orderDetailsModal .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }
        
        #productInspectionModal img {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        #productInspectionModal img:hover {
            transform: scale(1.03);
        }

        /* ======================================
           3D MODEL VIEWER STYLES
           ====================================== */
        model-viewer {
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            --poster-color: transparent;
            min-height: 400px;
            width: 100%;
        }
        
        #toggleView {
            transition: all 0.3s ease;
        }
        
        #toggleView:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        #productViewContainer {
            min-height: 400px;
        }

        /* ======================================
           QUICK ACCESS PANEL STYLES
           ====================================== */
        .quick-access-link {
            display: block;
            transition: all 0.3s ease;
        }

        .quick-access-link:hover {
            transform: translateY(-5px);
        }

        .quick-access-box {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .quick-access-box:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .col-md-3 {
                padding: 0 10px;
            }
            
            .quick-access-box {
                margin-bottom: 15px;
            }
            
            .quick-access-link:hover {
                transform: translateY(-3px);
            }
        }

        /* ======================================
           SECTION SELECTOR STYLES
           ====================================== */

        .grid-item {
            width: calc(30% - 5px);
            margin: 5px;
            float: left;
        }

        .grid-item.is-dragging {
            z-index: 2;
            cursor: grabbing !important;
        }

        .grid-item .card {
            margin-bottom: 0;
        }

        .section-selector {
            cursor: pointer;
            padding: 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: #e1e6eb;
            border: 2px solid transparent;
            margin-bottom: 20px;
            border-left: 5px solid #17b855;
        }
        
        .section-selector:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .section-selector.active {
            border-color: #17a2b8;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            font-size: 30px;
            color: #17a2b8;
            transition: all 0.3s ease;
        }
        
        .section-selector:hover .section-icon {
            transform: rotate(15deg);
        }
        
        .section-selector.active .section-icon {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
        }
    </style>
@endpush


{{-- Push extra scripts --}}
@push('js')

    <!-- Intial console log -->
    <script>console.log("Loading js scripts...");</script>

    <!-- Bootstrap 5 (Without jQuery Dependency) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Packery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/packery/2.1.2/packery.pkgd.min.js"></script>
    <!-- Draggabilly -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/draggabilly/3.0.0/draggabilly.pkgd.min.js"></script>
    <!-- ImagesLoaded -->
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>   

    <!-- Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Sales chart initialization -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const salesData = {
                labels: ['January', 'February', 'March', 'April'],
                datasets: [{
                    label: 'Articulos comprados',
                    data: [1200, 1500, 1800, 2000],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            };

            const ctx = document.getElementById('sales-chart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line', 
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>

    <!-- Product Types Chart initialization -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productData = {
                labels: ['Electrónicos', 'Ropa', 'Hogar', 'Deportes', 'Juguetes'],
                datasets: [{
                    label: 'Cantidad vendida',
                    data: [150, 90, 80, 70, 60],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const ctx = document.getElementById('productTypeChart').getContext('2d');
            const productTypeChart = new Chart(ctx, {
                type: 'bar',
                data: productData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de productos'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Categorías'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Cantidad vendida: ${context.parsed.y} unidades`;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

    <!-- Product expansion functionality && Grid functionality -->
    <script>
        let pckry;
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.querySelector('.grid');

            // Initialize Packery after collapse animation
            document.querySelector('#statsPanel').addEventListener('shown.bs.collapse', function() {
                if (!pckry) {
                    pckry = new Packery(grid, {
                        itemSelector: '.grid-item',
                        percentPosition: true,
                        gutter: '.gutter-sizer',
                        columnWidth: '.grid-sizer'
                    });
                    initializeDraggable();
                }
                pckry.layout();
            });

            function initializeDraggable() {
                const draggies = [];

                document.querySelectorAll('.grid-item').forEach(function(itemElem) {
                    const draggie = new Draggabilly(itemElem, {
                        handle: '.card-header',
                        containment: true
                    });

                    pckry.bindDraggabillyEvents(draggie);
                    draggies.push(draggie);

                    // Drag Start Event
                    draggie.on('dragStart', function() {
                        itemElem.classList.add('is-dragging');
                        itemElem.style.zIndex = '1000';
                    });

                    // Drag End Event
                    draggie.on('dragEnd', function() {
                        itemElem.classList.remove('is-dragging');
                        itemElem.style.zIndex = '';
                        pckry.layout();
                        saveLayout(); // Save layout after dragging
                    });
                });
            }

            // Force layout after images load
            function layoutAfterImagesLoad() {
                imagesLoaded(grid, function() {
                    pckry.layout();
                });
            }

            // Save Layout to LocalStorage
            function saveLayout() {
                if (!pckry) return;
                const positions = pckry.getItemElements().map((item) => {
                    return {
                        id: item.getAttribute('data-card-id'),
                        position: pckry.getItem(item).rect // Store Packery's position
                    };
                });
                localStorage.setItem('gridLayout', JSON.stringify(positions));
            }


            // Load Layout from LocalStorage
            function loadSavedLayout() {
                const savedLayout = localStorage.getItem('gridLayout');
                if (savedLayout && pckry) {
                    const positions = JSON.parse(savedLayout);
                    positions.forEach(pos => {
                        const item = document.querySelector(`[data-card-id="${pos.id}"]`);
                        if (item) {
                            // Apply position properly
                            item.style.transform = `translate(${pos.position.x}px, ${pos.position.y}px)`;
                        }
                    });
                    pckry.layout();
                }
            }

            // Load layout after panel is shown
            document.querySelector('#statsPanel').addEventListener('shown.bs.collapse', function() {
                loadSavedLayout();
                layoutAfterImagesLoad();
            });

            // Clear LocalStorage when refreshing (Optional)
            window.addEventListener('beforeunload', function() {
                localStorage.removeItem('gridLayout');
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const productItems = document.querySelectorAll('.products-list .item');

            productItems.forEach(item => {
                item.addEventListener('click', function() {
                    
                    const productId = this.getAttribute('data-product-id');
                    const expandedContent = document.getElementById('product-expanded-' + productId);
                    
                    // Close any other expanded items
                    document.querySelectorAll('.product-expanded.active').forEach(expanded => {
                        if (expanded.id !== 'product-expanded-' + productId) {
                            expanded.classList.remove('active');
                            const relatedItem = document.querySelector(`.item[data-product-id="${expanded.id.replace('product-expanded-', '')}"]`);
                            if (relatedItem) relatedItem.classList.remove('active');
                        }
                    });

                    // Toggle current item
                    this.classList.toggle('active');
                    expandedContent.classList.toggle('active');
                    
                    pckry.layout();
                });
            });
        });
    </script>
    
    <!-- Product inspection modal functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize close buttons for both modals
            ['productInspectionModal', 'orderDetailsModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                const closeButtons = modal.querySelectorAll('[data-dismiss="modal"]');
                
                closeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        $(`#${modalId}`).modal('hide');
                    });
                });
            });
            // Product data with 3D models
            const products = {
                1: {
                    title: "Samsung TV",
                    image: "https://thumb.pccomponentes.com/w-530-530/articles/1084/10843920/1598-samsung-tq65q60dauxxc-65-qled-ultrahd-4k-hdr10-tizen.jpg",
                    description: "Disfruta de una calidad de imagen excepcional con esta Smart TV Samsung de 32 pulgadas. Con resolución 1080p y tecnología LED, ofrece colores vibrantes y un contraste nítido.",
                    specs: `
                        <p><strong>Modelo:</strong> Samsung 32J5205</p>
                        <p><strong>Resolución:</strong> 1080p Full HD</p>
                        <p><strong>Conectividad:</strong> Wi-Fi, HDMI, USB</p>
                        <p><strong>Tamaño de pantalla:</strong> 32 pulgadas</p>
                        <p><strong>Tecnología:</strong> LED Smart TV</p>
                    `,
                    info: `
                        <p><strong>Puntos necesarios:</strong> 1800</p>
                        <p><strong>Disponibilidad:</strong> En stock</p>
                        <p><strong>Tiempo de entrega:</strong> 3-5 días hábiles</p>
                        <p><strong>Garantía:</strong> 2 años</p>
                    `,
                    buttonClass: "btn-warning",
                    model3D: "{{ asset('assets/tv.glb') }}"
                },
                2: {
                    title: "Bicicleta",
                    image: "https://contents.mediadecathlon.com/p2579112/k$953dede5738b886e3a1127136a2888fa/sq/bicicleta-de-montana-26-pulgadas-aluminio-rockrider-st-500-azul-9-12-anos.jpg?format=auto&f=969x969",
                    description: "Bicicleta de montaña de alta calidad con 7 velocidades, perfecta para terrenos variados. Construcción robusta y duradera con un diseño elegante en azul marino.",
                    specs: `
                        <p><strong>Modelo:</strong> Mongoose Dolomite</p>
                        <p><strong>Tamaño:</strong> 26 pulgadas</p>
                        <p><strong>Velocidades:</strong> 7</p>
                        <p><strong>Material del cuadro:</strong> Aluminio</p>
                        <p><strong>Peso:</strong> 14 kg</p>
                    `,
                    info: `
                        <p><strong>Puntos necesarios:</strong> 700</p>
                        <p><strong>Disponibilidad:</strong> En stock</p>
                        <p><strong>Tiempo de entrega:</strong> 5-7 días hábiles</p>
                        <p><strong>Garantía:</strong> 1 año</p>
                    `,
                    buttonClass: "btn-info",
                    model3D: "{{ asset('assets/bike.glb') }}"
                },
                3: {
                    title: "Xbox One",
                    image: "https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/82afef17-15e1-428d-90c9-779de0b5881f-1_7862d190-ec1e-4a64-b264-4d36b91d1862.jpg",
                    description: "Consola Xbox One con el bundle de Halo Master Chief Collection. Disfruta de los mejores juegos de la saga Halo remasterizados en alta definición.",
                    specs: `
                        <p><strong>Modelo:</strong> Xbox One S</p>
                        <p><strong>Almacenamiento:</strong> 1TB</p>
                        <p><strong>Incluye:</strong> Halo Master Chief Collection</p>
                        <p><strong>Controladores:</strong> 1 mando inalámbrico</p>
                        <p><strong>Conectividad:</strong> HDMI, USB, Ethernet, Wi-Fi</p>
                    `,
                    info: `
                        <p><strong>Puntos necesarios:</strong> 350</p>
                        <p><strong>Disponibilidad:</strong> Últimas unidades</p>
                        <p><strong>Tiempo de entrega:</strong> 2-4 días hábiles</p>
                        <p><strong>Garantía:</strong> 1 año</p>
                    `,
                    buttonClass: "btn-danger",
                    model3D: "{{ asset('assets/xbox.glb') }}"
                },
                4: {
                    title: "PlayStation 4",
                    image: "https://www.backmarket.es/cdn-cgi/image/format%3Dauto%2Cquality%3D75%2Cwidth%3D640/https://d2e6ccujb3mkqf.cloudfront.net/ae29148c-0fe7-4b12-baf0-0ae44208e169-1_9daa420c-48da-451e-a0e6-fb8e7fb5e11f.jpg",
                    description: "Consola PlayStation 4 con 500GB de almacenamiento. Disfruta de los mejores juegos exclusivos de Sony con gráficos de alta calidad.",
                    specs: `
                        <p><strong>Modelo:</strong> PlayStation 4 Slim</p>
                        <p><strong>Almacenamiento:</strong> 500GB</p>
                        <p><strong>Incluye:</strong> 1 mando DualShock 4</p>
                        <p><strong>Conectividad:</strong> HDMI, USB, Ethernet, Wi-Fi</p>
                        <p><strong>Resolución:</strong> Hasta 1080p</p>
                    `,
                    info: `
                        <p><strong>Puntos necesarios:</strong> 399</p>
                        <p><strong>Disponibilidad:</strong> En stock</p>
                        <p><strong>Tiempo de entrega:</strong> 3-5 días hábiles</p>
                        <p><strong>Garantía:</strong> 1 año</p>
                    `,
                    buttonClass: "btn-success",
                    model3D: "{{ asset('assets/ps4.glb') }}"
                }
            };
            
            // Handle product inspection
            const inspectButtons = document.querySelectorAll('.inspect-product');
            
            inspectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const productId = this.getAttribute('data-product-id');
                    const product = products[productId];
                    
                    // Update modal structure to include 3D viewer if not already present
                    let modalBody = document.querySelector('#productInspectionModal .modal-body .row');
                    if (!document.getElementById('modalProduct3D')) {
                        const imageCol = modalBody.querySelector('.col-md-6');
                        imageCol.innerHTML = `
                            <div id="productViewContainer">
                                <img id="modalProductImage" src="${product.image}" alt="Product Image" class="img-fluid rounded">
                                <model-viewer id="modalProduct3D" 
                                    style="display: none; width: 100%; height: 500px;"
                                    camera-controls
                                    auto-rotate
                                    shadow-intensity="1"
                                    environment-image="neutral"
                                    exposure="0.5">
                                </model-viewer>
                                <button id="toggleView" class="btn btn-outline-primary mt-2 w-100">
                                    <i class="fas fa-cube"></i> Ver en 3D
                                </button>
                            </div>
                        `;
                    } else {
                        // Just update the image
                        document.getElementById('modalProductImage').src = product.image;
                    }
                    
                    // Fill modal with product data
                    document.getElementById('modalProductTitle').textContent = product.title;
                    document.getElementById('modalProductDescription').textContent = product.description;
                    document.getElementById('modalProductSpecs').innerHTML = product.specs;
                    document.getElementById('modalProductInfo').innerHTML = product.info;
                    
                    // Set up 3D viewer
                    const modelViewer = document.getElementById('modalProduct3D');
                    const toggleButton = document.getElementById('toggleView');
                    const imageView = document.getElementById('modalProductImage');
                    
                    if (product.model3D) {
                        modelViewer.src = product.model3D;
                        toggleButton.style.display = 'block';
                        
                        toggleButton.onclick = function() {
                            const is3DVisible = modelViewer.style.display !== 'none';
                            modelViewer.style.display = is3DVisible ? 'none' : 'block';
                            imageView.style.display = is3DVisible ? 'block' : 'none';
                            toggleButton.innerHTML = is3DVisible ? 
                                '<i class="fas fa-cube"></i> Ver en 3D' : 
                                '<i class="fas fa-image"></i> Ver imagen';
                        };
                    } else {
                        toggleButton.style.display = 'none';
                        modelViewer.style.display = 'none';
                        imageView.style.display = 'block';
                    }
                    
                    // Set redeem button class
                    const redeemButton = document.getElementById('modalRedeemButton');
                    redeemButton.className = 'btn ' + product.buttonClass;
                    
                    // Show the modal
                    $('#productInspectionModal').modal('show');
                });
            });
        });
    </script>

    <!-- 3D Model Viewer -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

    <!-- Order details modal functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample order data
            const orders = {
                '22': {
                    number: 'ORD-22-2023',
                    customer: 'John Bender',
                    date: '15/05/2023',
                    status: 'delivered',
                    statusDisplay: '<span class="badge badge-success">Entregado</span>',
                    statusIcon: '<i class="fa fa-check-circle"></i>',
                    payment: 'Tarjeta de Crédito',
                    shipping: 'Calle Principal 123, Ciudad',
                    subtotal: '€245.00',
                    shippingCost: '€15.00',
                    taxes: '€54.60',
                    total: '€314.60',
                    products: [
                        { name: 'Samsung TV 32"', quantity: 1, price: '€199.00', total: '€199.00' },
                        { name: 'Cable HDMI 2m', quantity: 2, price: '€12.00', total: '€24.00' },
                        { name: 'Soporte de pared', quantity: 1, price: '€22.00', total: '€22.00' }
                    ]
                },
                '19': {
                    number: 'ORD-19-2023',
                    customer: 'Sophia Clemens',
                    date: '10/05/2023',
                    status: 'transit',
                    statusDisplay: '<span class="badge badge-warning">En tránsito</span>',
                    statusIcon: '<i class="fa fa-truck-fast"></i>',
                    payment: 'PayPal',
                    shipping: 'Avenida Central 456, Ciudad',
                    subtotal: '€399.00',
                    shippingCost: '€0.00',
                    taxes: '€83.79',
                    total: '€482.79',
                    products: [
                        { name: 'PlayStation 4 Slim', quantity: 1, price: '€299.00', total: '€299.00' },
                        { name: 'Mando adicional', quantity: 1, price: '€59.00', total: '€59.00' },
                        { name: 'Juego FIFA 23', quantity: 1, price: '€41.00', total: '€41.00' }
                    ]
                },
                '3': {
                    number: 'ORD-03-2023',
                    customer: 'Peter Sousa',
                    date: '05/05/2023',
                    status: 'processing',
                    statusDisplay: '<span class="badge badge-info">Procesando</span>',
                    statusIcon: '<i class="fa fa-clipboard"></i>',
                    payment: 'Transferencia Bancaria',
                    shipping: 'Calle Secundaria 789, Ciudad',
                    subtotal: '€700.00',
                    shippingCost: '€25.00',
                    taxes: '€152.25',
                    total: '€877.25',
                    products: [
                        { name: 'Bicicleta de montaña', quantity: 1, price: '€700.00', total: '€700.00' }
                    ]
                },
                '15': {
                    number: 'ORD-15-2023',
                    customer: 'Anna Johnson',
                    date: '01/05/2023',
                    status: 'cancelled',
                    statusDisplay: '<span class="badge badge-danger">Cancelado</span>',
                    statusIcon: '<i class="fa fa-times-circle"></i>',
                    payment: 'Tarjeta de Débito',
                    shipping: 'Plaza Mayor 321, Ciudad',
                    subtotal: '€350.00',
                    shippingCost: '€15.00',
                    taxes: '€76.65',
                    total: '€441.65',
                    products: [
                        { name: 'Xbox One S', quantity: 1, price: '€350.00', total: '€350.00' }
                    ]
                },
                '8': {
                    number: 'ORD-08-2023',
                    customer: 'James Smith',
                    date: '28/04/2023',
                    status: 'delivered',
                    statusDisplay: '<span class="badge badge-success">Entregado</span>',
                    statusIcon: '<i class="fa fa-check-circle"></i>',
                    payment: 'Efectivo contra reembolso',
                    shipping: 'Avenida Principal 654, Ciudad',
                    subtotal: '€1,200.00',
                    shippingCost: '€0.00',
                    taxes: '€252.00',
                    total: '€1,452.00',
                    products: [
                        { name: 'Portátil HP Pavilion', quantity: 1, price: '€899.00', total: '€899.00' },
                        { name: 'Monitor 24"', quantity: 1, price: '€199.00', total: '€199.00' },
                        { name: 'Teclado inalámbrico', quantity: 1, price: '€49.00', total: '€49.00' },
                        { name: 'Ratón inalámbrico', quantity: 1, price: '€29.00', total: '€29.00' },
                        { name: 'Auriculares con micrófono', quantity: 1, price: '€24.00', total: '€24.00' }
                    ]
                }
            };
            
            // Handle order detail buttons
            const orderDetailButtons = document.querySelectorAll('.order-details-btn');
            
            orderDetailButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const orderId = this.getAttribute('data-order-id');
                    const order = orders[orderId];
                    
                    if (!order) {
                        console.error('Order not found:', orderId);
                        return;
                    }
                    
                    // Fill modal with order data
                    document.getElementById('orderNumber').textContent = order.number;
                    document.getElementById('orderCustomer').textContent = order.customer;
                    document.getElementById('orderDate').textContent = order.date;
                    document.getElementById('orderStatus').innerHTML = order.statusDisplay;
                    document.getElementById('orderPayment').textContent = order.payment;
                    document.getElementById('orderShipping').textContent = order.shipping;
                    document.getElementById('orderSubtotal').textContent = order.subtotal;
                    document.getElementById('orderShippingCost').textContent = order.shippingCost;
                    document.getElementById('orderTaxes').textContent = order.taxes;
                    document.getElementById('orderTotal').textContent = order.total;
                    
                    // Fill products table
                    const productsListElement = document.getElementById('orderProductsList');
                    productsListElement.innerHTML = '';
                    
                    order.products.forEach(product => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td>${product.price}</td>
                            <td>${product.total}</td>
                        `;
                        productsListElement.appendChild(row);
                    });
                    
                    // Show the modal
                    $('#orderDetailsModal').modal('show');
                });
            });
        });
    </script>

    <!-- Section toggle functionality -->
    <script>
        $(document).ready(function() {
            // Show the first panel by default
            $('#quickAccessPanel').collapse('show');
            $('.section-selector[data-target="quickAccessPanel"]').addClass('active');
            
            // Variable to track click state and prevent double-click issues
            let isProcessing = false;
            
            // Handle section toggle clicks
            $('.section-toggle').click(function(e) {
                // Prevent multiple rapid clicks from causing issues
                if (isProcessing) return;
                isProcessing = true;
                
                const targetId = $(this).data('target');
                const $selector = $('.section-selector[data-target="' + targetId + '"]');
                const isActive = $selector.hasClass('active');
                
                // If clicking on already active section and it's visible, hide it
                if (isActive && $('#' + targetId).hasClass('show')) {
                    $('#' + targetId).collapse('hide');
                    $selector.removeClass('active');
                    
                    // Reset processing flag after animation completes
                    $('#' + targetId).on('hidden.bs.collapse', function() {
                        isProcessing = false;
                    });
                    return;
                }
                
                // Hide all sections
                $('.section-content').collapse('hide');
                
                // Remove active class from all selectors
                $('.section-selector').removeClass('active');
                
                // Show the clicked section
                $('#' + targetId).collapse('show');
                
                // Add active class to the clicked selector
                $selector.addClass('active');
                
                // Reset processing flag after animation completes
                $('#' + targetId).on('shown.bs.collapse', function() {
                    isProcessing = false;
                });
            });
        });
    </script>
@endpush



@extends('adminlte::page')

@section('title', 'Panel de Productos')

@section('preloader')
    <img src="{{ asset('vendor/adminlte/dist/img/GlobalIcon.png') }}" class="animation__shake" width="120" height="120">
    <h4 class="mt-4 text-dark">Cargando panel de productos...</h4>
@stop

{{-- Activate the necessary DataTables plugins --}}
@section('plugins.Datatables', true)
@section('plugins.DatatablesButtons', true)

@section('content_header')
    <h1>Panel de Productos</h1>
@stop

@section('content')
    <!-- Filter Options -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filter Options</h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" method="GET">
                        <div class="row">
                            <!-- Filter: Category -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Categoría</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">All Categories</option>
                                        <option value="Gaming Consoles">Gaming Consoles</option>
                                        <option value="Gaming Accessories">Gaming Accessories</option>
                                        <option value="Gaming Furniture">Gaming Furniture</option>
                                        <option value="Gaming Machines">Gaming Machines</option>
                                        <option value="Gaming Audio">Gaming Audio</option>
                                        <option value="Gaming Computers">Gaming Computers</option>
                                        <option value="Gaming Network">Gaming Network</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Filter: Brand -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Marca</label>
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">All Brands</option>
                                        <option value="Sony">Sony</option>
                                        <option value="Microsoft">Microsoft</option>
                                        <option value="Nintendo">Nintendo</option>
                                        <option value="Razer">Razer</option>
                                        <option value="Logitech">Logitech</option>
                                        <option value="DXRacer">DXRacer</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Filter: Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="In Stock">In Stock</option>
                                        <option value="Low Stock">Low Stock</option>
                                        <option value="Out of Stock">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Price Range Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_price">Precio Mínimo</label>
                                    <input type="number" name="min_price" id="min_price" class="form-control" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_price">Precio Máximo</label>
                                    <input type="number" name="max_price" id="max_price" class="form-control" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        <!-- Rating Range Filter -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_rating">Rating Mínimo</label>
                                    <input type="number" name="min_rating" id="min_rating" class="form-control" min="0" max="5" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_rating">Rating Máximo</label>
                                    <input type="number" name="max_rating" id="max_rating" class="form-control" min="0" max="5" step="0.1">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <button type="reset" class="btn btn-secondary">Reset Filters</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Display Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Productos</h3>
                </div>
                <div class="card-body">
                    <div id="products-container">
                        <!-- Products will be displayed here -->
                    </div>
                    <!-- Pagination Controls -->
                    <div class="pagination-container mt-4 d-flex justify-content-center">
                        <nav aria-label="Product pagination">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
<style>
    /* Filter form styling */
    #filter-form .form-group {
        margin-bottom: 1rem;
    }
    #filter-form label {
        font-weight: bold;
        color: #000000;
    }
    #filter-form select,
    #filter-form input {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
    }
    #filter-form select:focus,
    #filter-form input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    #filter-form button {
        margin-right: 0.5rem;
    }
    
    /* Product card styling */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    #products-container .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .card-img-top {
        height: 200px;
        object-fit: contain;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .card-title {
        font-weight: bold;
        text-align: center;
        margin-bottom: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    /* Pagination styling */
    .pagination .page-link {
        color: #007bff;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #filter-form .col-md-4,
        #filter-form .col-md-6 {
            margin-bottom: 1rem;
        }
        .col-md-4 {
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        let products = [];
        let currentPage = 1;
        const productsPerPage = 10;

        // Fetch products data
        $.getJSON('/data/products.json', function(data) {
            products = data.products;
            displayProducts(products);
        });

        // Display products function with pagination
        function displayProducts(filteredProducts) {
            const productsContainer = $('#products-container');
            if (filteredProducts.length === 0) {
                productsContainer.html('<p class="text-center">No products found matching your criteria.</p>');
                $('#pagination').empty();
                return;
            }

            // Calculate pagination
            const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
            const startIndex = (currentPage - 1) * productsPerPage;
            const endIndex = Math.min(startIndex + productsPerPage, filteredProducts.length);
            const currentProducts = filteredProducts.slice(startIndex, endIndex);

            // Generate product cards
            let html = '<div class="row">';
            currentProducts.forEach(product => {
                const imagePath = `/img/products/${product.image}` || '/img/products/placeholder.jpg';
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="${imagePath}" class="card-img-top" alt="${product.name}" onerror="this.src='/img/products/placeholder.jpg'">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            productsContainer.html(html);

            // Generate pagination controls
            generatePagination(totalPages);
        }

        // Generate pagination controls
        function generatePagination(totalPages) {
            const pagination = $('#pagination');
            pagination.empty();

            // Previous button
            pagination.append(`
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                pagination.append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // Next button
            pagination.append(`
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);

            // Add click event to pagination links
            $('.page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page >= 1 && page <= totalPages) {
                    currentPage = page;
                    // Get the current filtered products without calling displayProducts again
                    const filtered = filterProducts(false);
                    // Now display the products with the updated page
                    displayProducts(filtered);
                }
            });
        }

        // Filter products function
        function filterProducts(updateDisplay = true) {
            const category = $('#category').val();
            const brand = $('#brand').val();
            const status = $('#status').val();
            const minPrice = parseFloat($('#min_price').val()) || 0;
            const maxPrice = parseFloat($('#max_price').val()) || Infinity;
            const minRating = parseFloat($('#min_rating').val()) || 0;
            const maxRating = parseFloat($('#max_rating').val()) || 5;

            const filtered = products.filter(product => {
                const matchesCategory = !category || product.category === category;
                const matchesBrand = !brand || product.brand === brand;
                const matchesStatus = !status || product.status === status;
                const matchesPrice = product.price >= minPrice && product.price <= maxPrice;
                const matchesRating = product.rating >= minRating && product.rating <= maxRating;

                return matchesCategory && matchesBrand && matchesStatus && matchesPrice && matchesRating;
            });

            // Reset to first page when filtering (but not when just getting filtered products for pagination)
            if (updateDisplay) {
                currentPage = 1;
                displayProducts(filtered);
            }
            return filtered;
        }

        // Initialize form handling
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            filterProducts();
        });

        // Reset form and display all products
        $('#filter-form button[type="reset"]').on('click', function() {
            setTimeout(function() {
                displayProducts(products);
            }, 0);
        });

        // Update brand options based on available products
        $.getJSON('/data/products.json', function(data) {
            const brands = [...new Set(data.products.map(p => p.brand))].sort();
            const brandSelect = $('#brand');
            brandSelect.find('option:not(:first)').remove();
            brands.forEach(brand => {
                brandSelect.append(`<option value="${brand}">${brand}</option>`);
            });
        });
    });
</script>
@endpush
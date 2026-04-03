@extends('layouts.admin')

@section('title', 'Admin — Products')

@section('content')
<main class="admin-main">
    <div class="container-fluid px-4 px-md-5">

        <!-- Page header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="admin-page-title">Products</h1>
            <a href="{{ route('admin.products.create') }}" class="admin-add-btn clear-link">Add a product</a>
        </div>

        <!-- Table card -->
        <div class="admin-table-card">

            <!-- Toolbar -->
            <div class="admin-toolbar">
                <div class="d-flex gap-2">
                    <button class="admin-filter-btn active">All</button>
                    <button class="admin-filter-btn">Active</button>
                    <button class="admin-filter-btn">Archived</button>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <button class="admin-icon-btn" title="Sort"><span class="material-symbols-outlined">swap_vert</span></button>
                    <button class="admin-icon-btn" title="Options"><span class="material-symbols-outlined">remove</span></button>
                </div>
            </div>

            <!-- Table header -->
            <div class="admin-table-header">
                <div class="col-check"></div>
                <div class="col-img"></div>
                <div class="col-name">Product</div>
                <div class="col-status">Status</div>
                <div class="col-inventory">Inventory</div>
                <div class="col-type">Type</div>
                <div class="col-vendor">Vendor</div>
                <div class="col-action"></div>
            </div>

            <!-- Rows -->
            <div class="admin-table-body" id="products-list">

                @foreach ([
                    ['image_1.jpg'],
                    ['image_2.jpg'],
                    ['image_3.jpg'],
                    ['image_1.jpg'],
                    ['image_2.jpg'],
                    ['image_3.jpg'],
                ] as $row)
                <div class="admin-row">
                    <div class="col-check"><input type="radio" class="admin-radio" name="product-select"></div>
                    <div class="col-img"><div class="admin-product-img"><img class="img__container" src="{{ asset('images/' . $row[0]) }}" alt="Product thumbnail"></div></div>
                    <div class="col-name admin-cell-label">NAME</div>
                    <div class="col-status admin-cell-muted">Act/Arch</div>
                    <div class="col-inventory admin-cell-muted">How many ?</div>
                    <div class="col-type admin-cell-muted">Sh/cl/hat</div>
                    <div class="col-vendor admin-cell-muted">Zara, nike, …</div>
                    <div class="col-action">
                        <a href="{{ route('admin.products.edit') }}" class="admin-edit-btn clear-link" title="Edit">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.admin-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.admin-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>
@endsection

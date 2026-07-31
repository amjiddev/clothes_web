@extends('admin.layouts.app')

@section('title', 'Product Sections Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.website-management.contact') }}">Website Management</a></li>
    <li class="breadcrumb-item active">Product Sections</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">
                    <i class="fas fa-th-large me-2"></i>Product Sections Management
                </h1>
                <p class="text-muted">Manage products and their display across website sections</p>
            </div>
            <div class="col-auto" style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                    <i class="fas fa-plus me-2"></i>Add Brand
                </button>
                <a href="{{ route('admin.website-management.product-sections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary-subtle text-primary rounded">
                                <i class="fas fa-boxes fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Total Products</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success-subtle text-success rounded">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Active Products</h6>
                            <h3 class="mb-0">{{ $stats['active_products'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info-subtle text-info rounded">
                                <i class="fas fa-warehouse fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">In Stock</h6>
                            <h3 class="mb-0">{{ $stats['in_stock'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-danger-subtle text-danger rounded">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Out of Stock</h6>
                            <h3 class="mb-0">{{ $stats['out_of_stock'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Products</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products..." id="searchProduct">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Display Sections</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-center">
                                @if($product->featuredImage)
                                    <img src="{{ $product->featuredImage->image_url }}" alt="{{ $product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>{{ $product->brand ?? 'N/A' }}</td>
                            <td>
                                @if($product->sale_price)
                                    <div>
                                        <span class="text-danger fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                                    </div>
                                    <div>
                                        <small class="text-muted text-decoration-line-through">₹{{ number_format($product->regular_price ?? $product->price, 2) }}</small>
                                        <small class="badge bg-danger-subtle text-danger">{{ $product->discount_percentage }}% OFF</small>
                                    </div>
                                @else
                                    <span class="fw-bold">₹{{ number_format($product->regular_price ?? $product->price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->stock_status_badge }}-subtle text-{{ $product->stock_status_badge }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </td>
                            <td>
                                @if($product->displaySections->count() > 0)
                                    @foreach($product->displaySections->take(2) as $section)
                                        <span class="badge bg-primary-subtle text-primary mb-1">{{ $section->section_name }}</span>
                                    @endforeach
                                    @if($product->displaySections->count() > 2)
                                        <span class="badge bg-secondary-subtle text-secondary mb-1">+{{ $product->displaySections->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-muted">No sections</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.website-management.product-sections.show', $product) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.website-management.product-sections.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct({{ $product->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $product->id }}" action="{{ route('admin.website-management.product-sections.destroy', $product) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No products found. <a href="{{ route('admin.website-management.product-sections.create') }}">Add your first product</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($products->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalTitle">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBrandForm" method="POST" action="{{ route('admin.brands.store') }}">
                @csrf
                <input type="hidden" id="brandId" value="">
                <input type="hidden" id="methodField" name="_method" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter brand name" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="brand@example.com" autocomplete="off">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone number" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Number</label>
                            <input type="text" name="number" class="form-control" placeholder="Business number" autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Brand address" autocomplete="off"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brand description" autocomplete="off"></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked value="1">
                        <label class="form-check-label" for="isActive">
                            Active Brand
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="submitBrandBtn">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Brand Modal -->
<div class="modal fade" id="viewBrandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Brand Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Brand Name</p>
                        <h6 id="viewBrandName" class="fw-bold mb-3"></h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Status</p>
                        <span id="viewBrandStatus" class="badge"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Email</p>
                        <p id="viewBrandEmail" class="mb-3">-</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Phone</p>
                        <p id="viewBrandPhone" class="mb-3">-</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Number</p>
                        <p id="viewBrandNumber" class="mb-3">-</p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-muted mb-1">Address</p>
                        <p id="viewBrandAddress" class="mb-3">-</p>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Description</p>
                    <p id="viewBrandDescription">-</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Brands Table -->
<div class="card border-0 shadow-sm mt-5">
    <div class="card-header bg-white border-bottom">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0"><i class="fas fa-store me-2"></i>All Brands</h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search brands..." id="searchBrand">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="brandsTable">
                <thead class="bg-light">
                    <tr>
                        <th>Brand Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Number</th>
                        <th>Address</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="brandsTableBody">
                    @if(isset($brands) && $brands->count() > 0)
                        @foreach($brands as $brand)
                        <tr class="brand-row">
                            <td class="fw-bold">{{ $brand->name }}</td>
                            <td>{{ $brand->email ?? '-' }}</td>
                            <td>{{ $brand->phone ?? '-' }}</td>
                            <td>{{ $brand->number ?? '-' }}</td>
                            <td>{{ $brand->address ? substr($brand->address, 0, 50) . '...' : '-' }}</td>
                            <td class="text-center">
                                @if($brand->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" title="View" onclick="viewBrand({{ $brand->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" title="Edit" onclick="editBrand({{ $brand->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="deleteBrand({{ $brand->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No brands found. Click "Add Brand" to create one.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
// Get CSRF token from meta tag
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Search brands functionality
document.getElementById('searchBrand')?.addEventListener('keyup', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const tableRows = document.querySelectorAll('#brandsTableBody tr');
    
    tableRows.forEach(row => {
        if (!row.classList.contains('brand-row')) return;
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// View brand function
function viewBrand(id) {
    fetch('/admin/brands/' + id + '/get-by-id', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const brand = data.brand;
            
            // Populate modal fields
            document.getElementById('viewBrandName').textContent = brand.name;
            document.getElementById('viewBrandEmail').textContent = brand.email || '-';
            document.getElementById('viewBrandPhone').textContent = brand.phone || '-';
            document.getElementById('viewBrandNumber').textContent = brand.number || '-';
            document.getElementById('viewBrandAddress').textContent = brand.address || '-';
            document.getElementById('viewBrandDescription').textContent = brand.description || '-';
            
            // Set status badge
            const statusBadge = document.getElementById('viewBrandStatus');
            if (brand.is_active) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'Active';
            } else {
                statusBadge.className = 'badge bg-secondary';
                statusBadge.textContent = 'Inactive';
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('viewBrandModal'));
            modal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading brand data');
    });
}

// Edit brand function
function editBrand(id) {
    fetch('/admin/brands/' + id + '/get-by-id', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const brand = data.brand;
            const form = document.getElementById('addBrandForm');
            
            // Update form action for update
            form.action = '/admin/brands/' + id;
            
            // Update method field for PUT request
            document.getElementById('methodField').value = 'PUT';
            
            // Populate form fields
            form.querySelector('input[name="name"]').value = brand.name;
            form.querySelector('input[name="email"]').value = brand.email || '';
            form.querySelector('input[name="phone"]').value = brand.phone || '';
            form.querySelector('input[name="number"]').value = brand.number || '';
            form.querySelector('textarea[name="address"]').value = brand.address || '';
            form.querySelector('textarea[name="description"]').value = brand.description || '';
            form.querySelector('input[name="is_active"]').checked = brand.is_active;
            
            // Update modal title and button
            document.getElementById('brandModalTitle').textContent = 'Edit Brand';
            document.getElementById('submitBrandBtn').textContent = 'Update Brand';
            document.getElementById('brandId').value = id;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addBrandModal'));
            modal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading brand data');
    });
}

// Delete brand function
function deleteBrand(id) {
    if (confirm('Are you sure you want to delete this brand?')) {
        fetch('/admin/brands/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Brand deleted successfully');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to delete brand'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting brand');
        });
    }
}

// Reset form when modal is hidden
document.getElementById('addBrandModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('addBrandForm');
    form.reset();
    form.action = '{{ route("admin.brands.store") }}';
    document.getElementById('methodField').value = '';
    document.getElementById('brandModalTitle').textContent = 'Add New Brand';
    document.getElementById('submitBrandBtn').textContent = 'Add Brand';
    document.getElementById('brandId').value = '';
});

// Handle form submission for add/update brand
document.getElementById('addBrandForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const methodField = document.getElementById('methodField').value;
    
    // Remove empty method field value if not updating
    if (!methodField) {
        formData.delete('_method');
    }
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
            if (modal) {
                modal.hide();
            }
            // Reload page to show new brand
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert('Error: ' + (data.message || 'Failed to save brand'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving brand: ' + error.message);
    });
});

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Simple search functionality
document.getElementById('searchProduct').addEventListener('keyup', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endpush
@endsection

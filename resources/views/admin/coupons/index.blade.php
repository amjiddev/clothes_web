@extends('admin.layouts.app')

@section('title', 'Coupons & Discounts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Coupons & Discounts</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-tags me-2"></i>Coupons & Discounts</h1>
                <p class="text-muted">Manage discount coupons for your shop</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Coupon
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Coupons Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="mb-0">All Coupons</h6>
                </div>
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search coupons..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="couponsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Type</th>
                            <th>Valid Until</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $coupon->code }}</code>
                                </td>
                                <td>
                                    @if($coupon->discount_type === 'percentage')
                                        <strong>{{ $coupon->discount_value }}%</strong>
                                    @else
                                        <strong>{{ number_format($coupon->discount_value, 2) }}</strong>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $coupon->discount_type === 'percentage' ? 'info' : 'primary' }}">
                                        {{ ucfirst($coupon->discount_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->valid_until)
                                        {{ $coupon->valid_until->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">No limit</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $coupon->usage_limit ?? 'Unlimited' }}</small>
                                </td>
                                <td>
                                    @if($coupon->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No coupons found</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>Create First Coupon
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($coupons->hasPages())
    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
    @endif
</div>

<style>
    .page-header {
        padding: 20px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#couponsTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection

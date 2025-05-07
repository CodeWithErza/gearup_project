<x-dashboard-layout :title="'Inventory Management'" :icon="'fa-solid fa-warehouse'">
    <div class="container-fluid">
        <!-- Quick Action Cards -->
        <div class="row mb-3">
            <div class="col-md-4 mb-3 mb-md-0">
                <a href="{{ route('inventory.stock-in') }}" class="text-decoration-none">
                    <div class="card shadow-sm hover-card h-100 border-0 bg-white">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #212529;">
                                <i class="fas fa-arrow-down fa-lg" style="color: #ffc107;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold text-dark">Stock In</h5>
                                <p class="card-text text-secondary mb-0 small">Add new inventory to stock</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <a href="{{ route('inventory.stock-out') }}" class="text-decoration-none">
                    <div class="card shadow-sm hover-card h-100 border-0 bg-white">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #212529;">
                                <i class="fas fa-arrow-up fa-lg" style="color: #ffc107;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold text-dark">Stock Out</h5>
                                <p class="card-text text-secondary mb-0 small">Remove items from inventory</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm hover-card h-100 border-0 bg-white">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background-color: #212529;">
                                <i class="fas fa-truck fa-lg" style="color: #ffc107;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold text-dark">Suppliers</h5>
                                <p class="card-text text-secondary mb-0 small">Manage supplier profiles</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Inventory Statistics -->
        <div class="row mb-3">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm h-100 border-0 bg-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-dark fw-semibold">Inventory Value</h6>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background-color: #212529;">
                                <i class="fas fa-dollar-sign fa-lg" style="color: #ffc107;"></i>
                            </div>
                        </div>
                        <h3 class="card-title mb-1 fw-bold text-dark">₱{{ number_format($inventoryValue, 2) }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success-subtle text-success fw-semibold px-2 py-1 me-2">Calculated</span>
                            <span class="text-secondary fw-medium">from current stock levels</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm h-100 border-0 bg-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-dark fw-semibold">Stock Status</h6>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background-color: #212529;">
                                <i class="fas fa-boxes fa-lg" style="color: #ffc107;"></i>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="mb-0 text-success fw-bold">{{ $normalStockCount }}</h3>
                                <small class="text-dark fw-medium">Normal</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-warning fw-bold">{{ $lowStockCount }}</h3>
                                <small class="text-dark fw-medium">Low</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0 text-danger fw-bold">{{ $criticalStockCount }}</h3>
                                <small class="text-dark fw-medium">Critical</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0 bg-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-dark fw-semibold">Monthly Movement</h6>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background-color: #212529;">
                                <i class="fas fa-exchange-alt fa-lg" style="color: #ffc107;"></i>
                            </div>
                        </div>
                        <h3 class="card-title mb-1 fw-bold text-dark">
                            {{ $recentActivities->count() ?? 0 }}
                        </h3>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info-subtle text-info fw-semibold px-2 py-1 me-2">Active</span>
                            <span class="text-secondary fw-medium">recent transactions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Products Preview -->
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm h-100 border-0 bg-white">
                    <div class="card-header bg-white py-2 border-bottom border-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold" style="color: var(--accent);">
                                <i class="fas fa-boxes-stacked me-2" style="color: var(--accent);"></i>
                                Low Stock Items
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-0 overflow-auto" style="max-height: 350px;">
                        <div class="list-group list-group-flush">
                            @forelse($lowStockProducts as $product)
                            <div class="list-group-item py-2 border-bottom border-light highlight-item">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px; min-width: 40px; background-color: {{ $product->stock == 0 ? 'rgba(220, 53, 69, 0.15)' : 'rgba(255, 193, 7, 0.15)' }};">
                                        <i class="fas fa-box fa-lg {{ $product->stock == 0 ? 'text-danger' : 'text-warning' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $product->name }}</h6>
                                            <span class="badge {{ $product->stock == 0 ? 'bg-danger text-white' : 'bg-warning text-dark' }} fw-semibold px-2 py-1">{{ $product->stock }} left</span>
                                        </div>
                                        <small class="text-dark fw-medium">{{ $product->sku }} | {{ $product->category->name ?? 'Uncategorized' }}</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item py-3 border-bottom border-light text-center">
                                <p class="mb-0 text-muted">No low stock items</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center py-2 border-top border-light">
                        <a href="{{ route('products.index') }}?filter=low-stock" class="text-accent text-decoration-none fw-semibold">
                            View All Low Stock Items
                            <i class="fas fa-chevron-right ms-1 small"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0 bg-white">
                    <div class="card-header bg-white py-2 border-bottom border-light">
                        <h5 class="card-title mb-0 fw-bold" style="color: var(--accent);">
                            <i class="fas fa-history me-2" style="color: var(--accent);"></i>
                            Recent Inventory Activity
                        </h5>
                    </div>
                    <div class="card-body p-0 overflow-auto" style="max-height: 350px;">
                        <div class="list-group list-group-flush">
                            @forelse($recentActivities as $activity)
                            <div class="list-group-item py-2 border-bottom border-light highlight-item">
                                <div class="d-flex">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px; min-width: 40px; background-color: {{ $activity['type'] == 'stock_in' ? 'rgba(40, 167, 69, 0.15)' : ($activity['type'] == 'stock_out' ? 'rgba(220, 53, 69, 0.15)' : 'rgba(255, 193, 7, 0.15)') }};">
                                        <i class="fas {{ $activity['type'] == 'stock_in' ? 'fa-arrow-down text-success' : ($activity['type'] == 'stock_out' ? 'fa-arrow-up text-danger' : 'fa-sliders-h text-warning') }} fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-semibold text-dark">
                                                {{ ucfirst(str_replace('_', ' ', $activity['type'])) }}: 
                                                @if($activity['type'] == 'stock_in')
                                                    From {{ $activity['supplier'] ?? 'Supplier' }}
                                                @else
                                                    {{ $activity['notes'] ? Str::limit($activity['notes'], 30) : 'Multiple Items' }}
                                                @endif
                                            </h6>
                                            <small class="text-dark fw-medium">{{ $activity['date']->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-dark small">{{ $activity['items_count'] }} item(s) {{ $activity['type'] == 'stock_in' ? 'added to' : 'removed from' }} inventory</p>
                                        <small class="badge bg-secondary text-white">Reference: {{ $activity['reference_number'] }}</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item py-3 border-bottom border-light text-center">
                                <p class="mb-0 text-muted">No recent inventory activity</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center py-2 border-top border-light">
                        <a href="#" class="text-accent text-decoration-none fw-semibold">
                            View All Activity
                            <i class="fas fa-chevron-right ms-1 small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS For Hover Effect and Theme Variables -->
    <style>
        :root {
            --accent-rgb: 13, 110, 253;
        }
        
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
        }
        
        .btn-icon {
            color: #6c757d;
            background: transparent;
            border: none;
            padding: 0.25rem 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-icon:hover {
            color: var(--accent);
            background-color: rgba(var(--accent-rgb), 0.1);
            border-radius: 4px;
        }
        
        .btn-outline-accent {
            color: var(--accent);
            border-color: var(--accent);
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-outline-accent:hover {
            background-color: var(--accent);
            color: white;
            box-shadow: 0 4px 8px rgba(var(--accent-rgb), 0.25);
        }
        
        .highlight-item {
            transition: all 0.2s ease;
        }
        
        .highlight-item:hover {
            background-color: rgba(var(--accent-rgb), 0.05);
            transform: translateX(3px);
        }
        
        /* Custom scrollbar */
        .overflow-auto::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .overflow-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
</x-dashboard-layout> 
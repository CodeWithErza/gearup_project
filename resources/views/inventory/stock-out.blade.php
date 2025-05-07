<x-dashboard-layout :title="'Stock Out'" :icon="'fa-solid fa-arrow-down'">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('inventory') }}" class="text-decoration-none">
                        <i class="fa-solid fa-warehouse"></i> Inventory
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Stock Out</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Left column - Stock Out Form -->
            <div class="col-lg-8">
                <!-- Stock Out Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-arrow-down me-2"></i>
                            Stock Out Details
                        </h5>
                    </div>
                    <div class="card-body">
                    <form id="stockOutForm" method="POST" action="{{ route('stockout.store') }}">
                            @csrf
                            <!-- Reference Number and Date -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="reference_number" value="{{ $nextRefNumber }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" id="stockOutDate" name="date" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <!-- Product Search -->
                            <div class="mb-4">
                                <label class="form-label">Search Product</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="productSearch" placeholder="Search product...">
                                    <button class="btn btn-accent" type="button" id="openProductModal">
                                        <i class="fas fa-search me-1"></i>
                                        Browse
                                    </button>
                                </div>
                                <ul class="list-group mt-1" id="searchResults" style="position: absolute; z-index: 999;"></ul>
                            </div>

                            <!-- Stock Out Items Table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Current Stock</th>
                                            <th>Quantity</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stockOutItems">
                                        <tr id="noItemsMessage">
                                            <td colspan="6" class="text-center py-4">
                                                <div class="no-data-message">
                                                    <i class="fas fa-box-open fa-3x mb-3" style="color: var(--accent);"></i>
                                                    <h6 class="fw-normal">No items added</h6>
                                                    <p class="small mb-0">
                                                        Search for products to add
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Notes (Collapsible) -->
                            <div class="mb-4">
                                <a class="d-flex align-items-center collapsed text-decoration-none" data-bs-toggle="collapse" href="#notesCollapse" role="button" aria-expanded="false" aria-controls="notesCollapse">
                                    <i class="fas fa-caret-right me-2 notes-caret"></i>
                                    <label class="form-label mb-0">Add Notes (Optional)</label>
                                </a>
                                <div class="collapse mt-2" id="notesCollapse">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
                                </div>
                            </div>

                            <!-- Processed By -->
                            <div class="mb-4">
                                <label class="form-label">Processed By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-accent" id="processStockOut">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Process Stock Out
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column - Info cards -->
            <div class="col-lg-4 d-flex flex-column">
                <!-- Recent Transactions -->
                <div class="card shadow-sm mb-4 flex-grow-1">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Recent Transactions
                        </h5>
                    </div>
                    <div class="card-body p-0 bg-white" style="overflow-y: auto; height: 250px;">
                        <div class="list-group list-group-flush h-100 bg-white">
                            @forelse($recentTransactions ?? [] as $transaction)
                            <div class="list-group-item border-bottom">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-dark">{{ $transaction->reference_number }}</h6>
                                    <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-dark">Stock Out - {{ $transaction->items->count() }} item(s) removed</p>
                                <small class="text-secondary">Processed by {{ $transaction->user->name ?? 'Unknown' }}</small>
                            </div>
                            @empty
                            <div class="list-group-item border-bottom text-center py-3">
                                <p class="mb-0 text-muted">No recent transactions</p>
                            </div>
                            @endforelse
                            <div class="flex-grow-1 bg-white"></div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Guidelines -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Transaction Guidelines
                        </h5>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="mb-0 ps-3 text-dark">
                            <li class="mb-2">Always verify product details before processing</li>
                            <li class="mb-2">Ensure quantities are correct and available</li>
                            <li class="mb-2">Document all reasons for stock removals</li>
                            <li class="mb-2">Large adjustments require supervisor approval</li>
                            <li>Keep related documents for reference</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSelectionModalLabel">Select Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="modalProductSearch" placeholder="Search products...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>PRODUCT</th>
                                    <th>SKU</th>
                                    <th>CATEGORY</th>
                                    <th>CURRENT STOCK</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="productModalResults">
                                <!-- Products will be populated here via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stockOutForm = document.getElementById('stockOutForm');
            const productSearch = document.getElementById('productSearch');
            const searchResults = document.getElementById('searchResults');
            const stockOutItems = document.getElementById('stockOutItems');
            const noItemsMessage = document.getElementById('noItemsMessage');
            const openProductModal = document.getElementById('openProductModal');
            const modalProductSearch = document.getElementById('modalProductSearch');
            const productModalResults = document.getElementById('productModalResults');
            const productModal = new bootstrap.Modal(document.getElementById('productSelectionModal'));

            // Handle form submission
            stockOutForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = {
                    _token: document.querySelector('input[name="_token"]').value,
                    reference_number: document.getElementById('referenceNumber').value,
                    date: document.getElementById('stockOutDate').value,
                    notes: document.getElementById('notes').value,
                    items: []
                };

                // Gather items data
                const rows = stockOutItems.querySelectorAll('tr:not(#noItemsMessage)');
                if (rows.length === 0) {
                    alert('Please add at least one item to process.');
                    return;
                }

                rows.forEach(row => {
                    const productId = row.querySelector('input[name$="[product_id]"]').value;
                    const newCount = parseInt(row.querySelector('input[name$="[new_count]"]').value);
                    const reason = row.querySelector('select[name$="[reason]"]').value;

                    formData.items.push({
                        product_id: productId,
                        new_count: newCount,
                        reason: reason
                    });
                });

                try {
                    const response = await fetch(stockOutForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': formData._token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    // Check for error responses
                    if (!response.ok) {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            // Handle JSON error response
                            const result = await response.json();
                            throw new Error(result.message || 'Error processing stock out');
                        } else {
                            // Handle non-JSON error response
                            const text = await response.text();
                            throw new Error('Server error occurred. Please try again.');
                        }
                    }

                    // Handle successful response
                    const result = await response.json();
                    alert(result.message || 'Stock out processed successfully');
                    window.location.href = '{{ route('inventory.stock-out') }}';
                    
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message || 'An error occurred while processing the stock out');
                }
            });

            // Open Product Modal
            openProductModal.addEventListener('click', function() {
                loadAllProducts();
                productModal.show();
            });

            // Load all products into modal
            async function loadAllProducts() {
                try {
                    const response = await fetch('/api/products');
                    const products = await response.json();
                    
                    renderProductsInModal(products);
                } catch (error) {
                    console.error('Error loading products:', error);
                }
            }

            // Modal search functionality
            let modalDebounceTimer;
            modalProductSearch.addEventListener('input', function() {
                clearTimeout(modalDebounceTimer);
                const query = this.value.trim();

                if (query.length < 2 && query.length > 0) {
                    return;
                }

                modalDebounceTimer = setTimeout(async () => {
                    try {
                        const url = query.length === 0 
                            ? '/api/products' 
                            : `/api/products/search?q=${encodeURIComponent(query)}`;
                            
                        const response = await fetch(url);
                        const products = await response.json();
                        
                        renderProductsInModal(products);
                    } catch (error) {
                        console.error('Error searching products:', error);
                    }
                }, 300);
            });

            // Render products in modal table
            function renderProductsInModal(products) {
                productModalResults.innerHTML = '';
                
                if (products.length === 0) {
                    productModalResults.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">No products found</td>
                        </tr>
                    `;
                    return;
                }
                
                products.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="product-icon me-2">
                                    <i class="fas fa-box"></i>
                                </div>
                                <span>${product.name}</span>
                            </div>
                        </td>
                        <td>${product.sku}</td>
                        <td>${product.category}</td>
                        <td>${product.stock}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning select-product">
                                Select
                            </button>
                        </td>
                    `;
                    
                    row.querySelector('.select-product').addEventListener('click', function() {
                        addStockOutItem(product);
                        productModal.hide();
                    });
                    
                    productModalResults.appendChild(row);
                });
            }

            // Inline product search functionality
            let debounceTimer;
            productSearch.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
                        const products = await response.json();

                        searchResults.innerHTML = '';
                        if (products.length > 0) {
                            products.forEach(product => {
                                const item = document.createElement('li');
                                item.classList.add('list-group-item', 'list-group-item-action');
                                item.textContent = `${product.name} (${product.sku})`;
                                item.style.cursor = 'pointer';
                                item.onclick = () => {
                                    addStockOutItem(product);
                                    productSearch.value = '';
                                    searchResults.innerHTML = '';
                                };
                                searchResults.appendChild(item);
                            });
                        } else {
                            const item = document.createElement('li');
                            item.classList.add('list-group-item');
                            item.textContent = 'No results found';
                            searchResults.appendChild(item);
                        }
                    } catch (error) {
                        console.error('Error searching products:', error);
                    }
                }, 300);
            });

            // Function to add item to list
            let itemIndex = 0;
            function addStockOutItem(product) {
                noItemsMessage.style.display = 'none';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.sku}</td>
                    <td>${product.stock}</td>
                    <td>
                        <input type="hidden" name="items[${itemIndex}][product_id]" value="${product.id}">
                        <input type="number" name="items[${itemIndex}][new_count]" class="form-control form-control-sm new-count" 
                            value="1" 
                            min="1" max="${product.stock}">
                    </td>
                    <td>
                        <select name="items[${itemIndex}][reason]" class="form-select form-select-sm">
                            <option value="damaged">Damaged</option>
                            <option value="expired">Expired</option>
                            <option value="lost">Lost/Missing</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;

                // Add event listeners
                const quantityInput = row.querySelector('.new-count');

                quantityInput.addEventListener('input', function() {
                    const quantity = parseInt(this.value) || 0;
                    
                    // Ensure quantity does not exceed available stock
                    if (quantity > product.stock) {
                        this.value = product.stock;
                    }
                });

                row.querySelector('.remove-item').addEventListener('click', function() {
                    row.remove();
                    if (stockOutItems.querySelectorAll('tr:not(#noItemsMessage)').length === 0) {
                        noItemsMessage.style.display = '';
                    }
                });

                stockOutItems.appendChild(row);
                itemIndex++;
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Product Selection Modal Styles */
        #productSelectionModal .modal-content {
            background-color: #f8f9fa;
            border: none;
            border-radius: 8px;
        }
        
        #productSelectionModal .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
        }
        
        #productSelectionModal .modal-title {
            font-weight: 600;
            color: #333;
        }
        
        #productSelectionModal .modal-body {
            padding: 20px;
        }
        
        #productSelectionModal table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        #productSelectionModal table thead {
            background-color: #343a40;
            color: white;
        }
        
        #productSelectionModal table th {
            font-weight: 600;
            padding: 12px 15px;
            border: none;
        }
        
        #productSelectionModal table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }
        
        #productSelectionModal table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        #productSelectionModal .select-product {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            font-weight: 500;
            padding: 5px 15px;
            border-radius: 4px;
        }
        
        #productSelectionModal .select-product:hover {
            background-color: #ffb400;
            border-color: #ffb400;
        }
        
        #modalProductSearch {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
    </style>
    @endpush
</x-dashboard-layout> 
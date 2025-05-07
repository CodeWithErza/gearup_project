<x-dashboard-layout :title="'Stock In'" :icon="'fa-solid fa-arrow-down'">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('inventory') }}" class="text-decoration-none">
                        <i class="fa-solid fa-warehouse"></i> Inventory
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Stock In</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Left column - Stock In Form -->
            <div class="col-lg-8">
                <!-- Stock In Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-arrow-down me-2" style="color: #28a745;"></i>
                            Stock In Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="stockInForm" method="POST" action="{{ route('inventory.stock-in.store') }}">
                            @csrf
                            <!-- Supplier and Invoice -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                                        <option value="" selected disabled>Select a supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->supplier_code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" placeholder="Supplier invoice #">
                                </div>
                            </div>
                            
                            <!-- Date and Notes -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <!-- Notes (Collapsible) -->
                                    <a class="d-flex align-items-center collapsed text-decoration-none" data-bs-toggle="collapse" href="#notesCollapse" role="button" aria-expanded="false" aria-controls="notesCollapse">
                                        <i class="fas fa-caret-right me-2 notes-caret"></i>
                                        <label class="form-label mb-0">Add Notes (Optional)</label>
                                    </a>
                                    <div class="collapse mt-2 flex-grow-1" id="notesCollapse">
                                        <textarea class="form-control h-100" id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Search -->
                            <div class="mb-4">
                                <label class="form-label">Search Product</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchProduct" placeholder="Search by product name or SKU">
                                    <button class="btn btn-accent" type="button" id="openProductModal">
                                        <i class="fas fa-search me-1"></i>
                                        Browse
                                    </button>
                                    <button class="btn btn-light" type="button" id="scanBarcode">
                                        <i class="fas fa-barcode me-1"></i>
                                        Scan
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Stock In Items Table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Category</th>
                                            <th>Current Stock</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedProductsTable">
                                        <tr id="noProductsRow">
                                            <td colspan="8" class="text-center py-4">
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

                            <!-- Processed By -->
                            <div class="mb-4">
                                <label class="form-label">Processed By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
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
                            Recent Stock Ins
                        </h5>
                    </div>
                    <div class="card-body p-0 bg-white" style="overflow-y: auto; height: 250px;">
                        <div class="list-group list-group-flush h-100 bg-white">
                            @forelse($stockins ?? [] as $stockin)
                            <div class="list-group-item border-bottom">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-dark">{{ $stockin->invoice_number ?? 'SI-'.$stockin->id }}</h6>
                                    <small class="text-muted">{{ $stockin->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-dark">{{ $stockin->supplier->name ?? 'Unknown Supplier' }}</p>
                                <small class="text-secondary">{{ $stockin->items->count() }} items, ₱{{ number_format($stockin->total_amount, 2) }}</small>
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
                
                <!-- Order Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Order Summary
                        </h5>
                    </div>
                    <div class="card-body bg-white">
                        <div class="summary-item d-flex justify-content-between align-items-center mb-3">
                            <span class="text-dark fw-medium">Total Items:</span>
                            <span class="badge bg-primary rounded-pill px-3 py-2" id="summaryTotalItems">0</span>
                        </div>
                        <div class="summary-item d-flex justify-content-between align-items-center mb-3">
                            <span class="text-dark fw-medium">Total Quantity:</span>
                            <span class="badge bg-info rounded-pill px-3 py-2" id="summaryTotalQuantity">0</span>
                        </div>
                        <div class="summary-item d-flex justify-content-between align-items-center mb-4">
                            <span class="text-dark fw-medium">Total Amount:</span>
                            <span class="badge bg-success rounded-pill px-3 py-2" id="summaryTotalAmount">₱0.00</span>
                        </div>
                        <div class="d-grid">
                            <button type="submit" form="stockInForm" class="btn btn-accent">
                                <i class="fas fa-check-circle me-1"></i>
                                Process Stock In
                            </button>
                        </div>
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
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-icon me-2">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning select-product" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-sku="{{ $product->sku }}"
                                                    data-product-category="{{ $product->category->name }}"
                                                    data-product-stock="{{ $product->stock }}">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
    
    <!-- Scanner Modal -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Scan Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="scanner">
                        <div class="viewport"></div>
                    </div>
                    <div class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="barcodeInput" placeholder="Enter barcode manually">
                            <button class="btn btn-accent" type="button" id="manualBarcodeBtn">Add</button>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <div class="last-scanned mb-2">Last scanned:</div>
                        <div class="scanned-code" id="lastScannedCode">-</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
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

        /* No data message style */
        .no-data-message {
            padding: 20px;
            color: #6c757d;
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelectionModal = new bootstrap.Modal(document.getElementById('productSelectionModal'));
            const selectedProducts = new Set();
            
            // Product search and modal functionality
            document.getElementById('openProductModal').addEventListener('click', function() {
                productSelectionModal.show();
            });
            
            document.getElementById('modalProductSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#productSelectionModal tbody tr');
                
                rows.forEach(row => {
                    const productName = row.querySelector('td:first-child').textContent.toLowerCase();
                    const sku = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    
                    if (productName.includes(searchTerm) || sku.includes(searchTerm) || category.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Product selection
            document.querySelectorAll('.select-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const productName = this.dataset.productName;
                    const productSku = this.dataset.productSku;
                    const productCategory = this.dataset.productCategory;
                    const productStock = this.dataset.productStock;
                    
                    if (!selectedProducts.has(productId)) {
                        selectedProducts.add(productId);
                        addProductToTable(productId, productName, productSku, productCategory, productStock);
                    }
                    
                    productSelectionModal.hide();
                });
            });
            
            function addProductToTable(productId, name, sku, category, currentStock) {
                const noProductsRow = document.getElementById('noProductsRow');
                if (noProductsRow) {
                    noProductsRow.style.display = 'none';
                }
                
                const tbody = document.querySelector('#selectedProductsTable');
                const tr = document.createElement('tr');
                tr.dataset.productId = productId;
                
                tr.innerHTML = `
                    <td>${name}</td>
                    <td>${sku}</td>
                    <td>${category}</td>
                    <td>${currentStock}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" 
                               name="items[${productId}][quantity]" min="1" value="1" required>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control price-input" 
                                   name="items[${productId}][unit_price]" step="0.01" min="0" required>
                        </div>
                    </td>
                    <td class="total-price">₱0.00</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(tr);
                
                // Add event listeners for quantity and price changes
                const quantityInput = tr.querySelector('.quantity-input');
                const priceInput = tr.querySelector('.price-input');
                
                function updateRowTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const total = quantity * price;
                    tr.querySelector('.total-price').textContent = `₱${total.toFixed(2)}`;
                    updateTotals();
                }
                
                quantityInput.addEventListener('input', updateRowTotal);
                priceInput.addEventListener('input', updateRowTotal);
                
                // Remove product
                tr.querySelector('.remove-product').addEventListener('click', function() {
                    selectedProducts.delete(productId);
                    tr.remove();
                    updateTotals();
                    
                    if (selectedProducts.size === 0) {
                        document.getElementById('noProductsRow').style.display = '';
                    }
                });

                updateTotals();
            }
            
            function updateTotals() {
                const rows = document.querySelectorAll('#selectedProductsTable tr:not(#noProductsRow)');
                let totalItems = rows.length;
                let totalQuantity = 0;
                let totalAmount = 0;
                
                rows.forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    totalQuantity += quantity;
                    totalAmount += quantity * price;
                });
                
                // Update the summary panel
                document.getElementById('summaryTotalItems').textContent = totalItems;
                document.getElementById('summaryTotalQuantity').textContent = totalQuantity;
                document.getElementById('summaryTotalAmount').textContent = `₱${totalAmount.toFixed(2)}`;
            }
            
            // Simple form submission
            document.getElementById('stockInForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const rows = document.querySelectorAll('#selectedProductsTable tr:not(#noProductsRow)');
                if (rows.length === 0) {
                    alert('Please add at least one product to complete stock in.');
                    return;
                }
                
                const supplier = document.getElementById('supplier_id').value;
                if (!supplier) {
                    alert('Please select a supplier.');
                    return;
                }

                // Create the items array and add it to the form
                let items = [];
                rows.forEach(row => {
                    items.push({
                        product_id: row.dataset.productId,
                        quantity: row.querySelector('.quantity-input').value,
                        unit_price: row.querySelector('.price-input').value
                    });
                });
                
                // Create a hidden input for items
                let itemsInput = document.createElement('input');
                itemsInput.type = 'hidden';
                itemsInput.name = 'items';
                itemsInput.value = JSON.stringify(items);
                this.appendChild(itemsInput);
                
                // Debug log to help troubleshoot
                console.log('Submitting form with items:', items);
                
                // Submit the form normally
                this.submit();
            });

            // Scanner functionality
            document.getElementById('scanBarcode').addEventListener('click', function() {
                const scannerModal = new bootstrap.Modal(document.getElementById('scannerModal'));
                scannerModal.show();
            });
        });
    </script>
    @endpush
</x-dashboard-layout> 
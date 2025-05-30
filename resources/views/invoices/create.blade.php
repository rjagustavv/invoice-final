@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<!-- Page Header with Glass Effect -->
<div class="position-relative mb-4">
    <div class="page-header-bg rounded-3"></div>
    <div class="position-relative p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div>
                <h1 class="h3 fw-bold mb-1 text-white">Create Invoice</h1>
                <p class="text-white text-opacity-75 mb-0">Create a new invoice with line items</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        
        <div class="card-body p-4">
            <!-- Invoice Information Section -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="info-card-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <h6 class="info-card-title">Invoice Information</h6>
                        </div>
                        <div class="info-card-body">
                            <div class="mb-4">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-hash"></i>
                                    </span>
                                    <input type="text" class="form-control-plaintext ps-2 fw-bold" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber }}" readonly>
                                </div>
                                <div class="form-text">Auto-generated by system</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Enter customer name" required>
                                </div>
                                @error('customer_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-0">
                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', date('Y-m-d')) }}" required>
                                </div>
                                @error('delivery_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="summary-card">
                        <div class="summary-card-header">
                            <div class="summary-card-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h6 class="summary-card-title">Invoice Summary</h6>
                        </div>
                        <div class="summary-card-body">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="summary-stat">
                                        <div class="summary-stat-icon bg-primary bg-opacity-10">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <div class="summary-stat-content">
                                            <span class="summary-stat-label">Total Items</span>
                                            <span class="summary-stat-value" id="total_items_count">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="summary-stat">
                                        <div class="summary-stat-icon bg-success bg-opacity-10">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <div class="summary-stat-content">
                                            <span class="summary-stat-label">Total Amount</span>
                                            <span class="summary-stat-value" id="total_amount_display">Rp 0,00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="completion-tracker">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="completion-label">Form Completion</span>
                                    <span class="completion-percentage" id="progress_percentage">0%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" id="form_progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Details Section -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="section-icon">
                            <i class="bi bi-list-ul"></i>
                        </div>
                        <h5 class="section-title">Invoice Line Items</h5>
                    </div>
                    <button type="button" class="btn btn-primary" id="add_line_item">
                        <i class="bi bi-plus-lg me-2"></i> Add Item
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table invoice-table" id="invoice_details_table">
                        <thead>
                            <tr>
                                <th>Coil Number</th>
                                <th>Width</th>
                                <th>Length</th>
                                <th>Thickness</th>
                                <th>Weight</th>
                                <th>Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoice_details_body">
                            {{-- Baris template untuk item (dihide dan disabled) --}}
                            <tr class="detail-item-template" style="display: none;">
                                <td>
                                    <input type="text" name="details[__INDEX__][coil_number]" class="form-control coil-number" placeholder="Enter coil number" disabled>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="details[__INDEX__][width]" class="form-control width" placeholder="Width" disabled>
                                        <span class="input-group-text">mm</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="details[__INDEX__][length]" class="form-control length" placeholder="Length" disabled>
                                        <span class="input-group-text">mm</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="details[__INDEX__][thickness]" class="form-control thickness" placeholder="Thickness" disabled>
                                        <span class="input-group-text">mm</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="details[__INDEX__][weight]" class="form-control weight" placeholder="Weight" disabled>
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" step="0.01" name="details[__INDEX__][price]" class="form-control price-item" placeholder="Price" disabled>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-delete-item" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            {{-- Baris untuk input, jika ada old input --}}
                            @if(old('details'))
                                @foreach(old('details') as $index => $detail)
                                <tr class="detail-item">
                                    <td>
                                        <input type="text" name="details[{{$index}}][coil_number]" class="form-control coil-number @error('details.'.$index.'.coil_number') is-invalid @enderror" value="{{ $detail['coil_number'] ?? '' }}" placeholder="Enter coil number">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[{{$index}}][width]" class="form-control width @error('details.'.$index.'.width') is-invalid @enderror" value="{{ $detail['width'] ?? '' }}" placeholder="Width">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[{{$index}}][length]" class="form-control length @error('details.'.$index.'.length') is-invalid @enderror" value="{{ $detail['length'] ?? '' }}" placeholder="Length">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[{{$index}}][thickness]" class="form-control thickness @error('details.'.$index.'.thickness') is-invalid @enderror" value="{{ $detail['thickness'] ?? '' }}" placeholder="Thickness">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[{{$index}}][weight]" class="form-control weight @error('details.'.$index.'.weight') is-invalid @enderror" value="{{ $detail['weight'] ?? '' }}" placeholder="Weight">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" step="0.01" name="details[{{$index}}][price]" class="form-control price-item @error('details.'.$index.'.price') is-invalid @enderror" value="{{ $detail['price'] ?? '' }}" placeholder="Price">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-delete-item remove-line-item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                {{-- Baris default pertama --}}
                                <tr class="detail-item">
                                    <td>
                                        <input type="text" name="details[0][coil_number]" class="form-control coil-number" placeholder="Enter coil number">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[0][width]" class="form-control width" placeholder="Width">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[0][length]" class="form-control length" placeholder="Length">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[0][thickness]" class="form-control thickness" placeholder="Thickness">
                                            <span class="input-group-text">mm</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="details[0][weight]" class="form-control weight" placeholder="Weight">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" step="0.01" name="details[0][price]" class="form-control price-item" placeholder="Price">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-delete-item remove-line-item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total Price:</td>
                                <td class="fw-bold text-primary" id="total_price_cell">Rp 0,00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-text mt-2">
                    <i class="bi bi-info-circle me-1"></i> Total price is automatically calculated when you enter prices for each line item
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('invoices.index') }}'">
                    <i class="bi bi-x-lg me-2"></i> Cancel
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="save_draft">
                        <i class="bi bi-save me-2"></i> Save as Draft
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i> Create Invoice
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Modern UI Styles */
    :root {
        --primary: #4361ee;
        --primary-rgb: 67, 97, 238;
        --success: #10b981;
        --success-rgb: 16, 185, 129;
        --info: #0ea5e9;
        --info-rgb: 14, 165, 233;
        --warning: #f59e0b;
        --warning-rgb: 245, 158, 11;
        --danger: #ef4444;
        --danger-rgb: 239, 68, 68;
        --light-bg: #f8f9fb;
        --border-color: #e9ecef;
    }
    
    /* Page Header with Glass Effect */
    .page-header-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary), #6366f1);
        z-index: -1;
    }
    
    /* Card Styles */
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
    
    /* Info Card */
    .info-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 100%;
    }
    
    .info-card-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .info-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
    }
    
    .info-card-title {
        font-weight: 600;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }
    
    .info-card-body {
        padding: 1.5rem;
    }
    
    /* Summary Card */
    .summary-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 100%;
    }
    
    .summary-card-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .summary-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
    }
    
    .summary-card-title {
        font-weight: 600;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }
    
    .summary-card-body {
        padding: 1.5rem;
    }
    
    /* Summary Stats */
    .summary-stat {
        display: flex;
        align-items: center;
        background-color: var(--light-bg);
        border-radius: 12px;
        padding: 1.25rem;
        height: 100%;
    }
    
    .summary-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    
    .summary-stat-content {
        display: flex;
        flex-direction: column;
    }
    
    .summary-stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .summary-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    /* Completion Tracker */
    .completion-tracker {
        margin-top: 1.5rem;
        padding: 1.25rem;
        background-color: var(--light-bg);
        border-radius: 12px;
    }
    
    .completion-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .completion-percentage {
        font-weight: 600;
        color: var(--primary);
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: rgba(var(--primary-rgb), 0.1);
        overflow: hidden;
    }
    
    .progress-bar {
        background-color: var(--primary);
        transition: width 0.5s ease;
    }
    
    /* Section Header */
    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
    }
    
    .section-title {
        font-weight: 600;
        margin-bottom: 0;
    }
    
    /* Invoice Table */
    .invoice-table {
        margin-bottom: 0;
    }
    
    .invoice-table thead {
        background-color: var(--light-bg);
    }
    
    .invoice-table th {
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem 1.5rem;
        border-bottom-width: 1px;
    }
    
    .invoice-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }
    
    .invoice-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .invoice-table tbody tr:hover {
        background-color: rgba(var(--primary-rgb), 0.02);
    }
    
    .invoice-table tfoot {
        background-color: var(--light-bg);
    }
    
    .invoice-table tfoot td {
        padding: 1rem 1.5rem;
    }
    
    /* Form Controls */
    .form-control, .form-select {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.15);
        border-color: var(--primary);
    }
    
    .form-control-plaintext {
        font-weight: 600;
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .input-group-text {
        background-color: var(--light-bg);
        border-color: var(--border-color);
        color: #6c757d;
    }
    
    .form-label {
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    /* Delete Button */
    .btn-delete-item {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: rgba(var(--danger-rgb), 0.1);
        color: var(--danger);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-delete-item:hover {
        background-color: var(--danger);
        color: white;
        transform: translateY(-3px);
    }
    
    /* Buttons */
    .btn {
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-primary:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
    }
    
    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary);
        border-color: var(--primary);
        transform: translateY(-3px);
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .detail-item {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-card-header,
        .summary-card-header,
        .info-card-body,
        .summary-card-body {
            padding: 1rem;
        }
        
        .summary-stat {
            padding: 1rem;
        }
        
        .summary-stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }
        
        .summary-stat-value {
            font-size: 1.25rem;
        }
        
        .invoice-table th,
        .invoice-table td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('invoice_details_body');
        const addLineButton = document.getElementById('add_line_item');
        const templateRow = document.querySelector('.detail-item-template');
        const totalItemsCount = document.getElementById('total_items_count');
        const totalAmountDisplay = document.getElementById('total_amount_display');
        const formProgress = document.getElementById('form_progress');
        const progressPercentage = document.getElementById('progress_percentage');
        
        let rowIndex = tableBody.querySelectorAll('.detail-item').length; // Start from the number of existing items
        
        // Update total price and other summary information
        function updateSummary() {
            let total = 0;
            let itemCount = tableBody.querySelectorAll('.detail-item').length;
            let filledInputs = 0;
            let totalInputs = 0;
            
            tableBody.querySelectorAll('.detail-item').forEach(function(row) {
                // Calculate total price
                const priceInput = row.querySelector('.price-item');
                if (priceInput && priceInput.value) {
                    total += parseFloat(priceInput.value);
                }
                
                // Calculate form completion
                row.querySelectorAll('input').forEach(function(input) {
                    totalInputs++;
                    if (input.value) {
                        filledInputs++;
                    }
                });
            });
            
            // Update displays with animation
            const totalPriceCell = document.getElementById('total_price_cell');
            const formattedTotal = 'Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Add highlight effect
            totalPriceCell.classList.add('highlight');
            totalAmountDisplay.classList.add('highlight');
            
            // Update text
            totalPriceCell.textContent = formattedTotal;
            totalAmountDisplay.textContent = formattedTotal;
            totalItemsCount.textContent = itemCount;
            
            // Remove highlight effect after animation
            setTimeout(() => {
                totalPriceCell.classList.remove('highlight');
                totalAmountDisplay.classList.remove('highlight');
            }, 500);
            
            // Update progress bar
            const customerName = document.getElementById('customer_name').value ? 1 : 0;
            const deliveryDate = document.getElementById('delivery_date').value ? 1 : 0;
            const baseInputs = 2; // customer_name and delivery_date
            
            const progress = totalInputs > 0 ? Math.round(((filledInputs + customerName + deliveryDate) / (totalInputs + baseInputs)) * 100) : 0;
            
            // Animate progress bar
            formProgress.style.width = progress + '%';
            progressPercentage.textContent = progress + '%';
            
            // Change progress bar color based on completion
            if (progress < 30) {
                formProgress.className = 'progress-bar bg-danger';
            } else if (progress < 70) {
                formProgress.className = 'progress-bar bg-warning';
            } else {
                formProgress.className = 'progress-bar bg-success';
            }
        }
        
        // Add a new row with animation
        function addRow() {
            const newRow = templateRow.cloneNode(true);
            newRow.classList.remove('detail-item-template');
            newRow.classList.add('detail-item');
            newRow.style.display = ''; // Make it visible
            
            // Enable all inputs and replace index
            newRow.querySelectorAll('input, button').forEach(element => {
                element.disabled = false;
                if (element.name) {
                    element.name = element.name.replace(/__INDEX__/g, rowIndex);
                }
            });
            
            tableBody.appendChild(newRow);
            rowIndex++;
            
            // Add event listeners to the new row
            newRow.querySelector('.remove-line-item').addEventListener('click', function() {
                const row = this.closest('tr');
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.remove();
                    updateSummary();
                }, 300);
            });
            
            newRow.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', updateSummary);
            });
            
            updateSummary();
            
            // Focus on the first input of the new row
            newRow.querySelector('input').focus();
        }
        
        // Add event listener to the "Add Line" button
        addLineButton.addEventListener('click', addRow);
        
        // Add event listeners to existing rows
        tableBody.querySelectorAll('.detail-item').forEach(row => {
            row.querySelector('.remove-line-item').addEventListener('click', function() {
                const row = this.closest('tr');
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.remove();
                    updateSummary();
                }, 300);
            });
            
            row.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', updateSummary);
            });
        });
        
        // Add event listeners to main form fields
        document.getElementById('customer_name').addEventListener('input', updateSummary);
        document.getElementById('delivery_date').addEventListener('input', updateSummary);
        
        // Save as draft button (just for UI, doesn't actually save as draft)
        document.getElementById('save_draft').addEventListener('click', function() {
            // Show toast notification
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '1050';
            
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="toast-header">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    <strong class="me-auto">Information</strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    This would save the invoice as a draft. Feature not implemented in this demo.
                </div>
            `;
            
            toastContainer.appendChild(toast);
            document.body.appendChild(toastContainer);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toastContainer);
                }, 300);
            }, 3000);
        });
        
        // Calculate summary on page load
        updateSummary();
        
        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes highlight {
                0% { background-color: rgba(var(--primary-rgb), 0.2); }
                100% { background-color: transparent; }
            }
            
            .highlight {
                animation: highlight 0.5s ease;
            }
            
            .toast {
                position: relative;
                overflow: hidden;
                background-color: white;
                background-clip: padding-box;
                border: 1px solid rgba(0, 0, 0, 0.1);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                border-radius: 0.5rem;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .toast.show {
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
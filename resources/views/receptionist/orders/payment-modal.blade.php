<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card me-2"></i>Record Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="recordPaymentForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <!-- Payment Amount -->
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label fw-bold">
                            <i class="fas fa-coins me-2"></i>Payment Amount (PKR) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">PKR</span>
                            <input type="number" class="form-control" id="payment_amount" name="amount" 
                                   step="0.01" min="0.01" placeholder="0.00" required>
                        </div>
                        <small id="amountHelp" class="text-muted d-block mt-1"></small>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label fw-bold">
                            <i class="fas fa-wallet me-2"></i>Payment Method <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="cash">💵 Cash</option>
                            <option value="card">💳 Card</option>
                            <option value="bank_transfer">🏦 Bank Transfer</option>
                            <option value="online">📱 Online Payment</option>
                        </select>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label fw-bold">
                            <i class="fas fa-hashtag me-2"></i>Transaction ID (Optional)
                        </label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                               placeholder="Reference/Check#/Transaction ID" maxlength="100">
                        <small class="text-muted d-block mt-1">
                            For card: Card reference number | For bank: Cheque number | For online: Transaction ID
                        </small>
                    </div>

                    <!-- Payment Summary -->
                    <div class="alert alert-info" role="alert">
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Order Total:</strong></p>
                                <p class="mb-1"><strong>Already Paid:</strong></p>
                                <p class="mb-0"><strong>Remaining:</strong></p>
                            </div>
                            <div class="col-6 text-end">
                                <p id="totalAmount" class="mb-1 fw-bold">PKR 0.00</p>
                                <p id="alreadyPaid" class="mb-1 text-success fw-bold">PKR 0.00</p>
                                <p id="remainingAmount" class="mb-0 text-warning fw-bold">PKR 0.00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Payment Modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const recordPaymentForm = document.getElementById('recordPaymentForm');
    const paymentAmountInput = document.getElementById('payment_amount');
    const amountHelp = document.getElementById('amountHelp');
    const recordPaymentModal = document.getElementById('recordPaymentModal');

    // Update payment summary when modal is shown
    recordPaymentModal?.addEventListener('show.bs.modal', function() {
        const orderTotal = parseFloat(this.getAttribute('data-order-total') || 0);
        const alreadyPaid = parseFloat(this.getAttribute('data-already-paid') || 0);
        const remaining = Math.max(0, orderTotal - alreadyPaid);

        document.getElementById('totalAmount').textContent = 'PKR ' + orderTotal.toFixed(2);
        document.getElementById('alreadyPaid').textContent = 'PKR ' + alreadyPaid.toFixed(2);
        document.getElementById('remainingAmount').textContent = 'PKR ' + remaining.toFixed(2);

        // Set max amount to remaining balance
        paymentAmountInput.max = remaining;
        paymentAmountInput.value = remaining;
        updateAmountHelp();
    });

    // Update amount helper text
    paymentAmountInput?.addEventListener('input', updateAmountHelp);

    function updateAmountHelp() {
        const amount = parseFloat(paymentAmountInput.value) || 0;
        const remaining = parseFloat(document.getElementById('remainingAmount').textContent.replace('PKR ', ''));
        
        if (amount > remaining) {
            amountHelp.innerHTML = '<span class="text-warning">⚠️ Payment exceeds remaining balance!</span>';
            amountHelp.style.color = 'var(--bs-warning)';
        } else if (amount === remaining) {
            amountHelp.innerHTML = '<span class="text-success">✓ This will fully pay the order</span>';
            amountHelp.style.color = 'var(--bs-success)';
        } else if (amount > 0) {
            const newRemaining = (remaining - amount).toFixed(2);
            amountHelp.innerHTML = `<span class="text-info">Remaining after payment: PKR ${newRemaining}</span>`;
            amountHelp.style.color = 'var(--bs-info)';
        } else {
            amountHelp.textContent = '';
        }
    }

    // Form submission
    recordPaymentForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const action = this.action;

        fetch(action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Insert alert at top of content
                const contentArea = document.querySelector('.page-header')?.parentElement || document.body;
                contentArea.insertAdjacentHTML('afterbegin', alertHtml);

                // Close modal
                const modal = bootstrap.Modal.getInstance(recordPaymentModal);
                modal.hide();

                // Refresh page after 1 second
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>

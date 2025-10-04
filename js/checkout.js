document.addEventListener('DOMContentLoaded', () => {
    const shippingMethod = document.querySelector('select[name="shippingMethod"]');
    
    // Shipping costs
    const shippingCosts = {
        'standard': 150,
        'express': 350,
        'pickup': 0
    };
    
    // Update order summary with shipping cost
    function updateOrderSummary() {
        const selectedMethod = shippingMethod.value;
        const shippingCost = shippingCosts[selectedMethod];
        const subtotal = parseFloat(document.getElementById('co-sub').textContent.replace('₱', '').replace(',', ''));
        
        document.getElementById('co-ship').textContent = `₱${shippingCost.toFixed(2)}`;
        document.getElementById('co-total').textContent = `₱${(subtotal + shippingCost).toFixed(2)}`;
    }
    
    // Initialize shipping cost
    updateOrderSummary();
    
    // Update shipping cost when method changes
    shippingMethod.addEventListener('change', updateOrderSummary);
});
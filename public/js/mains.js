// IMPORTANT: If you change this file, do a hard refresh (Ctrl+F5) or use a cache-busting query string in your HTML script tag!
// Example: <script src="/js/mains.js?v=2"></script>
// Shopping Cart Toggle
document.addEventListener('DOMContentLoaded', function() {
    // Quantity Modal logic
    let selectedProductId = null;
    const quantityModal = document.getElementById('quantity-modal');
    const quantityInput = document.getElementById('modal-quantity-input');
    const modalAddBtn = document.getElementById('modal-add-btn');
    const modalCancelBtn = document.getElementById('modal-cancel-btn');

    function showQuantityModal(productId) {
        selectedProductId = productId;
        if (quantityInput) quantityInput.value = 1;
        if (quantityModal) quantityModal.style.display = 'flex';
    }
    function hideQuantityModal() {
        selectedProductId = null;
        if (quantityModal) quantityModal.style.display = 'none';
    }
    if (modalAddBtn) {
        modalAddBtn.onclick = function() {
            const qty = parseInt(quantityInput.value, 10) || 1;
            if (selectedProductId) {
                addToCart(selectedProductId, qty);
            }
            hideQuantityModal();
        };
    }
    if (modalCancelBtn) {
        modalCancelBtn.onclick = function() {
            hideQuantityModal();
        };
    }
    // Add to cart buttons (form and button)
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            showQuantityModal(productId);
        });
    });
    
    // Form validation
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const password = this.querySelector('#password');
            const confirmPassword = this.querySelector('#confirm_password');
            
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    });

    // Product Details Modal logic
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const modal = document.getElementById('product-modal-' + productId);
            if (modal) modal.style.display = 'flex';
        });
    });
    document.querySelectorAll('.close-modal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const modal = document.getElementById('product-modal-' + productId);
            if (modal) modal.style.display = 'none';
        });
    });
    // Optional: Close modal when clicking outside the modal card
    document.querySelectorAll('.product-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
});

function addToCart(productId, quantity = 1) {
    fetch('/add-to-cart.php', { // <-- .php is required!
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId, quantity })
    })
    .then(async response => {
        if (!response.ok) {
            // Try to extract error message from response, fallback to status text
            let errorMsg = response.statusText;
            try {
                const data = await response.json();
                errorMsg = data.error || errorMsg;
            } catch (e) {}
            throw new Error(errorMsg);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart);
        } else {
            alert('Failed to add to cart: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Failed to add to cart: ' + error.message);
        console.error(error);
    });
}

function updateCartUI(cart) {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        const itemCount = Object.keys(cart).length;
        cartBadge.textContent = itemCount;
        cartBadge.style.display = itemCount > 0 ? 'flex' : 'none';
    }
    
    // Update cart sidebar if open
    const cartSidebar = document.querySelector('.cart-sidebar');
    if (cartSidebar && cartSidebar.classList.contains('open')) {
        renderCartItems(cart);
    }
}

function renderCartItems(cart) {
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartTotalElement = document.querySelector('.cart-total');
    
    if (!cartItemsContainer || !cartTotalElement) return;
    
    cartItemsContainer.innerHTML = '';
    let total = 0;
    
    Object.entries(cart).forEach(([productId, item]) => {
        const cartItemElement = document.createElement('div');
        cartItemElement.className = 'cart-item';
        
        cartItemElement.innerHTML = `
            <div>
                <h4>${item.product.name}</h4>
                <p>${item.quantity} x $${item.product.price.toFixed(2)}</p>
            </div>
            <div>
                <button class="remove-item" data-product-id="${productId}">×</button>
            </div>
        `;
        
        cartItemsContainer.appendChild(cartItemElement);
        total += item.product.price * item.quantity;
    });
    
    cartTotalElement.textContent = `Total: $${total.toFixed(2)}`;
    
    // Add event listeners to remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            removeFromCart(this.dataset.productId);
        });
    });
}

function removeFromCart(productId) {
    fetch('remove-from-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart);
        }
    });
}
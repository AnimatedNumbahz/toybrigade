let cart = [];

// Add product to cart
function addToCart(name, price, img) {
    const existing = cart.find((item) => item.name === name);

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ name, price: parseFloat(price), quantity: 1, img });
    }

    updateCart();
    // ❌ Removed the auto modal popup
}

// Update cart modal UI
function updateCart() {
    const cartItems = document.getElementById("cartItems");
    const cartCount = document.getElementById("cart-count");
    const cartTotal = document.getElementById("cartTotal");

    cartItems.innerHTML = "";

    if (cart.length === 0) {
        cartItems.innerHTML = `<p class="empty-cart">🛒 Your cart is empty</p>`;
        cartTotal.textContent = "$0.00";
        cartCount.textContent = "0";
        return;
    }

    let total = 0;
    cart.forEach((item, index) => {
        total += item.price * item.quantity;

        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item", "d-flex", "align-items-center", "mb-3");
        cartItem.innerHTML = `
            <img src="${item.img}" alt="${item.name
            }" class="cart-item-img me-3">
            
            <div class="flex-grow-1">
                <p class="mb-1 fw-bold">${item.name}</p>
                <small class="text-muted">$${item.price.toFixed(2)}</small>
                
                <div class="cart-item-qty d-flex align-items-center mt-2">
                    <button class="qty-btn" onclick="changeQuantity(${index}, -1)">–</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="qty-btn" onclick="changeQuantity(${index}, 1)">+</button>
                </div>
            </div>

            <p class="cart-item-price mb-0">$${(
                item.price * item.quantity
            ).toFixed(2)}</p>
            <button class="btn btn-danger btn-square ms-3" onclick="removeFromCart(${index})">&times;</button>
        `;
        cartItems.appendChild(cartItem);
    });

    cartTotal.textContent = `$${total.toFixed(2)}`;
    cartCount.textContent = cart.reduce((acc, item) => acc + item.quantity, 0);
}

// ➕ New helper function
function changeQuantity(index, amount) {
    cart[index].quantity += amount;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1); // remove if zero
    }
    updateCart();
}

// Remove item from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Attach event listeners for all add-to-cart buttons
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-to-cart").forEach((button) => {
        button.addEventListener("click", () => {
            const name = button.getAttribute("data-product");
            const price = button.getAttribute("data-price");
            const img = button.closest(".card").querySelector("img").src; // grab product image

            addToCart(name, price, img);
        });
    });
});

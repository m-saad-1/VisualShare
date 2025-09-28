async function addToCart(product, quantity = 1, size = null, color = null) {
    console.log('Adding to cart:', { product, quantity, size, color });

    // Default selection for size and color
    if (!size && product.sizes && product.sizes.length > 0) {
        size = product.sizes[0];
    }
    if (!color && product.colors && product.colors.length > 0) {
        color = product.colors[0];
    }

    console.log('After default selection:', { product, quantity, size, color });

    if (!auth.currentUser) {
        showAuthAlert();
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/add_to_cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify({
                product_id: product.id,
                quantity: quantity,
                size: size,
                color: color
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }

        const data = await response.json();

        if (data.status === 'success') {
            await auth.loadUserCart();
            updateCartCount();
            showCartNotification(product);
        } else {
            console.error('Error adding to cart:', data.message);
            alert('Error adding product to cart: ' + data.message);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        addToCartLocal(product, quantity, size, color);
    }
}

function addToCartLocal(product, quantity = 1, size = null, color = null) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItemIndex = cart.findIndex(item =>
        item.id === product.id &&
        item.size === size &&
        item.color === color
    );

    if (existingItemIndex >= 0) {
        cart[existingItemIndex].quantity += quantity;
    } else {
        cart.push({
            id: product.id,
            title: product.title,
            price: product.price,
            image: product.image,
            size: size,
            color: color,
            quantity: quantity
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    updateCartCount();
    showCartNotification(product);
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = cartCount;
        el.style.display = cartCount > 0 ? 'flex' : 'none';
    });
}

function showCartNotification(product) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <p>${product.title} added to cart!</p>
        <a href="cart.php">View Cart</a>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function showAuthAlert() {
    const alert = document.createElement('div');
    alert.className = 'auth-alert';
    alert.innerHTML = `
        <div class="auth-alert-content">
            <h3>Account Required</h3>
            <p>Please create an account or login to add items to your wishlist or cart.</p>
            <div class="auth-alert-buttons">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="login.php" class="btn btn-outline">Create Account</a>
            </div>
        </div>
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.classList.add('show');
    }, 10);
    
    alert.addEventListener('click', function() {
        alert.classList.remove('show');
        setTimeout(() => {
            alert.remove();
        }, 300);
    });
}
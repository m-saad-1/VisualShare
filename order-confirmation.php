<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Order Confirmation</title>
      <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2a2a2a;
            --secondary-color: #d4a762;
            --accent-color: #e53935;
            --text-color: #333;
            --light-text: #777;
            --border-color: #e0e0e0;
            --bg-light: #f9f9f9;
            --white: #fff;
            --black: #000;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --error-color: #f44336;
            --font-main: 'Roboto', sans-serif;
            --font-heading: 'Playfair Display', serif;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            color: var(--text-color);
            line-height: 1.6;
            background-color: var(--bg-light);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }


        /* Confirmation Page Styles */
        .confirmation-page {
            padding: 40px 0;
            text-align: center;
        }
        
        .confirmation-card {
            background: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .confirmation-icon {
            color: #4CAF50;
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .confirmation-title {
            font-size: 32px;
            color: #2a5c8d;
            margin-bottom: 15px;
        }
        
        .confirmation-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .order-details {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .order-details h3 {
            color: #2a5c8d;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: 500;
            color: #555;
        }
        
        .detail-value {
            font-weight: 600;
        }
        
        .order-items {
            margin-top: 20px;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .item-details {
            flex: 1;
            text-align: left;
        }
        
        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .item-variants {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #666;
        }
        
        .item-quantity {
            color: #666;
            font-size: 14px;
        }
        
        .confirmation-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
    .btn-outline {
            background-color: transparent;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            cursor: pointer;
            transition: var(--transition);

        }

        .btn-outline:hover {
            background-color: rgba(212, 167, 98, 0.1);
            color: var(--secondary-color);
        }
        
        .btn-primary {
            background: #2a5c8d;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: #1e4a7a;
        }
        
        /* Footer */
        .main-footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 60px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .footer-col h3 {
            color: var(--white);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col li {
            margin-bottom: 10px;
        }

        .footer-col a {
            color: #ccc;
            transition: var(--transition);
        }

        .footer-col a:hover {
            color: var(--secondary-color);
        }

        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px 0 0 4px;
            outline: none;
        }

        .newsletter-form button {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary-color);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-methods {
            display: flex;
            gap: 10px;
        }

        .payment-methods i {
            font-size: 1.5rem;
            color: #ccc;
            transition: var(--transition);
        }

        .payment-methods i:hover {
            color: var(--secondary-color);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .confirmation-card {
                padding: 25px;
            }
            
            .confirmation-title {
                font-size: 28px;
            }
            
            .confirmation-subtitle {
                font-size: 16px;
            }
            
            .confirmation-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<?php include 'header.html'; ?>

    <main class="confirmation-page">
        <div class="container">
            <div class="confirmation-card">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="confirmation-title">Order Confirmed!</h1>
                <p class="confirmation-subtitle">Thank you for your purchase. Your order has been received and is being processed.</p>
                
                <div class="order-details">
                    <h3>Order Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Order Number:</span>
                        <span class="detail-value" id="order-number">FH-2025-0001</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value" id="order-date">June 15, 2023</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total:</span>
                        <span class="detail-value" id="order-total">$59.99</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Method:</span>
                        <span class="detail-value" id="payment-method">Credit Card</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value" id="order-status">Processing</span>
                    </div>
                    
                    <div class="order-items" id="order-items">
                        <!-- Order items will be inserted here -->
                    </div>
                </div>
                
                <div class="order-details">
                    <h3>Shipping Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value" id="shipping-name">John Smith</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span class极="detail-value" id="shipping-address">123 Main St, Apt 4B, New York, NY 10001</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value" id="shipping-phone">(123) 456-7890</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value" id="shipping-email">john.smith@example.com</span>
                    </div>
                </div>
                
                <div class="confirmation-actions">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="account.php#orders" class="btn btn-outline">View My Orders</a>
                </div>
            </div>
        </div>
    </main>

   <?php include 'footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get order data from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const orderDataParam = urlParams.get('order');

            let order;

            if (orderDataParam) {
                // Parse order data from URL parameter
                try {
                    order = JSON.parse(decodeURIComponent(orderDataParam));
                    console.log("Order data from URL:", order);
                    console.log("Order data source: URL parameter");
                } catch (e) {
                    console.error('Error parsing order data:', e);
                }
            }

            // If no order data in URL, try to get from sessionStorage (from notification redirect)
            if (!order) {
                const sessionOrderData = sessionStorage.getItem('pendingOrderData');
                if (sessionOrderData) {
                    try {
                        order = JSON.parse(sessionOrderData);
                        console.log("Order data from sessionStorage:", order);
                        console.log("Order data source: sessionStorage");
                        // Clear the sessionStorage after use
                        sessionStorage.removeItem('pendingOrderData');
                    } catch (e) {
                        console.error('Error parsing session order data:', e);
                    }
                }
            }

            // If still no order data, try to get the latest order from localStorage (only as last resort)
            if (!order) {
                const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
                if (currentUser.orders && currentUser.orders.length > 0) {
                    // Get the most recent order
                    order = currentUser.orders[currentUser.orders.length - 1];
                    console.log("Order data from localStorage:", order);
                    console.log("Order data source: localStorage (fallback - may be outdated)");
                    console.warn("WARNING: Using localStorage order data. This may not reflect the most recent order!");
                }
            }
            
            if (order) {
                // Display order details - use backend-generated order number if available
                const orderNumber = order.order_number || order.orderNumber || order.id;

                // Debug logging to track order ID consistency
                console.log('Order confirmation - Full order data:', order);
                console.log('Order confirmation - Using order number:', orderNumber);
                console.log('Order confirmation - Available fields:', Object.keys(order));
                if (order.debug) {
                    console.log('Order confirmation - Debug info:', order.debug);
                }

                document.getElementById('order-number').textContent = orderNumber;
                document.getElementById('order-date').textContent = new Date(order.date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('order-total').textContent = `$${order.total.toFixed(2)}`;

                // Display payment method with actual last four digits if available
                let paymentMethodText = order.paymentMethod;
                if (order.lastFourDigits && order.paymentMethod.includes('Credit Card')) {
                    paymentMethodText = `Credit/Debit Card ending in ${order.lastFourDigits}`;
                }
                document.getElementById('payment-method').textContent = paymentMethodText;

                document.getElementById('order-status').textContent = order.status;
                
                // Display shipping information
                document.getElementById('shipping-name').textContent = order.shippingAddress.name;
                document.getElementById('shipping-address').textContent = 
                    `${order.shippingAddress.address}${order.shippingAddress.address2 ? ', ' + order.shippingAddress.address2 : ''}, 
                    ${order.shippingAddress.city}, ${order.shippingAddress.state} ${order.shippingAddress.zip}, 
                    ${order.shippingAddress.country}`;
                document.getElementById('shipping-phone').textContent = order.shippingAddress.phone;
                document.getElementById('shipping-email').textContent = order.shippingAddress.email;
                
                // Display order items (sorted with most recent at top)
                const orderItemsContainer = document.getElementById('order-items');
                if (order.items && order.items.length > 0) {
                    // Sort items with most recent at top
                    const sortedItems = [...order.items].sort((a, b) => {
                        // If items have timestamps, sort by timestamp (most recent first)
                        if (a.timestamp && b.timestamp) {
                            return new Date(b.timestamp) - new Date(a.timestamp);
                        }
                        // Otherwise sort by ID (assuming higher IDs are more recent)
                        const aId = parseInt(a.id) || 0;
                        const bId = parseInt(b.id) || 0;
                        return bId - aId;
                    });

                    sortedItems.forEach(item => {
                        const itemElement = document.createElement('div');
                        itemElement.className = 'order-item';

                        // Create variant details text
                        let variantDetails = '';
                        if (item.size && item.size !== 'N/A') {
                            variantDetails += `Size: ${item.size}`;
                        }
                        if (item.color && item.color !== 'N/A') {
                            if (variantDetails) variantDetails += ' | ';
                            variantDetails += `Color: ${item.color}`;
                        }

                        itemElement.innerHTML = `
                            <img src="${item.image || 'https://via.placeholder.com/80'}" alt="${item.title}" class="item-image">
                            <div class="item-details">
                                <div class="item-name">${item.title}</div>
                                ${variantDetails ? `<div class="item-variants">${variantDetails}</div>` : ''}
                                <div class="item-price">$${item.price.toFixed(2)}</div>
                                <div class="item-quantity">Quantity: ${item.quantity}</div>
                            </div>
                        `;
                        orderItemsContainer.appendChild(itemElement);
                    });
                } else {
                    orderItemsContainer.innerHTML = '<p>No items found in this order.</p>';
                }
            } else {
                // If order not found, show error message
                document.querySelector('.confirmation-icon').innerHTML = '<i class="fas fa-exclamation-circle" style="color:#e74c3c"></i>';
                document.querySelector('.confirmation-title').textContent = 'Order Not Found';
                document.querySelector('.confirmation-subtitle').textContent = 'We couldn\'t find the details for this order. Please check your order history or contact support.';
                document.querySelector('.order-details').style.display = 'none';
            }

            // Update cart and wishlist counts
            function updateCartCount() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = cartCount;
                    el.style.display = cartCount > 0 ? 'flex' : 'none';
                });
            }

            function updateWishlistCount() {
                const currentUser = JSON.parse(localStorage.getItem('currentUser')) || { wishlist: [] };
                const wishlistCount = currentUser.wishlist.length;
                document.querySelectorAll('.wishlist-count').forEach(el => {
                    el.textContent = wishlistCount;
                    el.style.display = wishlistCount > 0 ? 'flex' : 'none';
                });
            }

            updateCartCount();
            updateWishlistCount();

            // Mobile menu toggle
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const mainNav = document.querySelector('.main-nav');

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    mainNav.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
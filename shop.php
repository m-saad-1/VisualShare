<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Shop Our Collection</title>
    <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Loading Animation Styles */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
            width: 100%;
        }
        
        .dot-spinner {
            --uib-size: 2.8rem;
            --uib-speed: .9s;
            --uib-color: #d4a762;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: var(--uib-size);
            width: var(--uib-size);
        }

        .dot-spinner__dot {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
            width: 100%;
        }

        .dot-spinner__dot::before {
            content: '';
            height: 20%;
            width: 20%;
            border-radius: 50%;
            background-color: var(--uib-color);
            transform: scale(0);
            opacity: 0.5;
            animation: pulse0112 calc(var(--uib-speed) * 1.111) ease-in-out infinite;
            box-shadow: 0 0 20px rgba(212, 167, 98, 0.3);
        }

        .dot-spinner__dot:nth-child(2) {
            transform: rotate(45deg);
        }

        .dot-spinner__dot:nth-child(2)::before {
            animation-delay: calc(var(--uib-speed) * -0.875);
        }

        .dot-spinner__dot:nth-child(3) {
            transform: rotate(90deg);
        }

        .dot-spinner__dot:nth-child(3)::before {
            animation-delay: calc(var(--uib-speed) * -0.75);
        }

        .dot-spinner__dot:nth-child(4) {
            transform: rotate(135deg);
        }

        .dot-spinner__dot:nth-child(4)::before {
            animation-delay: calc(var(--uib-speed) * -0.625);
        }

        .dot-spinner__dot:nth-child(5) {
            transform: rotate(180deg);
        }

        .dot-spinner__dot:nth-child(5)::before {
            animation-delay: calc(var(--uib-speed) * -0.5);
        }

        .dot-spinner__dot:nth-child(6) {
            transform: rotate(225deg);
        }

        .dot-spinner__dot:nth-child(6)::before {
            animation-delay: calc(var(--uib-speed) * -0.375);
        }

        .dot-spinner__dot:nth-child(7) {
            transform: rotate(270deg);
        }

        .dot-spinner__dot:nth-child(7)::before {
            animation-delay: calc(var(--uib-speed) * -0.25);
        }

        .dot-spinner__dot:nth-child(8) {
            transform: rotate(315deg);
        }

        .dot-spinner__dot:nth-child(8)::before {
            animation-delay: calc(var(--uib-speed) * -0.125);
        }

        @keyframes pulse0112 {
            0%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Reset and Base Styles */
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

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
        }

        h3 {
            font-size: 1.75rem;
        }

        p {
            margin-bottom: 1rem;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: #c49555;
        }

        .btn-outline {
            background-color: transparent;
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline:hover {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .btn-text {
            background: none;
            border: none;
            color: var(--secondary-color);
            padding: 0;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        /* Shop Page Specific Styles */
        .shop-page {
            padding: 60px 0;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .breadcrumbs {
            color: var(--light-text);
            font-size: 0.9rem;
        }

        .breadcrumbs a {
            color: var(--light-text);
        }

        .breadcrumbs a:hover {
            color: var(--secondary-color);
        }

        .shop-content {
            display: flex;
            gap: 30px;
        }

        /* Filters Sidebar */
        .shop-filters {
            width: 250px;
            flex-shrink: 0;
        }

        .filter-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .filter-section h3 {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .filter-list {
            list-style: none;
        }

        .filter-list li {
            margin-bottom: 8px;
        }

        .filter-list a {
            display: block;
            padding: 5px 0;
            color: var(--light-text);
            transition: var(--transition);
        }

        .filter-list a:hover,
        .filter-list a.active {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .btn-secondary:hover {
            background-color: #c49555;
        }

        .btn-text {
            background: none;
            border: none;
            color: var(--secondary-color);
            padding: 0;
        }

        /* Product Listing */
        .product-listing {
            flex: 1;
        }

        .listing-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sort-options {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-options select {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: var(--white);
            outline: none;
            font-family: inherit;
        }

        .view-options {
            display: flex;
            gap: 10px;
        }

        .view-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            color: var(--light-text);
        }

        .view-btn.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .product-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--accent-color);
            color: var(--white);
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 15px;
        }

        .product-title {
            font-size: 1rem;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .current-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--secondary-color);
        }

        .old-price {
            text-decoration: line-through;
            color: var(--light-text);
            font-size: 0.9rem;
        }

        .discount {
            background-color: var(--accent-color);
            color: var(--white);
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stars {
            color: var(--secondary-color);
        }

        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            background-color: var(--white);
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: var(--transition);
        }

        .product-card:hover .product-actions {
            bottom: 0;
            opacity: 1;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background-color: var(--primary-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 5px;
        }

        .page-nav,
        .page-num {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            transition: var(--transition);
        }

        .page-num.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }

        .page-nav:hover,
        .page-num:hover:not(.active) {
            background-color: var(--bg-light);
        }

        .page-nav.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Add to existing styles */
        .auth-alert {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.7);
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .auth-alert.show {
            opacity: 1;
            visibility: visible;
        }

        .auth-alert-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .auth-alert.show .auth-alert-content {
            transform: translateY(0);
        }

        .auth-alert h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .auth-alert p {
            margin: 15px 0 25px;
            color: var(--text-color);
        }

        .auth-alert-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Cart Notification */
        .cart-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--secondary-color);
            color: var(--white);
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: var(--transition);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .cart-notification a {
            color: var(--white);
            text-decoration: underline;
            font-weight: 500;
        }

        /* Product Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .product-modal {
            background-color: var(--white);
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transform: translateY(50px);
            transition: var(--transition);
        }

        .modal-overlay.active .product-modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--light-text);
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--accent-color);
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        @media (min-width: 768px) {
            .modal-content {
                flex-direction: row;
                gap: 30px;
            }
        }

        .modal-image {
            flex: 1;
            min-height: 300px;
            border-radius: 8px;
            overflow: hidden;
        }

        .modal-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .modal-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .modal-old-price {
            text-decoration: line-through;
            color: var(--light-text);
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .modal-discount {
            background-color: var(--accent-color);
            color: var(--white);
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .modal-description {
            color: var(--light-text);
            line-height: 1.7;
        }

        .modal-features {
            margin-top: 10px;
        }

        .modal-features ul {
            list-style-position: inside;
            margin-top: 5px;
        }

        .modal-features li {
            margin-bottom: 5px;
            color: var(--light-text);
        }

        .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .size-btn {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            background: none;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .size-btn:hover,
        .size-btn.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }

        .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .color-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        .color-btn::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            opacity: 0;
            transition: var(--transition);
        }

        .color-btn:hover::after,
        .color-btn.active::after {
            opacity: 1;
            border-color: var(--secondary-color);
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-light);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: var(--white);
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            flex: 1;
        }

        .btn-primary:hover {
            background-color: #c49555;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--secondary-color);
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: 500;
            border: 1px solid var(--secondary-color);
            cursor: pointer;
            transition: var(--transition);
            flex: 1;
        }

        .btn-outline:hover {
            background-color: rgba(212, 167, 98, 0.1);
            color: var(--secondary-color);
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            color: var(--light-text);
            font-size: 0.9rem;
        }

        .modal-sku {
            font-weight: 500;
        }

        .modal-category {
            text-transform: capitalize;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .shop-content {
                flex-direction: column;
            }
            
            .shop-filters {
                width: 100%;
                margin-bottom: 30px;
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .listing-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .modal-content {
                flex-direction: column;
            }

            .modal-image {
                min-height: 200px;
            }
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Loading Animation Container -->
    <div id="loading-container" class="loading-container">
        <div class="dot-spinner">
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
        </div>
    </div>

    <!-- Main Content Container (initially hidden) -->
    <div id="main-content" style="display: none;">
        <?php include 'header.html'; ?>

        <main class="shop-page">
            <div class="container">
                <div class="page-header">
                    <h1>Shop Our Collection</h1>
                    <div class="breadcrumbs">
                        <a href="index.html">Home</a> / <span>Shop</span>
                    </div>
                </div>
                
                <div class="shop-content">
                    <!-- Filters Sidebar -->
                    <aside class="shop-filters">
                        <div class="filter-section">
                            <h3>Categories</h3>
                            <ul class="filter-list">
                                <li><a href="#" class="active" data-category="all">All Products</a></li>
                                <li><a href="#" data-category="shirts">Shirts</a></li>
                                <li><a href="#" data-category="jeans">Jeans</a></li>
                                <li><a href="#" data-category="jackets">Jackets</a></li>
                                <li><a href="#" data-category="accessories">Accessories</a></li>
                            </ul>
                        </div>
                        
                        <button class="btn btn-secondary apply-filters">Apply Filters</button>
                        <button class="btn btn-text reset-filters">Reset All</button>
                    </aside>
                    
                    <!-- Product Grid -->
                    <div class="product-listing">
                        <div class="listing-header">
                            <div class="sort-options">
                                <label for="sortBy">Sort by:</label>
                                <select id="sortBy">
                                    <option value="featured">Featured</option>
                                    <option value="newest">Newest</option>
                                    <option value="price-low">Price: Low to High</option>
                                    <option value="price-high">Price: High to Low</option>
                                    <option value="rating">Customer Rating</option>
                                </select>
                            </div>
                            <div class="view-options">
                                <span class="view-btn active" data-view="grid"><i class="fas fa-th"></i></span>
                                <span class="view-btn" data-view="list"><i class="fas fa-list"></i></span>
                            </div>
                        </div>
                        
                        <div class="product-grid grid-view" id="productGrid">
                            <!-- Products will be loaded via JavaScript -->
                        </div>
                        
                        <div class="pagination">
                            <a href="#" class="page-nav disabled"><i class="fas fa-chevron-left"></i></a>
                            <a href="#" class="page-num active">1</a>
                            <a href="#" class="page-num">2</a>
                            <a href="#" class="page-num">3</a>
                            <span class="page-dots">...</span>
                            <a href="#" class="page-num">8</a>
                            <a href="#" class="page-nav"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Product Modal -->
        <div class="modal-overlay" id="productModal">
            <div class="product-modal">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalProductTitle">Product Name</h2>
                    <button class="close-modal" id="closeModal">&times;</button>
                </div>
                <div class="modal-content">
                    <div class="modal-image">
                        <img src="" alt="" id="modalProductImage">
                    </div>
                    <div class="modal-details">
                        <div class="modal-price-container">
                            <span class="modal-price" id="modalProductPrice">$59.99</span>
                            <span class="modal-old-price" id="modalProductOldPrice"></span>
                            <span class="modal-discount" id="modalProductDiscount"></span>
                        </div>
                        <div class="rating">
                            <div class="stars" id="modalProductRating"></div>
                            <span class="review-count" id="modalProductReviews"></span>
                        </div>
                        <p class="modal-description" id="modalProductDescription">
                            This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. 
                            The tailored fit provides a modern silhouette while allowing freedom of movement. 
                            Perfect for both casual and business casual occasions.
                        </p>
                        <div class="modal-features">
                            <h4>Features:</h4>
                            <ul id="modalProductFeatures">
                                <li>100% Organic Cotton</li>
                                <li>Button-down collar</li>
                                <li>Single chest pocket</li>
                                <li>Tailored fit</li>
                                <li>Machine washable</li>
                            </ul>
                        </div>
                        <div class="size-selection">
                            <h4>Size:</h4>
                            <div class="size-options" id="modalSizeOptions">
                                <!-- Size options will be loaded via JavaScript -->
                            </div>
                        </div>
                        <div class="color-selection">
                            <h4>Color:</h4>
                            <div class="color-options" id="modalColorOptions">
                                <!-- Color options will be loaded via JavaScript -->
                            </div>
                        </div>
                        <div class="quantity-selector">
                            <h4>Quantity:</h4>
                            <button class="quantity-btn" id="decreaseQty">-</button>
                            <input type="number" min="1" value="1" class="quantity-input" id="productQuantity">
                            <button class="quantity-btn" id="increaseQty">+</button>
                        </div>
                        <div class="modal-actions">
                            <button class="btn-primary" id="addToCartModal">Add to Cart</button>
                            <button class="btn-outline" id="addToWishlistModal">Add to Wishlist</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="modal-sku">SKU: <span id="modalProductSKU">FH-001</span></span>
                    <a href="#" id="viewDetailsLink" class="btn btn-text" style="margin-left: auto;">View Full Details</a>
                    <span class="modal-category">Category: <span id="modalProductCategory">Shirts</span></span>
                </div>
            </div>
        </div>

        <?php include 'footer.html'; ?>
    </div>

    <script>
        // ==============================================
        // LOADING STATE MANAGEMENT
        // ==============================================
        function showLoading() {
            document.getElementById('loading-container').style.display = 'flex';
            document.getElementById('main-content').style.display = 'none';
        }
        
        function hideLoading() {
            document.getElementById('loading-container').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        }

        // API Configuration - ADD THIS AT THE TOP
        const API_BASE = window.location.origin + '/api';

        // Product Data
        const products = [
            {
                id: 1,
                title: "Premium Cotton Shirt",
                category: "shirts",
                price: 59.99,
                oldPrice: 74.99,
                image: "https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "blue", "white"],
                sizes: ["XS", "S", "M", "L", "XL"],
                rating: 4.5,
                reviews: 24,
                badge: "sale",
                featured: true,
                newArrival: false,
                sku: "FH-001",
                description: "This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. The tailored fit provides a modern silhouette while allowing freedom of movement. Perfect for both casual and business casual occasions.",
                features: [
                    "100% Organic Cotton",
                    "Button-down collar",
                    "Single chest pocket",
                    "Tailored fit",
                    "Machine washable"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "blue": "#5a8ac1",
                    "white": "#e6e6e6"
                }
            },
            {
                id: 2,
                title: "Slim Fit Jeans",
                category: "jeans",
                price: 79.99,
                oldPrice: 89.99,
                image: "https://images.unsplash.com/photo-1542272604-787c3835535d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "blue"],
                sizes: ["28", "30", "32", "34", "36"],
                rating: 4.0,
                reviews: 18,
                badge: "new",
                featured: false,
                newArrival: true,
                sku: "FH-002",
                description: "These slim fit jeans are designed for a modern, tailored look. Made from premium denim with just the right amount of stretch for comfort. The dark wash makes them versatile enough for both casual and dressier occasions.",
                features: [
                    "98% Cotton, 2% Elastane",
                    "Slim fit through hip and thigh",
                    "Zip fly with button closure",
                    "Five-pocket styling",
                    "Machine wash cold"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "blue": "#5a8ac1"
                }
            },
            {
                id: 3,
                title: "Classic Denim Jacket",
                category: "jackets",
                price: 99.99,
                image: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["blue", "black"],
                sizes: ["S", "M", "L", "XL"],
                rating: 5.0,
                reviews: 32,
                badge: null,
                featured: true,
                newArrival: false,
                sku: "FH-003",
                description: "This timeless denim jacket is a wardrobe essential. Made from durable 12-ounce denim, it features a classic fit that layers easily over your favorite tops. The jacket has a button-front closure, chest pockets, and adjustable waist tabs for a custom fit.",
                features: [
                    "100% Cotton denim",
                    "Classic fit",
                    "Button-front closure",
                    "Chest pockets with flap",
                    "Adjustable waist tabs"
                ],
                colorCodes: {
                    "blue": "#5a8ac1",
                    "black": "#3a3a3a"
                }
            },
            {
                id: 4,
                title: "Casual Summer Dress",
                category: "dresses",
                price: 69.99,
                oldPrice: 79.99,
                image: "https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["beige", "white", "black"],
                sizes: ["XS", "S", "M"],
                rating: 4.0,
                reviews: 21,
                badge: "sale",
                featured: false,
                newArrival: false,
                sku: "FH-004",
                description: "This breezy summer dress is perfect for warm weather. Made from lightweight linen blend fabric that drapes beautifully and keeps you cool. The wrap-style design with tie waist creates a flattering silhouette for all body types.",
                features: [
                    "65% Linen, 35% Cotton",
                    "Wrap-style with tie waist",
                    "V-neckline",
                    "Short sleeves",
                    "Machine wash gentle"
                ],
                colorCodes: {
                    "beige": "#d4a762",
                    "white": "#e6e6e6",
                    "black": "#3a3a3a"
                }
            },
            {
                id: 5,
                title: "Leather Crossbody Bag",
                category: "accessories",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1543076447-215ad9ba6923?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "beige"],
                sizes: ["One Size"],
                rating: 4.5,
                reviews: 27,
                badge: null,
                featured: true,
                newArrival: false,
                sku: "FH-005",
                description: "This stylish crossbody bag is crafted from genuine leather that develops a beautiful patina over time. The compact design fits all your essentials while keeping your hands free. Features multiple compartments for organization and an adjustable strap for comfort.",
                features: [
                    "Genuine leather",
                    "Adjustable crossbody strap",
                    "Main zip compartment",
                    "Interior slip pocket",
                    "Exterior back zip pocket"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 6,
                title: "Premium Wool Coat",
                category: "jackets",
                price: 129.99,
                image: "https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "beige"],
                sizes: ["S", "M", "L"],
                rating: 4.0,
                reviews: 15,
                badge: "new",
                featured: false,
                newArrival: true,
                sku: "FH-006",
                description: "This premium wool coat is perfect for transitional weather. Made from a wool blend that provides warmth without bulk. The tailored silhouette and notched lapel create a polished look that works from office to evening.",
                features: [
                    "70% Wool, 30% Polyester",
                    "Notched lapel",
                    "Single-breasted button front",
                    "Flap pockets",
                    "Lined interior"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 7,
                title: "Linen Button-Up Shirt",
                category: "shirts",
                price: 49.99,
                oldPrice: 59.99,
                image: "https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["white", "blue"],
                sizes: ["S", "M", "L", "XL"],
                rating: 4.5,
                reviews: 19,
                badge: "sale",
                featured: true,
                newArrival: false,
                sku: "FH-007",
                description: "This lightweight linen shirt is perfect for warm weather. The breathable fabric and relaxed fit keep you cool and comfortable all day long. The button-up design makes it versatile enough for both casual and dressier occasions.",
                features: [
                    "100% Linen",
                    "Button-up front",
                    "Chest pocket",
                    "Relaxed fit",
                    "Machine washable"
                ],
                colorCodes: {
                    "white": "#e6e6e6",
                    "blue": "#5a8ac1"
                }
            },
            {
                id: 8,
                title: "Cashmere Scarf",
                category: "accessories",
                price: 59.99,
                image: "https://images.unsplash.com/photo-1674515625083-24e5d544a532?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGNhc2htZXJlJTIwc2NhcmZ8ZW58MHx8MHx8fDA%3D",
                colors: ["red", "black", "beige"],
                sizes: ["One Size"],
                rating: 5.0,
                reviews: 12,
                badge: null,
                featured: false,
                newArrival: false,
                sku: "FH-008",
                description: "This luxurious cashmere scarf is the perfect accessory for cooler weather. The ultra-soft cashmere provides warmth without bulk, and the generous size allows for versatile styling. A timeless piece that will last for years.",
                features: [
                    "100% Cashmere",
                    "Generous size: 70\" x 12\"",
                    "Fringed ends",
                    "Ultra-soft hand feel",
                    "Dry clean recommended"
                ],
                colorCodes: {
                    "red": "#ff0000",
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 9,
                title: "Classic White Sneakers",
                category: "shoes",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["white", "black"],
                sizes: ["US 7", "US 8", "US 9", "US 10", "US 11"],
                rating: 4.8,
                reviews: 42,
                badge: "best-seller",
                featured: true,
                newArrival: false,
                sku: "FH-009",
                description: "These classic white sneakers are a wardrobe staple. Made from premium leather with a comfortable cushioned insole, they're perfect for all-day wear. The timeless design pairs well with any outfit.",
                features: [
                    "Premium leather upper",
                    "Cushioned insole for comfort",
                    "Rubber outsole for traction",
                    "Lace-up closure",
                    "Machine washable"
                ],
                colorCodes: {
                    "white": "#ffffff",
                    "black": "#3a3a3a"
                }
            },
            {
                id: 10,
                title: "Wool Blend Sweater",
                category: "sweaters",
                price: 79.99,
                oldPrice: 99.99,
                image: "https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["navy", "gray", "burgundy"],
                sizes: ["S", "M", "L", "XL"],
                rating: 4.3,
                reviews: 28,
                badge: "sale",
                featured: true,
                newArrival: false,
                sku: "FH-010",
                description: "This wool blend sweater offers warmth and style for the cooler months. The relaxed fit and ribbed cuffs provide both comfort and a polished look. Perfect for layering or wearing on its own.",
                features: [
                    "70% Wool, 30% Acrylic",
                    "Ribbed cuffs and hem",
                    "Relaxed fit",
                    "Crew neckline",
                    "Machine wash cold"
                ],
                colorCodes: {
                    "navy": "#001f3f",
                    "gray": "#aaaaaa",
                    "burgundy": "#800020"
                }
            },
            {
                id: 11,
                title: "Silk Blouse",
                category: "shirts",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["ivory", "black", "sage"],
                sizes: ["XS", "S", "M", "L"],
                rating: 4.6,
                reviews: 35,
                badge: null,
                featured: false,
                newArrival: true,
                sku: "FH-011",
                description: "This elegant silk blouse features a delicate drape and subtle sheen that elevates any outfit. The button-front design and pointed collar create a sophisticated look perfect for work or special occasions.",
                features: [
                    "100% Silk",
                    "Button-front closure",
                    "Pointed collar",
                    "Long sleeves with button cuffs",
                    "Dry clean only"
                ],
                colorCodes: {
                    "ivory": "#fffff0",
                    "black": "#3a3a3a",
                    "sage": "#b2ac88"
                }
            },
            {
                id: 12,
                title: "Leather Belt",
                category: "accessories",
                price: 49.99,
                image: "https://images.unsplash.com/photo-1664286074176-5206ee5dc878?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                colors: ["brown", "black"],
                sizes: ["S (30\")", "M (32\")", "L (34\")", "XL (36\")"],
                rating: 4.2,
                reviews: 19,
                badge: null,
                featured: false,
                newArrival: false,
                sku: "FH-012",
                description: "This genuine leather belt features a classic design with a polished buckle. The high-quality leather develops a beautiful patina over time, making it a durable and stylish accessory.",
                features: [
                    "Genuine leather",
                    "Polished metal buckle",
                    "Adjustable fit",
                    "Reversible (black/brown option)",
                    "Width: 1.5 inches"
                ],
                colorCodes: {
                    "brown": "#964B00",
                    "black": "#3a3a3a"
                }
            }
        ];

        // Shopping Cart - Now stored in MySQL database
        let cart = [];
        let cartCount = 0;

        // Current filters
        let currentFilters = {
            category: 'all'
        };

        // Current sort
        let currentSort = 'featured';

        // Currently selected product in modal
        let currentModalProduct = null;
        let selectedSize = null;
        let selectedColor = null;

        // Pagination variables
        const productsPerPage = 9;
        let currentPage = 1;
        let totalPages = 1;
        let filteredProducts = [];

        // Auth check
        const auth = {
            currentUser: null
        };

        // Custom event for authentication changes
        const authEvent = new Event('authChange');

        // Initialize authentication
        async function initializeAuth() {
            try {
                const response = await fetch(`${API_BASE}/check_auth.php`, {
                    credentials: 'include'
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    auth.currentUser = {
                        id: data.user.id,
                        name: data.user.name,
                        email: data.user.email,
                        wishlist: [] // Will be populated from database
                    };
                    
                    // Load wishlist items and cart
                    await loadWishlistItems();
                    await loadCartItems();
                } else {
                    // User is not authenticated
                    auth.currentUser = null;
                    cart = [];
                    cartCount = 0;
                    localStorage.removeItem('cart');
                    localStorage.removeItem('currentUser');
                }
                
                // Dispatch auth change event
                window.dispatchEvent(authEvent);
                
            } catch (error) {
                console.error('Auth initialization error:', error);
                // Fallback to localStorage if API call fails
                auth.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
                cart = JSON.parse(localStorage.getItem('cart')) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                
                // Dispatch auth change event
                window.dispatchEvent(authEvent);
            }
            
            // Update UI based on auth status
            updateCartCount();
            updateWishlistCount();
        }

        // Load cart items from database
        async function loadCartItems() {
            if (!auth.currentUser) {
                cart = JSON.parse(localStorage.getItem('cart')) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                updateCartCount();
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE}/get_cart.php`, {
                    credentials: 'include'
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    cart = data.cart;
                    cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                    // Also update localStorage for consistency
                    localStorage.setItem('cart', JSON.stringify(cart));
                    // Update cart count in UI
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                // Fallback to localStorage if API call fails
                cart = JSON.parse(localStorage.getItem('cart')) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                updateCartCount();
            }
        }

        // Load wishlist items from database
        async function loadWishlistItems() {
            if (!auth.currentUser) return;
            
            try {
                const response = await fetch(`${API_BASE}/get_wishlist.php`, {
                    credentials: 'include'
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update user's wishlist with product details
                    auth.currentUser.wishlist = data.wishlist.map(item => ({
                        id: item.product_id,
                        title: item.title,
                        price: item.price,
                        image: item.image
                    }));
                    
                    // Update localStorage for consistency
                    localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
                }
            } catch (error) {
                console.error('Error loading wishlist:', error);
                // Fallback to localStorage if API call fails
                if (!auth.currentUser.wishlist) {
                    auth.currentUser.wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
                }
            }
        }

        // Listen for authentication changes
        window.addEventListener('authChange', function() {
            updateCartCount();
            updateWishlistCount();
        });

        // Listen for logout events (if you have a logout function)
        window.addEventListener('userLogout', function() {
            auth.currentUser = null;
            cart = [];
            cartCount = 0;
            localStorage.removeItem('cart');
            localStorage.removeItem('currentUser');
            updateCartCount();
            updateWishlistCount();
        });

        // Listen for login events from the login page
        window.addEventListener('userLogin', async function(e) {
            // Update auth state with the user data from the login event
            auth.currentUser = e.detail;
            
            // Load cart items for the newly logged in user
            await loadCartItems();
            
            // Update UI
            updateCartCount();
            updateWishlistCount();
            
            // Re-render products to update wishlist status
            applyFilters();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Show loading animation on page load
            showLoading();
            
            // Initialize authentication and wishlist
            initializeAuth().then(() => {
                // Initialize the page after auth is loaded
                applyFilters();
                
                // Category filtering
                const categoryLinks = document.querySelectorAll('.filter-list a');
                categoryLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Remove active class from all category links
                        categoryLinks.forEach(l => l.classList.remove('active'));
                        
                        // Add active class to clicked link
                        this.classList.add('active');
                        
                        // Update current filter
                        currentFilters.category = this.dataset.category;
                        
                        // Reset to first page when changing filters
                        currentPage = 1;
                        
                        // Apply filters
                        applyFilters();
                    });
                });
                
                // Apply filters button
                const applyFiltersBtn = document.querySelector('.apply-filters');
                if (applyFiltersBtn) {
                    applyFiltersBtn.addEventListener('click', function() {
                        currentPage = 1;
                        applyFilters();
                    });
                }
                
                // Reset filters button
                const resetFiltersBtn = document.querySelector('.reset-filters');
                if (resetFiltersBtn) {
                    resetFiltersBtn.addEventListener('click', function() {
                        // Reset category filter
                        categoryLinks.forEach(link => {
                            link.classList.remove('active');
                            if (link.dataset.category === 'all') {
                                link.classList.add('active');
                            }
                        });
                        
                        // Reset filters
                        currentFilters = {
                            category: 'all'
                        };
                        
                        // Reset to first page
                        currentPage = 1;
                        
                        // Apply reset filters
                        applyFilters();
                    });
                }
                
                // Sort by selection
                const sortBySelect = document.getElementById('sortBy');
                if (sortBySelect) {
                    sortBySelect.addEventListener('change', function() {
                        currentSort = this.value;
                        applyFilters();
                    });
                }
                
                // View options
                const viewButtons = document.querySelectorAll('.view-btn');
                const productGrid = document.querySelector('.product-grid');
                
                viewButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove active class from all view buttons
                        viewButtons.forEach(btn => btn.classList.remove('active'));
                        
                        // Add active class to clicked button
                        this.classList.add('active');
                        
                        // Change view mode
                        if (this.dataset.view === 'grid') {
                            productGrid.classList.remove('list-view');
                            productGrid.classList.add('grid-view');
                        } else {
                            productGrid.classList.remove('grid-view');
                            productGrid.classList.add('list-view');
                        }
                    });
                });
                
                // Modal functionality
                const modal = document.getElementById('productModal');
                const closeModal = document.getElementById('closeModal');

                // Close modal when clicking X
                closeModal.addEventListener('click', function() {
                    modal.classList.remove('active');
                });

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                    }
                });

                // Quantity selector
                const decreaseQty = document.getElementById('decreaseQty');
                const increaseQty = document.getElementById('increaseQty');
                const quantityInput = document.getElementById('productQuantity');

                decreaseQty.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                });

                increaseQty.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    quantityInput.value = value + 1;
                });

                // Add to cart from modal
                const addToCartModal = document.getElementById('addToCartModal');
                addToCartModal.addEventListener('click', function() {
                    if (!currentModalProduct) return;
                    
                    if (!auth.currentUser) {
                        showAuthAlert();
                        return;
                    }
                    
                    const quantity = parseInt(quantityInput.value);
                    addToCart(currentModalProduct, quantity, selectedSize, selectedColor);
                    modal.classList.remove('active');
                });

                // Add to wishlist from modal
                const addToWishlistModal = document.getElementById('addToWishlistModal');
                addToWishlistModal.addEventListener('click', function() {
                    if (!currentModalProduct) return;
                    
                    if (!auth.currentUser) {
                        showAuthAlert();
                        return;
                    }
                    
                    toggleWishlist(currentModalProduct.id);
                });
                
                // Hide loading animation once everything is ready
                setTimeout(() => {
                    hideLoading();
                }, 500);
            });
        });

        // Apply all current filters
        function applyFilters() {
            filteredProducts = [...products];
            
            // Filter by category
            if (currentFilters.category !== 'all') {
                filteredProducts = filteredProducts.filter(
                    product => product.category === currentFilters.category
                );
            }
            
            // Sort products
            switch(currentSort) {
                case 'newest':
                    filteredProducts.sort((a, b) => b.newArrival - a.newArrival);
                    break;
                case 'price-low':
                    filteredProducts.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => b.price - a.price);
                    break;
                case 'rating':
                    filteredProducts.sort((a, b) => b.rating - a.rating);
                    break;
                case 'featured':
                default:
                    filteredProducts.sort((a, b) => b.featured - a.featured);
                    break;
            }
            
            // Calculate pagination
            totalPages = Math.ceil(filteredProducts.length / productsPerPage);
            
            // Ensure current page is within bounds
            if (currentPage > totalPages) {
                currentPage = totalPages > 0 ? totalPages : 1;
            }
            
            // Get products for current page
            const startIndex = (currentPage - 1) * productsPerPage;
            const endIndex = startIndex + productsPerPage;
            const productsToDisplay = filteredProducts.slice(startIndex, endIndex);
            
            // Render products
            renderProducts(productsToDisplay);
            
            // Update pagination controls
            updatePagination();
        }

        // Update pagination controls
        function updatePagination() {
            const paginationContainer = document.querySelector('.pagination');
            paginationContainer.innerHTML = '';
            
            // Previous button
            const prevButton = document.createElement('a');
            prevButton.href = '#';
            prevButton.className = 'page-nav';
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            if (currentPage === 1) {
                prevButton.classList.add('disabled');
            } else {
                prevButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage--;
                    applyFilters();
                });
            }
            paginationContainer.appendChild(prevButton);
            
            // Page numbers
            const maxVisiblePages = 5; // Maximum number of visible page numbers
            let startPage, endPage;
            
            if (totalPages <= maxVisiblePages) {
                // Show all pages
                startPage = 1;
                endPage = totalPages;
            } else {
                // Calculate start and end pages
                const maxPagesBeforeCurrent = Math.floor(maxVisiblePages / 2);
                const maxPagesAfterCurrent = Math.ceil(maxVisiblePages / 2) - 1;
                
                if (currentPage <= maxPagesBeforeCurrent) {
                    // Near the beginning
                    startPage = 1;
                    endPage = maxVisiblePages;
                } else if (currentPage + maxPagesAfterCurrent >= totalPages) {
                    // Near the end
                    startPage = totalPages - maxVisiblePages + 1;
                    endPage = totalPages;
                } else {
                    // Somewhere in the middle
                    startPage = currentPage - maxPagesBeforeCurrent;
                    endPage = currentPage + maxPagesAfterCurrent;
                }
            }
            
            // First page with ellipsis if needed
            if (startPage > 1) {
                const firstPageButton = document.createElement('a');
                firstPageButton.href = '#';
                firstPageButton.className = 'page-num';
                firstPageButton.textContent = '1';
                firstPageButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = 1;
                    applyFilters();
                });
                paginationContainer.appendChild(firstPageButton);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'page-dots';
                    ellipsis.textContent = '...';
                    paginationContainer.appendChild(ellipsis);
                }
            }
            
            // Page number buttons
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('a');
                pageButton.href = '#';
                pageButton.className = 'page-num';
                if (i === currentPage) {
                    pageButton.classList.add('active');
                }
                pageButton.textContent = i;
                pageButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = i;
                    applyFilters();
                });
                paginationContainer.appendChild(pageButton);
            }
            
            // Last page with ellipsis if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'page-dots';
                    ellipsis.textContent = '...';
                    paginationContainer.appendChild(ellipsis);
                }
                
                const lastPageButton = document.createElement('a');
                lastPageButton.href = '#';
                lastPageButton.className = 'page-num';
                lastPageButton.textContent = totalPages;
                lastPageButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = totalPages;
                    applyFilters();
                });
                paginationContainer.appendChild(lastPageButton);
            }
            
            // Next button
            const nextButton = document.createElement('a');
            nextButton.href = '#';
            nextButton.className = 'page-nav';
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            if (currentPage === totalPages || totalPages === 0) {
                nextButton.classList.add('disabled');
            } else {
                nextButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage++;
                    applyFilters();
                });
            }
            paginationContainer.appendChild(nextButton);
        }

        // Render products based on current filters and sort
        function renderProducts(productsToDisplay) {
            const productGrid = document.getElementById('productGrid');
            productGrid.innerHTML = '';
            
            if (productsToDisplay.length === 0) {
                productGrid.innerHTML = '<p class="no-products">No products found matching your criteria.</p>';
                return;
            }
            
            // Render each product
            productsToDisplay.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.dataset.id = product.id;
                productCard.dataset.category = product.category;
                
                // Check if product is in wishlist
                const isInWishlist = auth.currentUser ? 
                    auth.currentUser.wishlist && auth.currentUser.wishlist.some(item => item.id === product.id) : false;
                
                // Generate stars HTML
                const stars = generateStars(product.rating);
                
                // Generate badge if exists
                const badge = product.badge ? `<span class="product-badge ${product.badge}">${product.badge === 'sale' ? 'Sale' : product.badge === 'new' ? 'New' : 'Best Seller'}</span>` : '';
                
                // Generate old price if exists
                const oldPrice = product.oldPrice ? `<span class="old-price">$${product.oldPrice.toFixed(2)}</span>` : '';
                
                // Generate discount if exists
                let discount = '';
                if (product.oldPrice) {
                    const discountPercent = Math.round((1 - product.price / product.oldPrice) * 100);
                    discount = `<span class="discount">Save ${discountPercent}%</span>`;
                }
                
                productCard.innerHTML = `
                    <div class="product-image">
                        ${badge}
                        <img src="${product.image}" alt="${product.title}">
                        <div class="product-actions">
                            <button class="action-btn quick-view"><i class="far fa-eye"></i></button>
                            <button class="action-btn add-to-wishlist"><i class="${isInWishlist ? 'fas' : 'far'} fa-heart"></i></button>
                            <button class="action-btn add-to-cart"><i class="fas fa-shopping-bag"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">${product.title}</h3>
                        <div class="product-price">
                            <span class="current-price">$${product.price.toFixed(2)}</span>
                            ${oldPrice}
                            ${discount}
                        </div>
                        <div class="product-meta">
                            <div class="rating">
                                <div class="stars">
                                    ${stars}
                                </div>
                                <span class="review-count">(${product.reviews})</span>
                            </div>
                        </div>
                    </div>
                `;
                
                productGrid.appendChild(productCard);
                
                // Add event listeners to the newly created product card
                const quickViewBtn = productCard.querySelector('.quick-view');
                const wishlistBtn = productCard.querySelector('.add-to-wishlist');
                const cartBtn = productCard.querySelector('.add-to-cart');
                
                // Click on product card opens modal
                productCard.addEventListener('click', function(e) {
                    // Don't open modal if clicking on action buttons
                    if (e.target.closest('.product-actions')) return;
                    
                    showProductModal(product);
                });
                
                quickViewBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showProductModal(product);
                });
                
                wishlistBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleWishlist(product.id, productCard);
                });
                
                cartBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    addToCart(product);
                });
            });
        }

        // Show product modal with details
        function showProductModal(product) {
            const modal = document.getElementById('productModal');
            currentModalProduct = product;
            selectedSize = null;
            selectedColor = null;
            
            // Check if product is in wishlist
            const isInWishlist = auth.currentUser ? 
                auth.currentUser.wishlist && auth.currentUser.wishlist.some(item => item.id === product.id) : false;
            
            // Set basic product info
            document.getElementById('modalProductTitle').textContent = product.title;
            document.getElementById('modalProductImage').src = product.image;
            document.getElementById('modalProductPrice').textContent = `$${product.price.toFixed(2)}`;
            document.getElementById('modalProductDescription').textContent = product.description;
            document.getElementById('modalProductSKU').textContent = product.sku;
            document.getElementById('modalProductCategory').textContent = product.category;
            
            // Set rating
            document.getElementById('modalProductRating').innerHTML = generateStars(product.rating);
            document.getElementById('modalProductReviews').textContent = `(${product.reviews})`;
            
            // Set price details
            const oldPriceEl = document.getElementById('modalProductOldPrice');
            const discountEl = document.getElementById('modalProductDiscount');
            
            if (product.oldPrice) {
                oldPriceEl.textContent = `$${product.oldPrice.toFixed(2)}`;
                const discountPercent = Math.round((1 - product.price / product.oldPrice) * 100);
                discountEl.textContent = `Save ${discountPercent}%`;
                oldPriceEl.style.display = 'inline';
                discountEl.style.display = 'inline';
            } else {
                oldPriceEl.style.display = 'none';
                discountEl.style.display = 'none';
            }
            
            // Set features
            const featuresList = document.getElementById('modalProductFeatures');
            featuresList.innerHTML = '';
            product.features.forEach(feature => {
                const li = document.createElement('li');
                li.textContent = feature;
                featuresList.appendChild(li);
            });
            
            // Set size options
            const sizeOptions = document.getElementById('modalSizeOptions');
            sizeOptions.innerHTML = '';
            product.sizes.forEach(size => {
                const btn = document.createElement('button');
                btn.className = 'size-btn';
                btn.textContent = size;
                btn.dataset.size = size;
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    selectedSize = size;
                });
                sizeOptions.appendChild(btn);
            });
            
            // Set color options
            const colorOptions = document.getElementById('modalColorOptions');
            colorOptions.innerHTML = '';
            product.colors.forEach(color => {
                const btn = document.createElement('button');
                btn.className = 'color-btn';
                btn.dataset.color = color;
                btn.style.backgroundColor = product.colorCodes[color];
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    selectedColor = color;
                });
                colorOptions.appendChild(btn);
            });
            
            // Update wishlist button in modal
            const wishlistBtn = document.getElementById('addToWishlistModal');
            wishlistBtn.innerHTML = isInWishlist ? 
                '<i class="fas fa-heart"></i> Remove from Wishlist' : 
                '<i class="far fa-heart"></i> Add to Wishlist';
            
            // Reset quantity
            document.getElementById('productQuantity').value = 1;
            
            // Update view details link to pass product image
            const viewDetailsLink = document.getElementById('viewDetailsLink');
            if (viewDetailsLink) {
                // Encode the image URL to safely pass it as a parameter
                const encodedImage = encodeURIComponent(product.image);
                viewDetailsLink.href = `product-details.php?id=${product.id}&image=${encodedImage}`;
            }

            // Show modal
            modal.classList.add('active');
        }

        // Generate stars HTML based on rating
        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            
            // Full stars
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            
            // Half star
            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }
            
            // Empty stars
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star"></i>';
            }
            
            return stars;
        }

        // Wishlist functions for shop page
        async function toggleWishlist(productId, productElement = null) {
            try {
                // Check if user is logged in
                if (!auth.currentUser) {
                    showAuthAlert();
                    return false;
                }
                
                const response = await fetch(`${API_BASE}/toggle_wishlist.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        product_id: productId
                    })
                });
                
                // Check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON');
                }
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update wishlist in memory
                    if (data.action === 'added') {
                        const product = products.find(p => p.id === productId);
                        if (product) {
                            auth.currentUser.wishlist.push({
                                id: product.id,
                                title: product.title,
                                price: product.price,
                                image: product.image
                            });
                        }
                        showWishlistNotification('Added to wishlist!');
                    } else {
                        auth.currentUser.wishlist = auth.currentUser.wishlist.filter(item => item.id !== productId);
                        showWishlistNotification('Removed from wishlist!');
                    }
                    
                    // Update localStorage
                    localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));

                    // Update heart icon
                    if (productElement) {
                        const heartIcon = productElement.querySelector('.add-to-wishlist i');
                        if (heartIcon) {
                            heartIcon.className = data.action === 'added' ? 'fas fa-heart' : 'far fa-heart';
                        }
                    }

                    // Update wishlist button in modal
                    const modalWishlistBtn = document.getElementById('addToWishlistModal');
                    if (modalWishlistBtn) {
                        modalWishlistBtn.innerHTML = data.action === 'added' ?
                            '<i class="fas fa-heart"></i> Remove from Wishlist' :
                            '<i class="far fa-heart"></i> Add to Wishlist';
                    }
                    
                    // Update wishlist count
                    updateWishlistCount();
                    return true;
                } else {
                    console.error('Error toggling wishlist:', data.message);
                    return false;
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                // Fallback to local storage if API call fails
                return toggleWishlistLocal(productId, productElement);
            }
        }

        // Add product to cart (now saves to MySQL database)
        async function addToCart(product, quantity = 1, size = null, color = null) {
            if (!auth.currentUser) {
                showAuthAlert();
                return;
            }
            
            // Apply default values if not provided
            if (!size && product.sizes && product.sizes.length > 0) {
                size = product.sizes[0]; // Use first available size as default
            }
            
            if (!color && product.colors && product.colors.length > 0) {
                color = product.colors[0]; // Use first available color as default
            }
            
            // Ensure quantity is at least 1
            if (quantity < 1) {
                quantity = 1;
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
                
                // Check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON');
                }
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Reload cart items from database to get the updated count
                    await loadCartItems();
                    
                    // Show notification with applied defaults
                    const appliedDefaults = data.applied_defaults || {};
                    showCartNotification(product, appliedDefaults);
                } else {
                    console.error('Error adding to cart:', data.message);
                    alert('Error adding product to cart: ' + data.message);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                // Fallback to localStorage if API call fails
                addToCartLocal(product, quantity, size, color);
            }
        }

        // Fallback function for adding to cart in localStorage
        function addToCartLocal(product, quantity = 1, size = null, color = null) {
            // Check if product is already in cart with same size and color
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
            
            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            updateCartCount();
            
            // Show notification
            showCartNotification(product);
        }

        // Show authentication required alert
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
            
            // Click anywhere to close
            alert.addEventListener('click', function() {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }

        // Update cart count in header
        function updateCartCount() {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
                // Add animation to highlight the change
                cartCountElement.classList.add('pulse');
                setTimeout(() => {
                    cartCountElement.classList.remove('pulse');
                }, 500);
            }
        }

        // Update wishlist count in header
        function updateWishlistCount() {
            let wishlistCount = 0;
            
            if (auth.currentUser && auth.currentUser.wishlist) {
                wishlistCount = auth.currentUser.wishlist.length;
            }
            
            const wishlistCountElement = document.querySelector('.wishlist-count');
            if (wishlistCountElement) {
                wishlistCountElement.textContent = wishlistCount;
                // Add animation to highlight the change
                wishlistCountElement.classList.add('pulse');
                setTimeout(() => {
                    wishlistCountElement.classList.remove('pulse');
                }, 500);
            }
        }

        // Show cart notification
        function showCartNotification(product, appliedDefaults = {}) {
            const notification = document.createElement('div');
            notification.className = 'cart-notification';
            
            let defaultsText = '';
            if (appliedDefaults.size || appliedDefaults.color) {
                const parts = [];
                if (appliedDefaults.size) parts.push(`Size: ${appliedDefaults.size}`);
                if (appliedDefaults.color) parts.push(`Color: ${appliedDefaults.color}`);
                defaultsText = `<br><small>Default options applied: ${parts.join(', ')}</small>`;
            }
            
            notification.innerHTML = `
                <p>${product.title} added to cart!${defaultsText}</p>
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

        // Show wishlist notification
        function showWishlistNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'cart-notification';
            notification.innerHTML = `<p>${message}</p>`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 2000);
        }

        // Listen for storage events to update counts when data changes in other tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                updateCartCount();
            }
            if (e.key === 'currentUser') {
                auth.currentUser = JSON.parse(e.newValue);
                updateWishlistCount();
            }
        });
    </script>
</body>
</html>
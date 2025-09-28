<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Premium Clothing</title>
    <link rel="icon" href="/images/favicon.png" type="image/png">
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



        /* Hero Section */
        .hero { height: 100vh; position: relative; overflow: hidden; color: white; display: flex; align-items: center; margin-bottom: 120px; }
        .hero-slideshow { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .hero-slide { position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1.5s ease-in-out; background-size: cover; background-position: center; }
        .hero-slide.active { opacity: 1; }
        .hero-slide:nth-child(1) { background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); }
        .hero-slide:nth-child(2) { background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%), url('https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); }
        .hero-slide:nth-child(3) { background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%), url('https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1935&q=80'); }
        .hero-content { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2; animation: fadeInUp 1s ease-out; }
        .hero h1 { font-size: 5rem; margin-bottom: 20px; text-shadow: 2px 2px 10px rgba(0,0,0,0.3); line-height: 1.1; color: var(--white); }
        .hero p { font-size: 1.5rem; margin-bottom: 30px; max-width: 600px; color: rgba(255,255,255,0.9); }
        .hero-buttons { display: flex; gap: 20px; margin-top: 40px; }
        .hero-buttons .btn { padding: 12px 24px; font-size: 1rem; border-radius: 30px; }
        .hero-buttons .btn-primary { background-color: var(--secondary-color); border: 2px solid var(--secondary-color); }
        .hero-buttons .btn-outline { background-color: transparent; color: var(--white); border: 2px solid var(--white); }
        .hero-controls { position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); display: flex; gap: 12px; z-index: 3; }
        .hero-control { width: 14px; height: 14px; border-radius: 50%; background-color: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s; }
        .hero-control.active { background-color: var(--white); transform: scale(1.3); }

/* Featured Categories - Card Hover Effects */
.featured-categories {
    padding: 80px 0;
    background: white;
    position: relative;
    z-index: 2;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 60px;
    position: relative;
    color: #333;
}

.section-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, #ff4e50, #f9d423);
    margin: 15px auto 0;
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.category-card {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 300px;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.category-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-card:hover img {
    transform: scale(1.05);
}

.category-card h3 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 20px;
    margin: 0;
    color: white;
    font-size: 1.8rem;
    font-weight: 600;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    transform: translateY(0);
    transition: all 0.3s ease;
}

.category-card:hover h3 {
    transform: translateY(-10px);
}

/* Featured Products Section */
.featured-products {
    padding: 80px 0;
    background-color: var(--bg-light);
}

.featured-products .product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.product-card {
    background-color: var(--white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    transition: var(--transition);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
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
    z-index: 2;
}

.product-badge.new {
    background-color: var(--success-color);
}

.product-badge.best-seller {
    background-color: var(--secondary-color);
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 250px;
    flex-shrink: 0;
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
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1rem;
    margin-bottom: 10px;
    color: var(--primary-color);
    line-height: 1.4;
    min-height: 42px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
    flex-wrap: wrap;
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
    margin-left: auto;
}

.product-meta {
    margin-top: auto;
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
    z-index: 2;
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.action-btn:hover {
    background-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

    /* New Promo Banner Section */
        .promo-banner-new { padding: 100px 0; margin: 80px 0; background: url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1920&q=80') no-repeat center center; background-size: cover; background-attachment: fixed; position: relative; text-align: center; color: var(--white); }
        .promo-banner-new::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); z-index: 1; }
        .promo-content-new { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; padding: 0 20px; }
        .promo-content-new .subtitle { font-size: 1.2rem; color: var(--secondary-color); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .promo-content-new h2 { font-size: 3.5rem; margin-bottom: 20px; color: var(--white); }
        #countdown-timer { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; }
        .timer-box { background: rgba(255, 255, 255, 0.1); padding: 15px 20px; border-radius: 8px; min-width: 80px; backdrop-filter: blur(5px); }
        .timer-box span { display: block; font-size: 2.5rem; font-weight: 700; }
        .timer-box p { margin: 0; font-size: 0.9rem; text-transform: uppercase; }
        .promo-content-new .btn { background-color: var(--secondary-color); padding: 15px 35px; font-size: 1.1rem; border-radius: 50px; text-transform: uppercase; font-weight: 700; }

/* Testimonials - Enhanced Design */
.testimonials {
    padding: 100px 0;
    background: white;
    text-align: center;
}

.testimonial-slider {
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 40px;
}

.testimonial-slide {
    scroll-snap-align: start;
    flex-shrink: 0;
    width: 100%;
    background: white;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    margin: 0 20px;
    position: relative;
    text-align: center;
}

.testimonial-slide::before {
    content: '"';
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 5rem;
    color: rgba(255,78,80,0.1);
    font-family: serif;
    line-height: 1;
}

.testimonial-content {
    font-size: 1.2rem;
    line-height: 1.8;
    margin-bottom: 25px;
    color: #555;
    position: relative;
    z-index: 2;
    font-style: italic;
}

.testimonial-author {
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
    font-size: 1.1rem;
}

.testimonial-role {
    color: #999;
    font-size: 0.9rem;
}

.testimonial-rating {
    color: #f9d423;
    margin-bottom: 15px;
    font-size: 1.2rem;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 2000;
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

/* Cart Notification */
.cart-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--secondary-color);
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
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
    color: white;
    text-decoration: underline;
    font-weight: 600;
}

/* Wishlist Notification */
.wishlist-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #4CAF50;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 15px;
}

.wishlist-notification.show {
    transform: translateY(0);
    opacity: 1;
}

/* Compare Notification */
.compare-notification {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background: #4CAF50;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 15px;
}

.compare-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.compare-notification a {
    color: white;
    text-decoration: underline;
    font-weight: 600;
}

/* Auth Alert */
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


/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes pulse {
    0% {
        transform: rotate(15deg) scale(1);
    }
    50% {
        transform: rotate(15deg) scale(1.05);
    }
    100% {
        transform: rotate(15deg) scale(1);
    }
}


/* Responsive Adjustments */
@media (max-width: 1024px) {
    .hero h1 {
        font-size: 4.5rem;
    }

    .hero p {
        font-size: 1.4rem;
    }

    .hero-content {
        padding: 0 15px;
    }

    .featured-products .product-grid,
    .category-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 772px) {
    .hero h1 {
        font-size: 3rem;
    }

    .hero p {
        font-size: 1.2rem;
    }

 .promo-content-new h2 { font-size: 2.2rem; }

    .section-title {
        font-size: 2rem;
    }

    .testimonial-slide {
        padding: 30px;
    }

    .featured-products .product-grid,
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .modal-content {
        flex-direction: column;
    }

    .modal-image {
        min-height: 200px;
    }

    .modal-actions {
        flex-direction: column;
    }

    .season-tag {
        top: 80px;
        right: 30px;
        font-size: 0.9rem;
    }

    .hero-buttons .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

}

@media (max-width: 600px) {
    .search-box {
        display: none; /* Hide search box below 600px */
    }
}

@media (max-width: 500px) {
    .featured-products .product-grid,
    .category-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero h1 {
        font-size: 2.5rem;
    }

    .hero-buttons {
        flex-direction: column;
        gap: 15px;
    }

    .season-tag {
        top: 60px;
        right: 20px;
        padding: 8px 15px;
    }

    .fashion-icons {
        display: none;
    }
}

    </style>
</head>
<body>
    <?php include 'header.html'; ?>

<main>
    <section class="hero">
        <div class="hero-slideshow">
            <div class="hero-slide active"></div>
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
        </div>
        <div class="hero-content">
            <h1>Elevate Your Style With Premium Fashion</h1>
            <p>Discover our curated selection of high-quality clothing and accessories.</p>
            <div class="hero-buttons">
                <a href="shop.php" class="btn btn-primary">Shop Now</a>
                <a href="shop.php?category=new-arrivals" class="btn btn-outline">New Arrivals</a>
            </div>
        </div>
        <div class="hero-controls">
            <div class="hero-control active" data-slide="0"></div>
            <div class="hero-control" data-slide="1"></div>
            <div class="hero-control" data-slide="2"></div>
        </div>
    </section>

  <section class="featured-categories">
        <div class="container">
            <h2 class="section-title">Shop by Category</h2>
            <div class="category-grid">
                <a href="shop.php?category=shirts" class="category-card">
                    <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1025&q=80" alt="Shirts">
                    <h3>Shirts</h3>
                </a>
                <a href="shop.php?category=jeans" class="category-card">
                    <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80" alt="Jeans">
                    <h3>Jeans</h3>
                </a>
                <a href="shop.php?category=jackets" class="category-card">
                    <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1035&q=80" alt="Jackets">
                    <h3>Jackets</h3>
                </a>
                <a href="shop.php?category=dresses" class="category-card">
                    <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80" alt="Dresses">
                    <h3>Dresses</h3>
                </a>
                <a href="shop.php?category=accessories" class="category-card">
                    <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1038&q=80" alt="Accessories">
                    <h3>Accessories</h3>
                </a>
                <a href="shop.php?category=activewear" class="category-card">
                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80" alt="Activewear">
                    <h3>Activewear</h3>
                </a>
                <a href="shop.php?category=footwear" class="category-card">
                    <img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1001&q=80" alt="Footwear">
                    <h3>Footwear</h3>
                </a>
                <a href="shop.php?category=formal" class="category-card">
                    <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80" alt="Formal Wear">
                    <h3>Formal Wear</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">Featured Products</h2>
            <div class="product-grid">
                <!-- Product 1 -->
                <div class="product-card" data-id="1">
                    <div class="product-image">
                        <span class="product-badge sale">Sale</span>
                        <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Premium Cotton Shirt">
                        <div class="product-actions">
                            <button class="action-btn quick-view"><i class="far fa-eye"></i></button>
                            <button class="action-btn add-to-wishlist" data-id="1"><i class="far fa-heart"></i></button>
                            <button class="action-btn add-to-cart" data-id="1"><i class="fas fa-shopping-bag"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Premium Cotton Shirt</h3>
                        <div class="product-price">
                            <span class="current-price">$59.99</span>
                            <span class="old-price">$74.99</span>
                            <span class="discount">Save 20%</span>
                        </div>
                        <div class="product-meta">
                            <div class="rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="review-count">(24)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="product-card" data-id="2">
                    <div class="product-image">
                        <span class="product-badge new">New</span>
                        <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Slim Fit Jeans">
                        <div class="product-actions">
                            <button class="action-btn quick-view"><i class="far fa-eye"></i></button>
                            <button class="action-btn add-to-wishlist"><i class="far fa-heart"></i></button>
                            <button class="action-btn add-to-cart"><i class="fas fa-shopping-bag"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Slim Fit Jeans</h3>
                        <div class="product-price">
                            <span class="current-price">$79.99</span>
                            <span class="old-price">$89.99</span>
                            <span class="discount">Save 11%</span>
                        </div>
                        <div class="product-meta">
                            <div class="rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="review-count">(18)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="product-card" data-id="3">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Classic Denim Jacket">
                        <div class="product-actions">
                            <button class="action-btn quick-view"><i class="far fa-eye"></i></button>
                            <button class="action-btn add-to-wishlist"><i class="far fa-heart"></i></button>
                            <button class="action-btn add-to-cart"><i class="fas fa-shopping-bag"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Classic Denim Jacket</h3>
                        <div class="product-price">
                            <span class="current-price">$99.99</span>
                        </div>
                        <div class="product-meta">
                            <div class="rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="review-count">(32)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="product-card" data-id="4">
                    <div class="product-image">
                        <span class="product-badge sale">Sale</span>
                        <img src="https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Casual Summer Dress">
                        <div class="product-actions">
                            <button class="action-btn quick-view"><i class="far fa-eye"></i></button>
                            <button class="action-btn add-to-wishlist"><i class="far fa-heart"></i></button>
                            <button class="action-btn add-to-cart"><i class="fas fa-shopping-bag"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Casual Summer Dress</h3>
                        <div class="product-price">
                            <span class="current-price">$69.99</span>
                            <span class="old-price">$79.99</span>
                            <span class="discount">Save 13%</span>
                        </div>
                        <div class="product-meta">
                            <div class="rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="review-count">(21)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Banner - Enhanced Design -->
    <section class="promo-banner-new">
        <div class="promo-content-new">
            <p class="subtitle">Limited Time Offer</p>
            <h2>End of Season Sale</h2>
            <p>Get up to <strong>70% OFF</strong> on selected items. The clock is ticking!</p>
            <div id="countdown-timer">
                <div class="timer-box"><span id="days">00</span><p>Days</p></div>
                <div class="timer-box"><span id="hours">00</span><p>Hours</p></div>
                <div class="timer-box"><span id="minutes">00</span><p>Mins</p></div>
                <div class="timer-box"><span id="seconds">00</span><p>Secs</p></div>
            </div>
            <a href="shop.php?category=sale" class="btn">Shop the Sale</a>
        </div>
    </section>

    <!-- Testimonials - Enhanced Design -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Customers Say</h2>
            <div class="testimonial-slider">
                <div class="testimonial-slide">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"I absolutely love the quality of the clothes from FashionHub. The fit is perfect and the materials are so comfortable. I'll definitely be shopping here again and again!"</p>
                    </div>
                    <div class="testimonial-author">Sarah Johnson</div>
                    <div class="testimonial-role">Happy Customer</div>
                </div>
                <div class="testimonial-slide">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"The shipping was incredibly fast and the customer service was excellent when I had a question about sizing. Highly recommend FashionHub for all your fashion needs!"</p>
                    </div>
                    <div class="testimonial-author">Michael Smith</div>
                    <div class="testimonial-role">Happy Customer</div>
                </div>
                <div class="testimonial-slide">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"I've purchased multiple items from FashionHub and each one has exceeded my expectations. The quality is outstanding and the prices are very reasonable for what you get."</p>
                    </div>
                    <div class="testimonial-author">Emily Wilson</div>
                    <div class="testimonial-role">Loyal Customer</div>
                </div>
            </div>
        </div>
    </section>
</main>



 <?php include 'footer.html'; ?>
   
<script>
    // Shopping Cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartCount = cart.reduce((total, item) => total + item.quantity, 0);

    // Wishlist
    let wishlist = [];
    let wishlistCount = 0;

    // Track login status
    const auth = {
        currentUser: JSON.parse(localStorage.getItem('currentUser')) || null
    };

    // Initialize wishlist count if user is logged in
    if (auth.currentUser) {
        wishlist = auth.currentUser.wishlist || [];
        wishlistCount = wishlist.length;
    }

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
        }
    ];

    // Current modal product and selections
    let currentModalProduct = null;
    let selectedSize = null;
    let selectedColor = null;

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Update counters
        updateCartCount();
        updateWishlistCount();
        updateWishlistIcons();

        // Update account link based on login status
        updateAccountLink();


        // Create modal element
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.id = 'productModal';
        modal.innerHTML = `
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
                        <p class="modal-description" id="modalProductDescription"></p>
                        <div class="modal-features">
                            <h4>Features:</h4>
                            <ul id="modalProductFeatures"></ul>
                        </div>
                        <div class="size-selection">
                            <h4>Size:</h4>
                            <div class="size-options" id="modalSizeOptions"></div>
                        </div>
                        <div class="color-selection">
                            <h4>Color:</h4>
                            <div class="color-options" id="modalColorOptions"></div>
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
                    <span class="modal-category">Category: <span id="modalProductCategory">Shirts</span></span>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Close modal when clicking X
        document.getElementById('closeModal').addEventListener('click', function() {
            modal.classList.remove('active');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });

        // Quantity selector
        document.getElementById('decreaseQty').addEventListener('click', function() {
            let value = parseInt(document.getElementById('productQuantity').value);
            if (value > 1) {
                document.getElementById('productQuantity').value = value - 1;
            }
        });

        document.getElementById('increaseQty').addEventListener('click', function() {
            let value = parseInt(document.getElementById('productQuantity').value);
            document.getElementById('productQuantity').value = value + 1;
        });

        // Add event listeners to all product cards
        document.querySelectorAll('.product-card').forEach(card => {
            // Click on product card opens modal
            card.addEventListener('click', function(e) {
                // Don't open modal if clicking on action buttons
                if (e.target.closest('.product-actions')) return;
                
                const productId = parseInt(card.dataset.id);
                const product = products.find(p => p.id === productId);
                if (product) showProductModal(product);
            });

            // Quick view button
            card.querySelector('.quick-view').addEventListener('click', function(e) {
                e.stopPropagation();
                const productId = parseInt(card.dataset.id);
                const product = products.find(p => p.id === productId);
                if (product) showProductModal(product);
            });

            // Wishlist button
            card.querySelector('.add-to-wishlist').addEventListener('click', function(e) {
                e.stopPropagation();
                const productId = parseInt(card.dataset.id);
                const product = products.find(p => p.id === productId);
                if (product) toggleWishlist(card, product);
            });

            // Add to cart button
            card.querySelector('.add-to-cart').addEventListener('click', function(e) {
                e.stopPropagation();
                const productId = parseInt(card.dataset.id);
                const product = products.find(p => p.id === productId);
                if (product) addToCart(product);
            });
        });

        // Add to cart from modal
        document.getElementById('addToCartModal').addEventListener('click', function() {
            if (!currentModalProduct) return;
            
            const quantity = parseInt(document.getElementById('productQuantity').value);
            addToCart(currentModalProduct, quantity, selectedSize, selectedColor);
            modal.classList.remove('active');
        });

        // Add to wishlist from modal
        document.getElementById('addToWishlistModal').addEventListener('click', function() {
            if (!currentModalProduct) return;
            
            const productCard = document.querySelector(`.product-card[data-id="${currentModalProduct.id}"]`);
            toggleWishlist(productCard, currentModalProduct);
            
            // Update modal button
            const isInWishlist = auth.currentUser && auth.currentUser.wishlist ?
                auth.currentUser.wishlist.some(item => item.id === currentModalProduct.id) : false;
            document.getElementById('addToWishlistModal').innerHTML = isInWishlist ?
                '<i class="fas fa-heart"></i> Remove from Wishlist' :
                '<i class="far fa-heart"></i> Add to Wishlist';
        });

        // Hero Slider Functionality
        const slides = document.querySelectorAll('.hero-slide');
        const controls = document.querySelectorAll('.hero-control');
        let currentSlide = 0;
        
        // Auto slide change
        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            controls[currentSlide].classList.remove('active');
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].classList.add('active');
            controls[currentSlide].classList.add('active');
        }
        
        // Manual slide control
        controls.forEach(control => {
            control.addEventListener('click', function() {
                const slideIndex = parseInt(this.dataset.slide);
                
                slides[currentSlide].classList.remove('active');
                controls[currentSlide].classList.remove('active');
                
                currentSlide = slideIndex;
                
                slides[currentSlide].classList.add('active');
                controls[currentSlide].classList.add('active');
                
                // Reset timer when manually changing slides
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        
        // Start auto slide
        let slideInterval = setInterval(nextSlide, 5000);
        
        // Pause on hover
        const hero = document.querySelector('.hero');
        hero.addEventListener('mouseenter', function() {
            clearInterval(slideInterval);
        });
        
        hero.addEventListener('mouseleave', function() {
            slideInterval = setInterval(nextSlide, 5000);
        });

            // Countdown Timer
    function startCountdown() {
        const countdownDate = new Date(new Date().getTime() + 10 * 24 * 60 * 60 * 1000); // 10 days from now
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById('countdown-timer').innerHTML = "<h4>Sale Ended</h4>";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = String(days).padStart(2, '0');
            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
        }, 1000);
    }
    startCountdown();

    
        // Listen for storage events to update counts when cart/wishlist changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                updateCartCount();
            }
            if (e.key === 'currentUser') {
                auth.currentUser = JSON.parse(e.newValue);
                if (auth.currentUser) {
                    wishlist = auth.currentUser.wishlist || [];
                    wishlistCount = wishlist.length;
                } else {
                    wishlist = [];
                    wishlistCount = 0;
                }
                updateWishlistCount();
                updateAccountLink();
            }
        });
    });

    // Update account link based on login status
    function updateAccountLink() {
        const accountLink = document.getElementById('accountLink');
        if (accountLink) {
            accountLink.innerHTML = auth.currentUser ? 
                '<i class="fas fa-user"></i>' : 
                '<i class="far fa-user"></i>';
        }
    }

    // Show product modal with details
    function showProductModal(product) {
        const modal = document.getElementById('productModal');
        currentModalProduct = product;
        selectedSize = null;
        selectedColor = null;
        
        // Check if product is in wishlist
        const isInWishlist = auth.currentUser && auth.currentUser.wishlist ?
            auth.currentUser.wishlist.some(item => item.id === product.id) : false;
        
        // Set basic product info
        document.getElementById('modalProductTitle').textContent = product.title;
        document.getElementById('modalProductImage').src = product.image;
        document.getElementById('modalProductPrice').textContent = `$${product.price.toFixed(2)}`;
        document.getElementById('modalProductDescription').textContent = product.description;
        document.getElementById('modalProductSKU').textContent = `FH-00${product.id}`;
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
        product.sizes.forEach((size, index) => {
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

            // Auto-select first size
            if (index === 0) {
                btn.classList.add('active');
                selectedSize = size;
            }
        });

        // Set color options
        const colorOptions = document.getElementById('modalColorOptions');
        colorOptions.innerHTML = '';
        product.colors.forEach((color, index) => {
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

            // Auto-select first color
            if (index === 0) {
                btn.classList.add('active');
                selectedColor = color;
            }
        });
        
        // Update wishlist button in modal
        document.getElementById('addToWishlistModal').innerHTML = isInWishlist ? 
            '<i class="fas fa-heart"></i> Remove from Wishlist' : 
            '<i class="far fa-heart"></i> Add to Wishlist';
        
        // Reset quantity
        document.getElementById('productQuantity').value = 1;
        
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

    // Toggle wishlist status - now uses API
    async function toggleWishlist(productCard, product) {
        if (!auth.currentUser) {
            showAuthAlert();
            return;
        }

        // Validate product ID
        if (!product.id || product.id <= 0) {
            console.error('Invalid product ID:', product.id);
            return;
        }

        try {
            const response = await fetch('/fashionhub-old/api/toggle_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify({
                    product_id: product.id
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
                    auth.currentUser.wishlist.push({
                        id: product.id,
                        title: product.title,
                        price: product.price,
                        image: product.image
                    });
                    if (productCard) {
                        productCard.querySelector('.add-to-wishlist').innerHTML = '<i class="fas fa-heart"></i>';
                    }
                    showWishlistNotification('Added to wishlist!');
                } else {
                    auth.currentUser.wishlist = auth.currentUser.wishlist.filter(item => item.id !== product.id);
                    if (productCard) {
                        productCard.querySelector('.add-to-wishlist').innerHTML = '<i class="far fa-heart"></i>';
                    }
                    showWishlistNotification('Removed from wishlist!');
                }

                // Update localStorage
                localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));

                // Update wishlist count
                updateWishlistCount();

                // Update modal button if this product is currently in modal
                if (currentModalProduct && currentModalProduct.id === product.id) {
                    const isInWishlist = auth.currentUser.wishlist.some(item => item.id === product.id);
                    document.getElementById('addToWishlistModal').innerHTML = isInWishlist ?
                        '<i class="fas fa-heart"></i> Remove from Wishlist' :
                        '<i class="far fa-heart"></i> Add to Wishlist';
                }
            } else {
                console.error('Error toggling wishlist:', data.message);
            }
        } catch (error) {
            console.error('Error toggling wishlist:', error);
            // Fallback to local storage if API call fails
            toggleWishlistLocal(productCard, product);
        }
    }

    // Fallback function for wishlist using local storage
    function toggleWishlistLocal(productCard, product) {
        if (!auth.currentUser) {
            showAuthAlert();
            return;
        }

        // Get all users from localStorage
        const users = JSON.parse(localStorage.getItem('users')) || [];
        const userIndex = users.findIndex(u => u.id === auth.currentUser.id);

        if (userIndex === -1) return;

        // Check if product is already in wishlist
        const wishlistIndex = users[userIndex].wishlist.findIndex(item => item.id === product.id);

        if (wishlistIndex >= 0) {
            // Remove from wishlist
            users[userIndex].wishlist.splice(wishlistIndex, 1);
            if (productCard) {
                productCard.querySelector('.add-to-wishlist').innerHTML = '<i class="far fa-heart"></i>';
            }
        } else {
            // Add to wishlist
            users[userIndex].wishlist.push({
                id: product.id,
                title: product.title,
                price: product.price,
                image: product.image,
                category: product.category
            });
            if (productCard) {
                productCard.querySelector('.add-to-wishlist').innerHTML = '<i class="fas fa-heart"></i>';
            }
        }

        // Update localStorage
        localStorage.setItem('users', JSON.stringify(users));

        // Update current user data
        auth.currentUser = users[userIndex];
        localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));

        // Update wishlist count
        wishlist = auth.currentUser.wishlist;
        wishlistCount = wishlist.length;
        updateWishlistCount();

        // Update modal button if this product is currently in modal
        if (currentModalProduct && currentModalProduct.id === product.id) {
            const isInWishlist = auth.currentUser.wishlist.some(item => item.id === product.id);
            document.getElementById('addToWishlistModal').innerHTML = isInWishlist ?
                '<i class="fas fa-heart"></i> Remove from Wishlist' :
                '<i class="far fa-heart"></i> Add to Wishlist';
        }
    }

    // Add product to cart - now uses API
    async function addToCart(product, quantity = 1, size = null, color = null) {
        if (!auth.currentUser) {
            showAuthAlert();
            return;
        }

        try {
            const response = await fetch('/fashionhub-old/api/add_to_cart.php', {
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
                await loadUserCart();

                // Show notification
                showCartNotification(product);
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

    // Load user's cart from database
    async function loadUserCart() {
        if (!auth.currentUser) return;

        try {
            const response = await fetch('/fashionhub-old/api/toggle_wishlist.php', {
                credentials: 'include'
            });

            if (response.ok) {
                const data = await response.json();
                if (data.status === 'success') {
                    // Update localStorage cart with database cart
                    const dbCart = data.cart.map(item => ({
                        id: item.product_id,
                        title: item.title,
                        price: parseFloat(item.price),
                        image: item.image,
                        size: item.size,
                        color: item.color,
                        quantity: item.quantity
                    }));

                    localStorage.setItem('cart', JSON.stringify(dbCart));

                    // Update cart count
                    cartCount = dbCart.reduce((total, item) => total + item.quantity, 0);
                    updateCartCount();
                }
            }
        } catch (error) {
            console.error('Error loading user cart:', error);
        }
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
                    <a href="register.php" class="btn btn-outline">Create Account</a>
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
        }
    }

    // Update wishlist count in header
    function updateWishlistCount() {
        let count = 0;
        if (auth.currentUser && auth.currentUser.wishlist) {
            count = auth.currentUser.wishlist.length;
        }

        const wishlistCountElement = document.querySelector('.wishlist-count');
        if (wishlistCountElement) {
            wishlistCountElement.textContent = count;
        }
    }

    // Update wishlist icons on product cards
    function updateWishlistIcons() {
        if (auth.currentUser && auth.currentUser.wishlist) {
            const wishlistIds = auth.currentUser.wishlist.map(item => item.id);
            document.querySelectorAll('.product-card').forEach(card => {
                const productId = parseInt(card.dataset.id);
                const wishlistBtn = card.querySelector('.add-to-wishlist');
                if (wishlistBtn) {
                    if (wishlistIds.includes(productId)) {
                        wishlistBtn.innerHTML = '<i class="fas fa-heart"></i>';
                    } else {
                        wishlistBtn.innerHTML = '<i class="far fa-heart"></i>';
                    }
                }
            });
        } else {
            // If no user is logged in, all icons should be unfilled
            document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                btn.innerHTML = '<i class="far fa-heart"></i>';
            });
        }
    }

    // Show cart notification
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

    // Show wishlist notification
    function showWishlistNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'wishlist-notification';
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
</script>
</body>
</html>
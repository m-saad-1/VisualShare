<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - About Us</title>
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* About Page Specific Styles */
        .about-page {
            padding: 60px 0;
        }

        .page-header {
            margin-bottom: 40px;
            text-align: center;
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

        .about-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        @media (min-width: 768px) {
            .about-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .about-image {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .about-text h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .about-text p {
            margin-bottom: 15px;
            color: var(--text-color);
            line-height: 1.7;
        }

        .mission-values {
            margin-top: 60px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .value-card {
            background-color: var(--white);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            text-align: center;
            transition: var(--transition);
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .value-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .value-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .team-section {
            margin-top: 60px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .team-member {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .member-image {
            height: 300px;
            overflow: hidden;
        }

        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .team-member:hover .member-image img {
            transform: scale(1.05);
        }

        .member-info {
            padding: 20px;
            text-align: center;
        }

        .member-info h3 {
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .member-position {
            color: var(--secondary-color);
            font-weight: 500;
            margin-bottom: 15px;
        }

        .member-social {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .member-social a {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-light);
            border-radius: 50%;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .member-social a:hover {
            background-color: var(--secondary-color);
            color: var(--white);
        }
    </style>
</head>
<body>

<?php include 'header.html'; ?>

    <main class="about-page">
        <div class="container">
            <div class="page-header">
                <h1>About FashionHub</h1>
                <div class="breadcrumbs">
                    <a href="index.php">Home</a> / <span>About</span>
                </div>
            </div>
            
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="FashionHub store">
                </div>
                <div class="about-text">
                    <h2>Our Story</h2>
                    <p>Founded in 2015, FashionHub began as a small boutique with a passion for bringing high-quality fashion to everyone. What started as a single store has grown into a beloved online destination for fashion enthusiasts worldwide.</p>
                    <p>We believe that great style should be accessible to everyone, regardless of budget. That's why we work directly with manufacturers to bring you premium quality clothing at affordable prices.</p>
                    <p>Our team of fashion experts carefully curates each collection to ensure we offer the latest trends while maintaining timeless pieces that will stay in your wardrobe for years to come.</p>
                </div>
            </div>
            
            <div class="mission-values">
                <h2>Our Mission & Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Sustainability</h3>
                        <p>We're committed to ethical sourcing and sustainable practices in all aspects of our business.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Quality</h3>
                        <p>Every item in our collection meets our strict quality standards before reaching your wardrobe.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-smile"></i>
                        </div>
                        <h3>Customer Happiness</h3>
                        <p>Your satisfaction is our top priority. We go above and beyond to ensure you love your FashionHub experience.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3>Innovation</h3>
                        <p>We're constantly innovating to bring you fresh styles and an exceptional shopping experience.</p>
                    </div>
                </div>
            </div>
            
            <div class="team-section">
                <h2>Meet Our Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <div class="member-image">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80" alt="Sarah Johnson">
                        </div>
                        <div class="member-info">
                            <h3>Sarah Johnson</h3>
                            <p class="member-position">Founder & CEO</p>
                            <p>With over 15 years in the fashion industry, Sarah leads our team with passion and vision.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-member">
                        <div class="member-image">
                            <img src="https://images.unsplash.com/photo-1557862921-37829c790f19?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80" alt="Michael Chen">
                        </div>
                        <div class="member-info">
                            <h3>Michael Chen</h3>
                            <p class="member-position">Head of Design</p>
                            <p>Michael's keen eye for trends and detail ensures our collections are always on point.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-member">
                        <div class="member-image">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80" alt="Emma Rodriguez">
                        </div>
                        <div class="member-info">
                            <h3>Emma Rodriguez</h3>
                            <p class="member-position">Customer Experience</p>
                            <p>Emma and her team ensure every FashionHub customer receives exceptional service.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-member">
                        <div class="member-image">
                            <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80" alt="David Kim">
                        </div>
                        <div class="member-info">
                            <h3>David Kim</h3>
                            <p class="member-position">Operations Manager</p>
                            <p>David keeps everything running smoothly behind the scenes at FashionHub.</p>
                            <div class="member-social">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include 'footer.html'; ?>

    <script>
        // Shopping Cart
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let cartCount = cart.reduce((total, item) => total + item.quantity, 0);

        // Auth check
        const auth = {
            currentUser: JSON.parse(localStorage.getItem('currentUser')) || null
        };

        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            updateWishlistCount();
            
            
        });

        // Update cart count in header
        function updateCartCount() {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
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
            }
        }

        // Listen for storage events to update count when cart changes in other tabs
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
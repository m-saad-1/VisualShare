<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Contact Us</title>
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Contact Page Specific Styles */
        .contact-page {
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

        .contact-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        @media (min-width: 768px) {
            .contact-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .contact-info {
            background-color: var(--white);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .contact-info h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .contact-method {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-right: 15px;
            width: 30px;
            text-align: center;
        }

        .contact-details h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .contact-details p, 
        .contact-details a {
            color: var(--light-text);
            line-height: 1.6;
        }

        .contact-details a:hover {
            color: var(--secondary-color);
        }

        .contact-form-container {
            background-color: var(--white);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .contact-form h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(212, 167, 98, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .submit-btn:hover {
            background-color: #c49555;
        }

        .map-container {
            margin-top: 60px;
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        /* FAQ Section */
        .faq-section {
            margin-top: 60px;
        }

        .faq-item {
            background-color: var(--white);
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .faq-question {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .faq-question:hover {
            background-color: rgba(212, 167, 98, 0.1);
        }

        .faq-question i {
            transition: var(--transition);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-question {
            background-color: rgba(212, 167, 98, 0.1);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 20px 20px;
        }
    </style>
</head>
<body>

<?php include 'header.html'; ?>

    <main class="contact-page">
        <div class="container">
            <div class="page-header">
                <h1>Contact Us</h1>
                <div class="breadcrumbs">
                    <a href="index.php">Home</a> / <span>Contact</span>
                </div>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <p>Have questions about our products, orders, or anything else? We're here to help!</p>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Our Store</h3>
                            <p>123 Fashion Avenue<br>New York, NY 10001<br>United States</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Call Us</h3>
                            <p><a href="tel:+18005551234">+1 (800) 555-1234</a><br>Monday-Friday: 9am-6pm EST</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email Us</h3>
                            <p><a href="mailto:info@fashionhub.com">info@fashionhub.com</a><br>We typically respond within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Live Chat</h3>
                            <p>Available during business hours<br>Look for the chat icon in the bottom right</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form class="contact-form" id="contactForm">
                        <h2>Send Us a Message</h2>
                        
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
            
            <div class="map-container">
                <h2>Find Us</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215373510721!2d-73.98784492409006!3d40.74844097138966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1689875426213!5m2!1sen!2sus" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            
            <div class="faq-section">
                <h2>Frequently Asked Questions</h2>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What are your shipping options and delivery times?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We offer standard shipping (3-5 business days) and express shipping (1-2 business days) for domestic orders. International shipping typically takes 7-14 business days depending on the destination. All orders are processed within 1-2 business days.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>What is your return policy?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We accept returns within 30 days of purchase for a full refund or exchange. Items must be unworn, unwashed, and with all original tags attached. Final sale items are not eligible for return. Please contact our customer service team to initiate a return.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How can I track my order?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Once your order has shipped, you'll receive a confirmation email with a tracking number. You can track your package using the link provided in the email or by logging into your account on our website.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Do you offer international shipping?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we ship to most countries worldwide. Shipping costs and delivery times vary by destination. Please note that international orders may be subject to customs fees and import taxes, which are the responsibility of the customer.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How do I change or cancel my order?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>If you need to change or cancel your order, please contact us immediately at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a> or call us at +1 (800) 555-1234. We can only modify orders that haven't been processed for shipping yet.</p>
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
            
            
            
            // FAQ accordion functionality
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const faqItem = this.parentElement;
                    faqItem.classList.toggle('active');
                    
                    // Close other open FAQs
                    faqQuestions.forEach(q => {
                        if (q !== this && q.parentElement.classList.contains('active')) {
                            q.parentElement.classList.remove('active');
                        }
                    });
                });
            });
            
            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const subject = document.getElementById('subject').value;
                    const message = document.getElementById('message').value;
                    
                    // In a real application, you would send this data to your server
                    console.log('Form submitted:', { name, email, subject, message });
                    
                    // Show success message
                    alert('Thank you for your message! We will get back to you soon.');
                    
                    // Reset form
                    contactForm.reset();
                });
            }
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Login</title>
    <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Additional styles for auth page */
        .auth-page {
            padding: 60px 0;
        }
        
        .auth-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .auth-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
        }
        
        .auth-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-tab.active {
            background: white;
            color: #d4a762;
            border-bottom: 2px solid #d4a762;
        }
        
        .auth-form {
            padding: 30px;
            display: none;
        }
        
        .auth-form.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }
        .form-group-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .form-group-checkbox input[type="checkbox"] {
            width: auto;
            accent-color: var(--secondary-color);
        }
        .form-group-checkbox label {
            margin-bottom: 0;
            font-weight: normal;
        }
        
        .forgot-password {
            display: inline-block;
            margin-top: 5px;
            font-size: 0.9rem;
            color: #d4a762;
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .social-login {
            margin: 25px 0;
            text-align: center;
        }
        
        .social-login p {
            color: #777;
            margin-bottom: 15px;
            position: relative;
        }
        
        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #eee;
        }
        
        .social-login p::before {
            left: 0;
        }
        
        .social-login p::after {
            right: 0;
        }
        
        .social-buttons {
            display: flex;
            gap: 10px;
        }
        
        .social-btn {
            flex: 1;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .social-btn.google {
            background: #DB4437;
        }
        
        .social-btn.facebook {
            background: #4267B2;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
        
        .auth-footer a {
            color: #d4a762;
        }
        
        .error-message {
            color: #f44336;
            margin-top: 5px;
            font-size: 0.9rem;
            display: none;
        }
        
        @media (max-width: 576px) {
            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
  <?php include 'header.html'; ?>
     <main class="auth-page">
        <div class="container">
            <div class="auth-container">
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Login</button>
                    <button class="auth-tab" data-tab="register">Register</button>
                </div>
                
                <!-- Login Form -->
                <form class="auth-form active" id="login-form">
                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" id="login-email" required>
                        <div class="error-message" id="login-email-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" required>
                        <div class="error-message" id="login-password-error"></div>
                        <a href="#" class="forgot-password" id="show-reset-form">Forgot password?</a>
                    </div>
                    
                    <div class="form-group form-group-checkbox">
                        <input type="checkbox" id="remember-me">
                        <label for="remember-me">Remember me</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    
                    <div class="social-login">
                        <p>Or login with</p>
                        <div class="social-buttons">
                            <a href="#" class="social-btn google"><i class="fab fa-google"></i> Google</a>
                            <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
                        </div>
                    </div>
                    
                    <div class="auth-footer">
                        Don't have an account? <a href="#" class="switch-tab" data-tab="register">Register here</a>
                    </div>
                </form>
                
                <!-- Registration Form -->
                <form class="auth-form" id="register-form">
                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" id="register-name" required>
                        <div class="error-message" id="register-name-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-email">Email Address</label>
                        <input type="email" id="register-email" required>
                        <div class="error-message" id="register-email-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <input type="password" id="register-password" required>
                        <div class="error-message" id="register-password-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-confirm-password">Confirm Password</label>
                        <input type="password" id="register-confirm-password" required>
                        <div class="error-message" id="register-confirm-error"></div>
                    </div>
                    
                    <div class="form-group form-group-checkbox">
                        <input type="checkbox" id="accept-terms" required>
                        <label for="accept-terms">I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></label>
                        <div class="error-message" id="terms-error"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                    
                    <div class="auth-footer">
                        Already have an account? <a href="#" class="switch-tab" data-tab="login">Login here</a>
                    </div>
                </form>
                
                <!-- Password Reset Form -->
                <form class="auth-form" id="reset-form">
                    <div class="form-group">
                        <label for="reset-email">Email Address</label>
                        <input type="email" id="reset-email" required>
                        <div class="error-message" id="reset-email-error"></div>
                    </div>
                    
                    <p>We will send you a link to reset your password.</p>
                    
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                    
                    <div class="auth-footer">
                        <a href="#" class="switch-tab" data-tab="login">Back to login</a>
                    </div>
                </form>
            </div>
        </div>
    </main>


    <!-- Footer -->
 <?php include 'footer.html'; ?>

    <script>
// User Authentication System
const auth = {
    apiBaseUrl: window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/')) + '/api',
    
    init: function() {
        // Check if user is already logged in
        this.checkAuth();

        // Initialize all form handlers
        this.initForms();

        // Initialize cart count
        this.updateCartCount();

        // Listen for user login/logout events
        window.addEventListener('userLogin', (e) => {
            this.updateCartCount();
        });

        window.addEventListener('userLogout', () => {
            localStorage.removeItem('cart');
            this.updateCartCount();
        });
    },
    
    checkAuth: async function() {
        try {
            console.log('Checking authentication status...');
            const response = await fetch(`${this.apiBaseUrl}/check_auth.php`, {
                credentials: 'include',
                headers: {
                    'Cache-Control': 'no-cache'
                }
            });
            
            console.log('Auth check response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Auth check response data:', data);
                
                if (data.status === 'success') {
                    console.log('User is authenticated, redirecting to account page');
                    // Store user data in localStorage
                    localStorage.setItem('currentUser', JSON.stringify(data.user));
                    
                    // Dispatch login event to update cart counts across the site
                    window.dispatchEvent(new CustomEvent('userLogin', { detail: data.user }));
                    
                    // Redirect to account page
                    window.location.href = 'account.php';
                } else {
                    console.log('User is not authenticated:', data.message);
                }
            } else {
                console.log('Auth check failed with status:', response.status);
            }
        } catch (error) {
            console.error('Auth check error:', error);
        }
    },
    
    initForms: function() {
        // Login form
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.handleLogin();
            });
        }
        
        // Register form
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.handleRegister();
            });
        }
        
        // Password reset form
        const resetForm = document.getElementById('reset-form');
        if (resetForm) {
            resetForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.handlePasswordReset();
            });
        }
        
        // Tab switching
        const tabs = document.querySelectorAll('.auth-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.dataset.tab;
                this.switchTab(tabId);
            });
        });
        
        // Switch tab links
        document.querySelectorAll('.switch-tab').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tabId = link.dataset.tab;
                this.switchTab(tabId);
            });
        });
        
        // Show reset form
        const showResetLink = document.getElementById('show-reset-form');
        if (showResetLink) {
            showResetLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.showResetForm();
            });
        }
    },
    
    switchTab: function(tabId) {
        // Update active tab
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabId);
        });
        
        // Show corresponding form
        document.querySelectorAll('.auth-form').forEach(form => {
            form.classList.toggle('active', form.id === `${tabId}-form`);
        });
    },
    
    showResetForm: function() {
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.auth-form').forEach(form => {
            form.classList.remove('active');
        });
        document.getElementById('reset-form').classList.add('active');
    },

    // Load user's cart from database and sync with localStorage
    loadUserCart: async function(userData) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/get_cart.php`, {
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

                    // Update cart count in header
                    this.updateCartCount();
                }
            }
        } catch (error) {
            console.error('Error loading user cart:', error);
            // Clear cart on error to prevent stale data
            localStorage.removeItem('cart');
            this.updateCartCount();
        }
    },

    // Update cart count in header
    updateCartCount: function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);

        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = cartCount;
        }
    },
    
    handleLogin: async function() {
        // Get form elements
        const emailInput = document.getElementById('login-email');
        const passwordInput = document.getElementById('login-password');
        const rememberCheckbox = document.getElementById('remember-me');
        const emailError = document.getElementById('login-email-error');
        const passwordError = document.getElementById('login-password-error');
        
        // Get values
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const rememberMe = rememberCheckbox.checked;
        
        // Clear previous errors
        emailError.style.display = 'none';
        passwordError.style.display = 'none';
        
        // Validate inputs
        let isValid = true;
        
        if (!email) {
            emailError.textContent = 'Email is required';
            emailError.style.display = 'block';
            isValid = false;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            emailError.textContent = 'Please enter a valid email';
            emailError.style.display = 'block';
            isValid = false;
        }
        
        if (!password) {
            passwordError.textContent = 'Password is required';
            passwordError.style.display = 'block';
            isValid = false;
        }
        
        if (!isValid) return;
        
        try {
            // Show loading state
            const submitButton = document.querySelector('#login-form button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            
            console.log('Attempting login with:', { email, rememberMe });
            
            // Make API request
            const response = await fetch(`${this.apiBaseUrl}/login.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    email: email,
                    password: password,
                    rememberMe: rememberMe
                })
            });
            
            console.log('Login response status:', response.status);
            
            // Reset button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            
            // Handle non-JSON responses
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text.substring(0, 200));
                throw new Error(`Server returned unexpected response: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Login response data:', data);
            
            if (data.status === 'success') {
                console.log('Login successful, storing user data and redirecting');

                // Store user data in localStorage - FIXED THE ERROR HERE
                const userData = {
                    ...data.user,
                    avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.user.name) + '&background=random',
                    joinDate: new Date().toISOString(),
                    orders: [],
                    wishlist: [],
                    addresses: {
                        // Safely access addresses with fallback values
                        billing: data.user.addresses ? data.user.addresses.billing || false : false,
                        shipping: data.user.addresses ? data.user.addresses.shipping || false : false
                    }
                };

                localStorage.setItem('currentUser', JSON.stringify(userData));

                // Load user's cart from database and sync with localStorage
                await this.loadUserCart(userData);

                // Dispatch login event to update cart counts across the site
                window.dispatchEvent(new CustomEvent('userLogin', { detail: userData }));

                // Redirect to account page
                window.location.href = 'account.php';
            } else {
                throw new Error(data.message || 'Login failed');
            }
        } catch (error) {
            console.error('Login error:', error);
            
            let errorMessage = 'Login failed. Please try again.';
            if (error.message.includes('Server returned')) {
                errorMessage = 'Server error. Please try again later.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            passwordError.textContent = errorMessage;
            passwordError.style.display = 'block';
            
            // Ensure button is reset even on error
            const submitButton = document.querySelector('#login-form button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Login';
            }
        }
    },
    
    handleRegister: async function() {
        // Get form elements
        const nameInput = document.getElementById('register-name');
        const emailInput = document.getElementById('register-email');
        const passwordInput = document.getElementById('register-password');
        const confirmInput = document.getElementById('register-confirm-password');
        const termsCheckbox = document.getElementById('accept-terms');
        
        // Get values
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        const acceptTerms = termsCheckbox.checked;
        
        // Clear previous errors
        document.querySelectorAll('#register-form .error-message').forEach(el => {
            el.style.display = 'none';
        });
        
        // Validate inputs
        let isValid = true;
        
        if (!name) {
            document.getElementById('register-name-error').textContent = 'Name is required';
            document.getElementById('register-name-error').style.display = 'block';
            isValid = false;
        }
        
        if (!email) {
            document.getElementById('register-email-error').textContent = 'Email is required';
            document.getElementById('register-email-error').style.display = 'block';
            isValid = false;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            document.getElementById('register-email-error').textContent = 'Please enter a valid email';
            document.getElementById('register-email-error').style.display = 'block';
            isValid = false;
        }
        
        if (!password) {
            document.getElementById('register-password-error').textContent = 'Password is required';
            document.getElementById('register-password-error').style.display = 'block';
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById('register-password-error').textContent = 'Password must be at least 6 characters';
            document.getElementById('register-password-error').style.display = 'block';
            isValid = false;
        }
        
        if (password !== confirmPassword) {
            document.getElementById('register-confirm-error').textContent = 'Passwords do not match';
            document.getElementById('register-confirm-error').style.display = 'block';
            isValid = false;
        }
        
        if (!acceptTerms) {
            document.getElementById('terms-error').textContent = 'You must accept the terms';
            document.getElementById('terms-error').style.display = 'block';
            isValid = false;
        }
        
        if (!isValid) return;
        
        try {
            // Show loading state
            const submitButton = document.querySelector('#register-form button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
            
            // Make API request
            const response = await fetch(`${this.apiBaseUrl}/register.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password
                })
            });
            
            // Reset button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Server returned invalid response: ${text.substring(0, 100)}`);
            }
            
            const data = await response.json();
            
            if (data.status === 'success') {
                // Store user data in localStorage
                const userData = {
                    ...data.user,
                    avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.user.name) + '&background=random',
                    joinDate: new Date().toISOString(),
                    orders: [],
                    wishlist: [],
                    addresses: {
                        billing: false,
                        shipping: false
                    }
                };

                localStorage.setItem('currentUser', JSON.stringify(userData));

                // Load user's cart from database (should be empty for new users, but sync anyway)
                await this.loadUserCart(userData);

                // Dispatch login event to update cart counts across the site
                window.dispatchEvent(new CustomEvent('userLogin', { detail: userData }));

                // Redirect to account page after successful registration
                window.location.href = 'account.php';
            } else {
                if (data.errors) {
                    // Show field-specific errors
                    for (const [field, error] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`register-${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = error;
                            errorElement.style.display = 'block';
                        }
                    }
                } else {
                    throw new Error(data.message || 'Registration failed');
                }
            }
        } catch (error) {
            console.error('Registration error:', error);
            
            let errorMessage = 'Registration failed. Please try again.';
            if (error.message.includes('Server returned')) {
                errorMessage = 'Server error. Please try again later.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            document.getElementById('register-email-error').textContent = errorMessage;
            document.getElementById('register-email-error').style.display = 'block';
            
            // Ensure button is reset even on error
            const submitButton = document.querySelector('#register-form button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Register';
            }
        }
    },
    
    handlePasswordReset: async function() {
        // Get form elements
        const emailInput = document.getElementById('reset-email');
        const errorElement = document.getElementById('reset-email-error');
        
        // Get value
        const email = emailInput.value.trim();
        
        // Clear previous error
        errorElement.style.display = 'none';
        
        // Validate input
        if (!email) {
            errorElement.textContent = 'Email is required';
            errorElement.style.display = 'block';
            return;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errorElement.textContent = 'Please enter a valid email';
            errorElement.style.display = 'block';
            return;
        }
        
        try {
            // Show loading state
            const submitButton = document.querySelector('#reset-form button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            // Make API request
            const response = await fetch(`${this.apiBaseUrl}/reset_password.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email
                })
            });
            
            // Reset button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Server returned invalid response: ${text.substring(0, 100)}`);
            }
            
            const data = await response.json();
            
            if (data.status === 'success') {
                // Show success message and switch to login tab
                alert(data.message || 'Password reset link has been sent to your email');
                this.switchTab('login');
            } else {
                throw new Error(data.message || 'Password reset failed');
            }
        } catch (error) {
            console.error('Password reset error:', error);
            
            let errorMessage = 'Password reset failed. Please try again.';
            if (error.message.includes('Server returned')) {
                errorMessage = 'Server error. Please try again later.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            
            // Ensure button is reset even on error
            const submitButton = document.querySelector('#reset-form button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Reset Password';
            }
        }
    }
};

// Initialize auth system when page loads
document.addEventListener('DOMContentLoaded', () => {
    auth.init();
});

    </script>
</body>
</html>
// Product data with complete details for modal
const products = [
  {
    id: 1,
    title: "Premium Denim Jacket",
    brand: "UNITED",
    price: 99.99,
    originalPrice: 129.99,
    image: "https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1065&q=80",
    badge: "new",
    rating: 4.5,
    reviews: 24,
    description: "This premium denim jacket features a classic fit with modern details. Made from 100% cotton denim with a medium wash for a timeless look.",
    features: [
      "100% Cotton denim",
      "Medium wash",
      "Classic fit",
      "Metal buttons",
      "Machine washable"
    ],
    sizes: ["S", "M", "L", "XL"],
    colors: ["Blue", "Black"],
    sku: "DJ-001",
    category: "Jackets"
  },
  // ... other products
];

// Global variables
let currentModalProduct = null;
let selectedSize = null;
let selectedColor = null;
let cart = [];
let wishlist = [];

// Load products into the grid
export function loadProducts(containerId = 'featured-products-grid') {
  const productsGrid = document.getElementById(containerId);
  if (!productsGrid) return;
  
  productsGrid.innerHTML = '';
  
  products.forEach(product => {
    const discount = product.originalPrice ? 
      Math.round(((product.originalPrice - product.price) / product.originalPrice) * 100) : 0;
    
    const stars = Array(Math.floor(product.rating)).fill('<i class="fas fa-star"></i>').join('') + 
      (product.rating % 1 >= 0.5 ? '<i class="fas fa-star-half-alt"></i>' : '');
    
    const productCard = document.createElement('div');
    productCard.className = 'product-card';
    productCard.innerHTML = `
      ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
      <div class="product-image">
        <img src="${product.image}" alt="${product.title}">
      </div>
      <div class="product-info">
        <h3 class="product-title">${product.title}</h3>
        <div class="product-price">
          <span class="current-price">$${product.price.toFixed(2)}</span>
          ${product.originalPrice ? `<span class="old-price">$${product.originalPrice.toFixed(2)}</span>` : ''}
          ${discount > 0 ? `<span class="discount">${discount}% OFF</span>` : ''}
        </div>
        <div class="product-meta">
          <div class="rating">
            <div class="stars">${stars}</div>
            <span>(${product.reviews})</span>
          </div>
        </div>
      </div>
      <div class="product-actions">
        <button class="action-btn quick-view" title="Quick View">
          <i class="far fa-eye"></i>
        </button>
        <button class="action-btn add-to-wishlist" title="Add to Wishlist">
          <i class="far fa-heart"></i>
        </button>
        <button class="action-btn add-to-cart" title="Add to Cart">
          <i class="fas fa-shopping-cart"></i>
        </button>
      </div>
    `;
    
    productsGrid.appendChild(productCard);
    
    // Add event listeners
    const quickViewBtn = productCard.querySelector('.quick-view');
    const wishlistBtn = productCard.querySelector('.add-to-wishlist');
    const cartBtn = productCard.querySelector('.add-to-cart');
    
    // Click on product card opens modal
    productCard.addEventListener('click', function(e) {
      if (e.target.closest('.product-actions')) return;
      showProductModal(product);
    });
    
    quickViewBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      showProductModal(product);
    });
    
    wishlistBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      toggleWishlist(product.id);
    });
    
    cartBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      addToCart(product.id);
    });
  });
}

// Show product modal with details
export function showProductModal(product) {
  currentModalProduct = product;
  selectedSize = null;
  selectedColor = null;

  // Update modal content
  document.getElementById('modalProductTitle').textContent = product.title;
  document.getElementById('modalProductImage').src = product.image;
  document.getElementById('modalProductImage').alt = product.title;
  document.getElementById('modalProductPrice').textContent = `$${product.price.toFixed(2)}`;

  const oldPriceEl = document.getElementById('modalProductOldPrice');
  const discountEl = document.getElementById('modalProductDiscount');

  if (product.originalPrice) {
    const discount = Math.round(((product.originalPrice - product.price) / product.originalPrice) * 100);
    oldPriceEl.textContent = `$${product.originalPrice.toFixed(2)}`;
    discountEl.textContent = `${discount}% OFF`;
  } else {
    oldPriceEl.textContent = '';
    discountEl.textContent = '';
  }

  const stars = Array(Math.floor(product.rating)).fill('<i class="fas fa-star"></i>').join('') +
    (product.rating % 1 >= 0.5 ? '<i class="fas fa-star-half-alt"></i>' : '');
  document.getElementById('modalProductRating').innerHTML = stars;
  document.getElementById('modalProductReviews').textContent = `(${product.reviews} reviews)`;
  document.getElementById('modalProductDescription').textContent = product.description;

  // Update features list
  const featuresList = document.getElementById('modalProductFeatures');
  featuresList.innerHTML = '';
  product.features.forEach(feature => {
    const li = document.createElement('li');
    li.textContent = feature;
    featuresList.appendChild(li);
  });

  // Update size options
  const sizeOptions = document.getElementById('modalSizeOptions');
  sizeOptions.innerHTML = '';
  product.sizes.forEach((size, index) => {
    const sizeBtn = document.createElement('button');
    sizeBtn.className = 'size-btn';
    sizeBtn.textContent = size;
    sizeBtn.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('selected'));
      this.classList.add('selected');
      selectedSize = size;
    });
    sizeOptions.appendChild(sizeBtn);

    // Auto-select first size
    if (index === 0) {
      sizeBtn.classList.add('selected');
      selectedSize = size;
    }
  });

  // Update color options
  const colorOptions = document.getElementById('modalColorOptions');
  colorOptions.innerHTML = '';
  product.colors.forEach((color, index) => {
    const colorBtn = document.createElement('button');
    colorBtn.className = 'color-btn';
    colorBtn.style.backgroundColor = getColorValue(color);
    colorBtn.title = color;
    colorBtn.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelectorAll('.color-btn').forEach(btn => btn.classList.remove('selected'));
      this.classList.add('selected');
      selectedColor = color;
    });
    colorOptions.appendChild(colorBtn);

    // Auto-select first color
    if (index === 0) {
      colorBtn.classList.add('selected');
      selectedColor = color;
    }
  });

  // Update SKU and category
  document.getElementById('modalProductSKU').textContent = product.sku;
  document.getElementById('modalProductCategory').textContent = product.category;

  // Update wishlist button state
  updateWishlistButton();

  // Reset quantity
  document.getElementById('productQuantity').value = 1;

  // Show modal
  document.getElementById('productModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

// Helper function to get color values
function getColorValue(color) {
  const colors = {
    'Blue': '#3498db',
    'Black': '#2c3e50',
    'White': '#ecf0f1',
    'Ivory': '#fffff0',
    'Dark Blue': '#1a237e',
    'Brown': '#795548',
    'Red': '#e53935',
    'Green': '#43a047',
    'Yellow': '#fdd835'
  };
  return colors[color] || color;
}

// Update wishlist button state
function updateWishlistButton() {
  if (!currentModalProduct) return;

  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  const isInWishlist = currentUser && currentUser.wishlist ?
    currentUser.wishlist.some(item => item.id === currentModalProduct.id) : false;

  const icon = isInWishlist ? 'fas' : 'far';

  const wishlistBtn = document.getElementById('addToWishlistModal');
  if (wishlistBtn) {
    wishlistBtn.innerHTML = `
      <i class="${icon} fa-heart"></i> ${isInWishlist ? 'Remove from' : 'Add to'} Wishlist
    `;
  }
}

// Toggle wishlist status
async function toggleWishlist(productId) {
  try {
    // Check if user is logged in
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
      showAuthAlert();
      return false;
    }

    // Validate product ID
    if (!productId || productId <= 0) {
      console.error('Invalid product ID:', productId);
      return false;
    }

    const response = await fetch('/fashionhub-old/api/toggle_wishlist.php', {
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
          currentUser.wishlist.push({
            id: product.id,
            title: product.title,
            price: product.price,
            image: product.image
          });
        }
        showWishlistNotification('Added to wishlist!');
      } else {
        currentUser.wishlist = currentUser.wishlist.filter(item => item.id !== productId);
        showWishlistNotification('Removed from wishlist!');

        // If we're in the modal, update the button
        if (currentModalProduct && currentModalProduct.id === productId) {
          const wishlistBtn = document.getElementById('addToWishlistModal');
          if (wishlistBtn) {
            wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
          }
        }
      }

      // Update localStorage
      localStorage.setItem('currentUser', JSON.stringify(currentUser));

      // Update wishlist count
      updateWishlistCount();

      // Update button states
      updateWishlistButton();

      // Update all wishlist buttons on the page
      document.querySelectorAll(`.add-to-wishlist[data-id="${productId}"] i`).forEach(icon => {
        if (data.action === 'added') {
          icon.classList.remove('far');
          icon.classList.add('fas');
        } else {
          icon.classList.remove('fas');
          icon.classList.add('far');
        }
      });

      return true;
    } else {
      console.error('Error toggling wishlist:', data.message);
      return false;
    }
  } catch (error) {
    console.error('Error toggling wishlist:', error);
    // Fallback to local storage if API call fails
    return toggleWishlistLocal(productId);
  }
}

// Fallback function for wishlist using local storage
function toggleWishlistLocal(productId) {
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  if (!currentUser) {
    showAuthAlert();
    return false;
  }

  // Validate product ID
  if (!productId || productId <= 0) {
    console.error('Invalid product ID:', productId);
    return false;
  }

  // Initialize wishlist if it doesn't exist
  if (!currentUser.wishlist) {
    currentUser.wishlist = [];
  }

  // Check if product is already in wishlist
  const existingIndex = currentUser.wishlist.findIndex(item => item.id === productId);

  if (existingIndex >= 0) {
    // Remove from wishlist
    currentUser.wishlist.splice(existingIndex, 1);
    showWishlistNotification('Removed from wishlist!');

    // If we're in the modal, update the button
    if (currentModalProduct && currentModalProduct.id === productId) {
      const wishlistBtn = document.getElementById('addToWishlistModal');
      if (wishlistBtn) {
        wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
      }
    }
  } else {
    // Add to wishlist
    const product = products.find(p => p.id === productId);
    if (product) {
      currentUser.wishlist.push({
        id: product.id,
        title: product.title,
        price: product.price,
        image: product.image
      });

      showWishlistNotification('Added to wishlist!');
    }
  }

  // Save to localStorage
  localStorage.setItem('currentUser', JSON.stringify(currentUser));

  // Update wishlist count
  updateWishlistCount();

  // Update button states
  updateWishlistButton();

  return true;
}

// Add product to cart
async function addToCart(productId, quantity = 1) {
  const product = products.find(p => p.id === productId);
  if (!product) return;

  // Check if user is logged in
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  if (!currentUser) {
    showAuthAlert();
    return;
  }

  // Apply default values if not selected
  let finalSize = selectedSize;
  let finalColor = selectedColor;
  
  if (!finalSize && product.sizes && product.sizes.length > 0) {
    finalSize = product.sizes[0]; // Use first available size as default
  }
  
  if (!finalColor && product.colors && product.colors.length > 0) {
    finalColor = product.colors[0]; // Use first available color as default
  }

  try {
    const response = await fetch('/fashionhub-old/api/add_to_cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity,
        size: finalSize,
        color: finalColor
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
    addToCartLocal(productId, quantity, finalSize, finalColor);
  }
}

// Fallback function for adding to cart in localStorage
function addToCartLocal(productId, quantity = 1) {
  const product = products.find(p => p.id === productId);
  if (!product) return;

  const existingItemIndex = cart.findIndex(item =>
    item.id === product.id &&
    item.size === selectedSize &&
    item.color === selectedColor
  );

  if (existingItemIndex >= 0) {
    cart[existingItemIndex].quantity += quantity;
  } else {
    cart.push({
      id: product.id,
      title: product.title,
      price: product.price,
      image: product.image,
      size: selectedSize,
      color: selectedColor,
      quantity: quantity
    });
  }

  // Save to localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  // Update cart count
  updateCartCount();

  // Show notification
  showCartNotification(product);
}

// Load user's cart from database
async function loadUserCart() {
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  if (!currentUser) return;

  try {
    const response = await fetch('/fashionhub-old/api/get_cart.php', {
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
        updateCartCount();
      }
    }
  } catch (error) {
    console.error('Error loading user cart:', error);
  }
}

// Update cart count
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  let totalItems = 0;

  cart.forEach(item => {
    totalItems += item.quantity;
  });

  const cartCountElement = document.querySelector('.cart-count');
  if (cartCountElement) {
    cartCountElement.textContent = totalItems;
    cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
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
  
  setTimeout(() => notification.classList.add('show'), 10);
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Helper functions
function showAuthAlert() {
  const alert = document.createElement('div');
  alert.className = 'auth-alert';
  alert.innerHTML = `
    <div class="auth-alert-content">
      <h3>Account Required</h3>
      <p>Please create an account or login to add items to your wishlist or cart.</p>
      <div class="auth-alert-buttons">
        <a href="login.html" class="btn btn-primary">Login</a>
        <a href="register.html" class="btn btn-outline">Create Account</a>
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

function updateWishlistCount() {
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  let wishlistCount = 0;

  if (currentUser && currentUser.wishlist) {
    wishlistCount = currentUser.wishlist.length;
  }

  const wishlistCountElement = document.querySelector('.wishlist-count');
  if (wishlistCountElement) {
    wishlistCountElement.textContent = wishlistCount;
    wishlistCountElement.style.display = wishlistCount > 0 ? 'flex' : 'none';
  }
}

// Initialize product system
export function initProductSystem() {
  const modal = document.getElementById('productModal');
  if (!modal) return;

  // Modal functionality
  document.getElementById('closeModal').addEventListener('click', function() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  });

  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  });

  // Quantity selector
  document.getElementById('decreaseQty').addEventListener('click', function() {
    const quantityInput = document.getElementById('productQuantity');
    let value = parseInt(quantityInput.value);
    if (value > 1) quantityInput.value = value - 1;
  });

  document.getElementById('increaseQty').addEventListener('click', function() {
    const quantityInput = document.getElementById('productQuantity');
    let value = parseInt(quantityInput.value);
    quantityInput.value = value + 1;
  });

  // Add to cart from modal
  document.getElementById('addToCartModal').addEventListener('click', function() {
    if (!currentModalProduct) return;
    const quantity = parseInt(document.getElementById('productQuantity').value);
    addToCart(currentModalProduct.id, quantity);
    modal.classList.remove('active');
    document.body.style.overflow = '';
  });

  // Add to wishlist from modal
  document.getElementById('addToWishlistModal').addEventListener('click', function() {
    if (!currentModalProduct) return;
    toggleWishlist(currentModalProduct.id);
  });
}
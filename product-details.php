<?php
// Product data (temporary, should be in a database)
$products = [
    [
        'id' => 1,
        'title' => "Premium Cotton Shirt",
        'category' => "shirts",
        'price' => 59.99,
        'oldPrice' => 74.99,
        'image' => "https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["black", "blue", "white"],
        'sizes' => ["XS", "S", "M", "L", "XL"],
        'rating' => 4.5,
        'reviews' => 24,
        'badge' => "sale",
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-001",
        'description' => "This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. The tailored fit provides a modern silhouette while allowing freedom of movement. Perfect for both casual and business casual occasions.",
        'features' => [
            "100% Organic Cotton",
            "Button-down collar",
            "Single chest pocket",
            "Tailored fit",
            "Machine washable"
        ],
        'colorCodes' => [
            "black" => "#3a3a3a",
            "blue" => "#5a8ac1",
            "white" => "#e6e6e6"
        ]
    ],
    [
        'id' => 2,
        'title' => "Slim Fit Jeans",
        'category' => "jeans",
        'price' => 79.99,
        'oldPrice' => 89.99,
        'image' => "https://images.unsplash.com/photo-1542272604-787c3835535d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["black", "blue"],
        'sizes' => ["28", "30", "32", "34", "36"],
        'rating' => 4.0,
        'reviews' => 18,
        'badge' => "new",
        'featured' => false,
        'newArrival' => true,
        'sku' => "FH-002",
        'description' => "These slim fit jeans are designed for a modern, tailored look. Made from premium denim with just the right amount of stretch for comfort. The dark wash makes them versatile enough for both casual and dressier occasions.",
        'features' => [
            "98% Cotton, 2% Elastane",
            "Slim fit through hip and thigh",
            "Zip fly with button closure",
            "Five-pocket styling",
            "Machine wash cold"
        ],
        'colorCodes' => [
            "black" => "#3a3a3a",
            "blue" => "#5a8ac1"
        ]
    ],
    [
        'id' => 3,
        'title' => "Classic Denim Jacket",
        'category' => "jackets",
        'price' => 99.99,
        'image' => "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["blue", "black"],
        'sizes' => ["S", "M", "L", "XL"],
        'rating' => 5.0,
        'reviews' => 32,
        'badge' => null,
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-003",
        'description' => "This timeless denim jacket is a wardrobe essential. Made from durable 12-ounce denim, it features a classic fit that layers easily over your favorite tops. The jacket has a button-front closure, chest pockets, and adjustable waist tabs for a custom fit.",
        'features' => [
            "100% Cotton denim",
            "Classic fit",
            "Button-front closure",
            "Chest pockets with flap",
            "Adjustable waist tabs"
        ],
        'colorCodes' => [
            "blue" => "#5a8ac1",
            "black" => "#3a3a3a"
        ]
    ],
    [
        'id' => 4,
        'title' => "Casual Summer Dress",
        'category' => "dresses",
        'price' => 69.99,
        'oldPrice' => 79.99,
        'image' => "https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["beige", "white", "black"],
        'sizes' => ["XS", "S", "M"],
        'rating' => 4.0,
        'reviews' => 21,
        'badge' => "sale",
        'featured' => false,
        'newArrival' => false,
        'sku' => "FH-004",
        'description' => "This breezy summer dress is perfect for warm weather. Made from lightweight linen blend fabric that drapes beautifully and keeps you cool. The wrap-style design with tie waist creates a flattering silhouette for all body types.",
        'features' => [
            "65% Linen, 35% Cotton",
            "Wrap-style with tie waist",
            "V-neckline",
            "Short sleeves",
            "Machine wash gentle"
        ],
        'colorCodes' => [
            "beige" => "#d4a762",
            "white" => "#e6e6e6",
            "black" => "#3a3a3a"
        ]
    ],
    [
        'id' => 5,
        'title' => "Leather Crossbody Bag",
        'category' => "accessories",
        'price' => 89.99,
        'image' => "https://images.unsplash.com/photo-1543076447-215ad9ba6923?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["black", "beige"],
        'sizes' => ["One Size"],
        'rating' => 4.5,
        'reviews' => 27,
        'badge' => null,
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-005",
        'description' => "This stylish crossbody bag is crafted from genuine leather that develops a beautiful patina over time. The compact design fits all your essentials while keeping your hands free. Features multiple compartments for organization and an adjustable strap for comfort.",
        'features' => [
            "Genuine leather",
            "Adjustable crossbody strap",
            "Main zip compartment",
            "Interior slip pocket",
            "Exterior back zip pocket"
        ],
        'colorCodes' => [
            "black" => "#3a3a3a",
            "beige" => "#d4a762"
        ]
    ],
    [
        'id' => 6,
        'title' => "Premium Wool Coat",
        'category' => "jackets",
        'price' => 129.99,
        'image' => "https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["black", "beige"],
        'sizes' => ["S", "M", "L"],
        'rating' => 4.0,
        'reviews' => 15,
        'badge' => "new",
        'featured' => false,
        'newArrival' => true,
        'sku' => "FH-006",
        'description' => "This premium wool coat is perfect for transitional weather. Made from a wool blend that provides warmth without bulk. The tailored silhouette and notched lapel create a polished look that works from office to evening.",
        'features' => [
            "70% Wool, 30% Polyester",
            "Notched lapel",
            "Single-breasted button front",
            "Flap pockets",
            "Lined interior"
        ],
        'colorCodes' => [
            "black" => "#3a3a3a",
            "beige" => "#d4a762"
        ]
    ],
    [
        'id' => 7,
        'title' => "Linen Button-Up Shirt",
        'category' => "shirts",
        'price' => 49.99,
        'oldPrice' => 59.99,
        'image' => "https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["white", "blue"],
        'sizes' => ["S", "M", "L", "XL"],
        'rating' => 4.5,
        'reviews' => 19,
        'badge' => "sale",
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-007",
        'description' => "This lightweight linen shirt is perfect for warm weather. The breathable fabric and relaxed fit keep you cool and comfortable all day long. The button-up design makes it versatile enough for both casual and dressier occasions.",
        'features' => [
            "100% Linen",
            "Button-up front",
            "Chest pocket",
            "Relaxed fit",
            "Machine washable"
        ],
        'colorCodes' => [
            "white" => "#e6e6e6",
            "blue" => "#5a8ac1"
        ]
    ],
    [
        'id' => 8,
        'title' => "Cashmere Scarf",
        'category' => "accessories",
        'price' => 59.99,
        'image' => "https://images.unsplash.com/photo-1674515625083-24e5d544a532?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGNhc2htZXJlJTIwc2NhcmZ8ZW58MHx8MHx8fDA%3D",
        'colors' => ["red", "black", "beige"],
        'sizes' => ["One Size"],
        'rating' => 5.0,
        'reviews' => 12,
        'badge' => null,
        'featured' => false,
        'newArrival' => false,
        'sku' => "FH-008",
        'description' => "This luxurious cashmere scarf is the perfect accessory for cooler weather. The ultra-soft cashmere provides warmth without bulk, and the generous size allows for versatile styling. A timeless piece that will last for years.",
        'features' => [
            "100% Cashmere",
            "Generous size: 70\" x 12\"",
            "Fringed ends",
            "Ultra-soft hand feel",
            "Dry clean recommended"
        ],
        'colorCodes' => [
            "red" => "#ff0000",
            "black" => "#3a3a3a",
            "beige" => "#d4a762"
        ]
    ],
    [
        'id' => 9,
        'title' => "Classic White Sneakers",
        'category' => "shoes",
        'price' => 89.99,
        'image' => "https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["white", "black"],
        'sizes' => ["US 7", "US 8", "US 9", "US 10", "US 11"],
        'rating' => 4.8,
        'reviews' => 42,
        'badge' => "best-seller",
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-009",
        'description' => "These classic white sneakers are a wardrobe staple. Made from premium leather with a comfortable cushioned insole, they're perfect for all-day wear. The timeless design pairs well with any outfit.",
        'features' => [
            "Premium leather upper",
            "Cushioned insole for comfort",
            "Rubber outsole for traction",
            "Lace-up closure",
            "Machine washable"
        ],
        'colorCodes' => [
            "white" => "#ffffff",
            "black" => "#3a3a3a"
        ]
    ],
    [
        'id' => 10,
        'title' => "Wool Blend Sweater",
        'category' => "sweaters",
        'price' => 79.99,
        'oldPrice' => 99.99,
        'image' => "https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["navy", "gray", "burgundy"],
        'sizes' => ["S", "M", "L", "XL"],
        'rating' => 4.3,
        'reviews' => 28,
        'badge' => "sale",
        'featured' => true,
        'newArrival' => false,
        'sku' => "FH-010",
        'description' => "This wool blend sweater offers warmth and style for the cooler months. The relaxed fit and ribbed cuffs provide both comfort and a polished look. Perfect for layering or wearing on its own.",
        'features' => [
            "70% Wool, 30% Acrylic",
            "Ribbed cuffs and hem",
            "Relaxed fit",
            "Crew neckline",
            "Machine wash cold"
        ],
        'colorCodes' => [
            "navy" => "#001f3f",
            "gray" => "#aaaaaa",
            "burgundy" => "#800020"
        ]
    ],
    [
        'id' => 11,
        'title' => "Silk Blouse",
        'category' => "shirts",
        'price' => 89.99,
        'image' => "https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
        'colors' => ["ivory", "black", "sage"],
        'sizes' => ["XS", "S", "M", "L"],
        'rating' => 4.6,
        'reviews' => 35,
        'badge' => null,
        'featured' => false,
        'newArrival' => true,
        'sku' => "FH-011",
        'description' => "This elegant silk blouse features a delicate drape and subtle sheen that elevates any outfit. The button-front design and pointed collar create a sophisticated look perfect for work or special occasions.",
        'features' => [
            "100% Silk",
            "Button-front closure",
            "Pointed collar",
            "Long sleeves with button cuffs",
            "Dry clean only"
        ],
        'colorCodes' => [
            "ivory" => "#fffff0",
            "black" => "#3a3a3a",
            "sage" => "#b2ac88"
        ]
    ],
    [
        'id' => 12,
        'title' => "Leather Belt",
        'category' => "accessories",
        'price' => 49.99,
        'image' => "https://images.unsplash.com/photo-1664286074176-5206ee5dc878?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        'colors' => ["brown", "black"],
        'sizes' => ["S (30\")", "M (32\")", "L (34\")", "XL (36\")"],
        'rating' => 4.2,
        'reviews' => 19,
        'badge' => null,
        'featured' => false,
        'newArrival' => false,
        'sku' => "FH-012",
        'description' => "This genuine leather belt features a classic design with a polished buckle. The high-quality leather develops a beautiful patina over time, making it a durable and stylish accessory.",
        'features' => [
            "Genuine leather",
            "Polished metal buckle",
            "Adjustable fit",
            "Reversible (black/brown option)",
            "Width: 1.5 inches"
        ],
        'colorCodes' => [
            "brown" => "#964B00",
            "black" => "#3a3a3a"
        ]
    ]
];

// Get product ID and image from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$productImage = isset($_GET['image']) ? urldecode($_GET['image']) : null;
 
// Find the product
$product = null;
foreach ($products as $p) {
    if ($p['id'] === $productId) {
        $product = $p;
        break;
    }
}
 
// If product not found, redirect to shop
if (!$product) {
    header("Location: shop.php");
    exit;
}
 
// If an image was passed from shop.php, use it instead of the default
if ($productImage) {
    $product['image'] = $productImage;
}


$productReviews = isset($reviews[$productId]) ? $reviews[$productId] : [];

// Get related products
$relatedProducts = [];
foreach ($products as $p) {
    if ($p['category'] === $product['category'] && $p['id'] !== $product['id']) {
        $relatedProducts[] = $p;
    }
    if (count($relatedProducts) >= 4) {
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - <?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            border: 1px solid #eee;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .product-card a {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-image {
            width: 100%;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            position: relative;
        }
        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .product-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .product-price {
            margin-top: auto;
        }
        .accordion-content {
            display: none;
            padding: 15px;
            border-top: 1px solid #eee;
        }
        .accordion-item.active .accordion-content {
            display: block;
        }
        .accordion-header i {
            transition: transform 0.3s ease;
        }
        .accordion-item.active .accordion-header i {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <!-- Header -->
<?php include 'header.html'; ?>


<main class="product-page">
    <div class="container">
        <div class="breadcrumbs">
            <a href="index.php">Home</a> /
            <a href="shop.php">Shop</a> /
            <a href="shop.php?category=<?php echo htmlspecialchars($product['category']); ?>"><?php echo htmlspecialchars(ucfirst($product['category'])); ?></a> /
            <span><?php echo htmlspecialchars($product['title']); ?></span>
        </div>
        
        <div class="product-details" style="margin-top: 40px;">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="gallery-main">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" id="mainProductImage">
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
                <div class="product-meta">
                    <div class="rating">
                        <div class="stars">
                            <?php for ($i = 0; $i < 5; $i++):
                                // Determine the star icon based on the product rating
                                $starClass = 'far fa-star'; // Default to empty star
                                if ($i < floor($product['rating'])) {
                                    $starClass = 'fas fa-star'; // Full star
                                } elseif ($i < $product['rating']) {
                                    $starClass = 'fas fa-star-half-alt'; // Half star
                                }
                            ?>
                                <i class="<?php echo $starClass; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <a href="#reviews" class="review-count"><?php echo count($productReviews); ?> reviews</a>
                    </div>
                    <div class="sku">SKU: <?php echo htmlspecialchars($product['sku']); ?></div>
                </div>
                
                <div class="product-price">
                    <span class="current-price">$<?php echo htmlspecialchars($product['price']); ?></span>
                    <?php if (isset($product['oldPrice'])): 
                        // Display old price if it exists
                    ?>
                        <span class="old-price">$<?php echo htmlspecialchars($product['oldPrice']); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <div class="details-accordion">
                        <div class="accordion-item active">
                            <button class="accordion-header">Fabric &amp; Care <i class="fas fa-chevron-down"></i></button>
                            <div class="accordion-content">
                                <ul>
                                    <?php foreach ($product['features'] as $feature):
                                        // Display each feature in a list item
                                    ?>
                                        <li><?php echo htmlspecialchars($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <button class="accordion-header">Size &amp; Fit <i class="fas fa-chevron-down"></i></button>
                            <div class="accordion-content">
                                <p>Regular fit. Model is 6'2" wearing size Medium.</p>
                                <a href="#" class="size-guide-link">View Size Guide</a>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <button class="accordion-header">Shipping &amp; Returns <i class="fas fa-chevron-down"></i></button>
                            <div class="accordion-content">
                                <p>Free shipping on orders over $50. Easy 30-day returns.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="product-actions">
                    <div class="size-selector">
                        <label>Size:</label>
                        <div class="size-options">
                            <?php foreach ($product['sizes'] as $size):
                                // Display each available size
                            ?>
                                <button class="size-option"><?php echo htmlspecialchars($size); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="color-selector">
                        <label>Color:</label>
                        <div class="color-options">
                            <?php foreach ($product['colors'] as $color):
                                // Display color swatch
                            ?>
                                <button class="color-option" style="background-color: <?php echo htmlspecialchars($product['colorCodes'][$color]); ?>;" data-color="<?php echo htmlspecialchars($color); ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="qty-input">
                            <button class="qty-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" value="1" min="1">
                            <button class="qty-btn plus"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary add-to-cart">Add to Cart</button>
                        <button class="btn btn-outline add-to-wishlist"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                
                <div class="product-share">
                    <span>Share:</span>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                    <a href="#"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <ul class="tab-nav">
                <li class="active" data-tab="description">Description</li>
                <li data-tab="reviews">Reviews (<?php echo count($productReviews); ?>)</li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <h3>Product Description</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <ul>
                        <?php foreach ($product['features'] as $feature):
                            // Display each feature in a list item
                        ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="tab-pane" id="reviews">
                    <div class="review-list">
                        <h3>Customer Reviews</h3>
                        <?php if (count($productReviews) > 0):
                            // Display reviews if available
                        ?>
                            <?php foreach ($productReviews as $review):
                                // Determine the star class for each review rating
                                $ratingStars = '';
                                for ($i = 0; $i < 5; $i++) {
                                    $ratingStars .= '<i class="' . ($i < $review['rating'] ? 'fas' : 'far') . ' fa-star"></i>';
                                }
                            ?>
                                <div class="review">
                                    <div class="review-header">
                                        <div class="review-author"><?php echo htmlspecialchars($review['author']); ?></div>
                                        <div class="review-rating">
                                            <?php echo $ratingStars; ?>
                                        </div>
                                        <div class="review-date"><?php echo htmlspecialchars($review['date']); ?></div>
                                    </div>
                                    <div class="review-title"><?php echo htmlspecialchars($review['title']); ?></div>
                                    <div class="review-content">
                                        <p><?php echo htmlspecialchars($review['content']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else:
                            // Message if no reviews are available
                        ?>
                            <p>No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <section class="related-products">
            <h2 class="section-title">You May Also Like</h2>
            <div class="product-grid">
                <?php foreach ($relatedProducts as $relatedProduct):
                    // Display each related product card
                ?>
                    <div class="product-card">
                        <a href="product-details.php?id=<?php echo $relatedProduct['id']; ?>&image=<?php echo urlencode($relatedProduct['image']); ?>">
                            <?php if (isset($relatedProduct['badge']) && $relatedProduct['badge']): ?>
                                <div class="product-badge badge <?php echo htmlspecialchars($relatedProduct['badge']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($relatedProduct['badge'])); ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($relatedProduct['image']); ?>" alt="<?php echo htmlspecialchars($relatedProduct['title']); ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($relatedProduct['title']); ?></h3>
                                <div class="product-meta">
                                    <div class="rating">
                                        <div class="stars">
                                            <?php for ($i = 0; $i < 5; $i++):
                                                $starClass = 'far fa-star';
                                                if ($i < floor($relatedProduct['rating'])) {
                                                    $starClass = 'fas fa-star';
                                                } elseif ($i < $relatedProduct['rating']) {
                                                    $starClass = 'fas fa-star-half-alt';
                                                }
                                            ?>
                                                <i class="<?php echo $starClass; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-price">
                                    <span class="current-price">$<?php echo htmlspecialchars($relatedProduct['price']); ?></span>
                                    <?php if (isset($relatedProduct['oldPrice'])): ?>
                                        <span class="old-price">$<?php echo htmlspecialchars($relatedProduct['oldPrice']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>


    <!-- Footer -->
<?php include 'footer.html'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabNavItems = document.querySelectorAll('.tab-nav li');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabNavItems.forEach(item => {
            item.addEventListener('click', function() {
                tabNavItems.forEach(i => i.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });

        // Accordion functionality
        const accordionHeaders = document.querySelectorAll('.accordion-header');
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        });

        // Quantity selector
        const minusBtn = document.querySelector('.qty-btn.minus');
        const plusBtn = document.querySelector('.qty-btn.plus');
        const qtyInput = document.querySelector('.qty-input input');

        minusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value);
            if (value > 1) {
                qtyInput.value = value - 1;
            }
        });

        plusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value);
            qtyInput.value = value + 1;
        });
    });
</script>

</body>
</html>
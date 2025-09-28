<?php
// get_products.php
require_once 'config.php';

try {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert JSON strings to arrays
    foreach ($products as &$product) {
        $product['colors'] = json_decode($product['colors'], true);
        $product['sizes'] = json_decode($product['sizes'], true);
        $product['features'] = json_decode($product['features'], true);
        $product['color_codes'] = json_decode($product['color_codes'], true);
        // Add oldPrice field for compatibility with frontend
        $product['oldPrice'] = $product['old_price'];
        unset($product['old_price']);
        // Add newArrival field for compatibility with frontend
        $product['newArrival'] = (bool)$product['new_arrival'];
        unset($product['new_arrival']);
    }
    
    echo json_encode(['status' => 'success', 'products' => $products]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
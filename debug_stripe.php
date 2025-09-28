<?php include 'api/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Stripe Debug</title>
</head>
<body>
    <h1>Stripe Environment Variables Debug</h1>
    <p><strong>STRIPE_PUBLISHABLE_KEY:</strong> <?php echo getenv('STRIPE_PUBLISHABLE_KEY') ?: 'NOT SET'; ?></p>
    <p><strong>STRIPE_SECRET_KEY:</strong> <?php echo getenv('STRIPE_SECRET_KEY') ?: 'NOT SET'; ?></p>
    <p><strong>PORT:</strong> <?php echo getenv('PORT') ?: 'NOT SET'; ?></p>
    <p><strong>FRONTEND_URL:</strong> <?php echo getenv('FRONTEND_URL') ?: 'NOT SET'; ?></p>

    <h2>All $_ENV variables:</h2>
    <pre><?php print_r($_ENV); ?></pre>

    <h2>All $_SERVER variables:</h2>
    <pre><?php print_r($_SERVER); ?></pre>

    <script>
        console.log('JavaScript test - Stripe key:', '<?php echo getenv("STRIPE_PUBLISHABLE_KEY") ?: "NOT SET"; ?>');
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - My Orders</title>
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .orders-page {
            padding: 60px 0;
            min-height: 60vh;
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
    </style>
</head>
<body>

<?php include 'header.html'; ?>

    <main class="orders-page">
        <div class="container">
            <div class="page-header">
                <h1>My Orders</h1>
                <div class="breadcrumbs">
                    <a href="index.php">Home</a> / <a href="account.php">Account</a> / <span>Orders</span>
                </div>
            </div>
            
            <div class="orders-content">
                <p>Order history will be displayed here.</p>
            </div>
        </div>
    </main>

<?php include 'footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const mainNav = document.querySelector('.main-nav');
            
            if (mobileMenuToggle && mainNav) {
                mobileMenuToggle.addEventListener('click', function() {
                    mainNav.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
<?php
// Load configuration and environment variables
require_once 'api/config.php';

// Load .env file if it exists
if (file_exists('.env')) {
    $envLines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
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


        /* Checkout Page Styles */
        .checkout-page {
            padding: 40px 0;
        }
        
        .page-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 32px;
            color: #2a5c8d;
            margin-bottom: 10px;
        }
        
        /* Form Styles */
        .checkout-form {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2a5c8d;
            box-shadow: 0 0 0 2px rgba(42, 92, 141, 0.1);
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
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        /* Payment Methods */
        .payment-methods-section {
            margin: 30px 0;
        }
        
        .payment-option {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        .payment-option input[type="radio"] {
            display: none;
        }
        
        .payment-option label {
            display: block;
            padding: 15px 20px;
            background: #f9f9f9;
            cursor: pointer;
            font-weight: 600;
            position: relative;
            padding-left: 50px;
            transition: all 0.2s ease;
        }
        
        .payment-option label:hover {
            background: #f0f0f0;
        }
        
        .payment-option label::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .payment-option input[type="radio"]:checked + label {
            background: #f0f7ff;
            color: #2a5c8d;
        }
        
        .payment-option input[type="radio"]:checked + label::before {
            border-color: #2a5c8d;
            background: radial-gradient(#2a5c8d 0%, #2a5c8d 40%, transparent 50%, transparent);
        }
        
        .payment-details {
            padding: 20px;
            background: #fff;
            display: none;
            border-top: 1px solid #eee;
        }
        
        .payment-option input[type="radio"]:checked + label + .payment-details {
            display: block;
        }
        
        /* Stripe Elements */
        .StripeElement {
            box-sizing: border-box;
            height: 46px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            transition: box-shadow 150ms ease;
        }
        
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
            border-color: #2a5c8d;
        }
        
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
        
        /* Error Handling */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .error .form-control {
            border-color: #e74c3c;
        }
        
        .error .error-message {
            display: block;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
       .btn-outline {
            background-color: transparent;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-outline:hover {
            background-color: rgba(212, 167, 98, 0.1);
            color: var(--secondary-color);
        }
        
        .btn-primary {
            background: #2a5c8d;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e4a7a;
        }
        
        /* Checkout Actions */
        .checkout-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        /* Loading Spinner */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Hourglass Loading Animation */
        .hourglassBackground {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .hourglassBackground.active {
            opacity: 1;
            visibility: visible;
        }

        .hourglassContainer {
            position: relative;
            width: 50px;
            height: 70px;
            -webkit-animation: hourglassRotate 2s ease-in 0s infinite;
            animation: hourglassRotate 2s ease-in 0s infinite;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .hourglassContainer div,
        .hourglassContainer div:before,
        .hourglassContainer div:after {
            transform-style: preserve-3d;
        }

        @-webkit-keyframes hourglassRotate {
            0% {
                transform: rotateX(0deg);
            }

            50% {
                transform: rotateX(180deg);
            }

            100% {
                transform: rotateX(180deg);
            }
        }

        @keyframes hourglassRotate {
            0% {
                transform: rotateX(0deg);
            }

            50% {
                transform: rotateX(180deg);
            }

            100% {
                transform: rotateX(180deg);
            }
        }

        .hourglassCapTop {
            top: 0;
        }

        .hourglassCapTop:before {
            top: -25px;
        }

        .hourglassCapTop:after {
            top: -20px;
        }

        .hourglassCapBottom {
            bottom: 0;
        }

        .hourglassCapBottom:before {
            bottom: -25px;
        }

        .hourglassCapBottom:after {
            bottom: -20px;
        }

        .hourglassGlassTop {
            transform: rotateX(90deg);
            position: absolute;
            top: -16px;
            left: 3px;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            background-color: #999999;
        }

        .hourglassGlass {
            perspective: 100px;
            position: absolute;
            top: 32px;
            left: 20px;
            width: 10px;
            height: 6px;
            background-color: #999999;
            opacity: 0.5;
        }

        .hourglassGlass:before,
        .hourglassGlass:after {
            content: "";
            display: block;
            position: absolute;
            background-color: #999999;
            left: -17px;
            width: 44px;
            height: 28px;
        }

        .hourglassGlass:before {
            top: -27px;
            border-radius: 0 0 25px 25px;
        }

        .hourglassGlass:after {
            bottom: -27px;
            border-radius: 25px 25px 0 0;
        }

        .hourglassCurves:before,
        .hourglassCurves:after {
            content: "";
            display: block;
            position: absolute;
            top: 32px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #333;
            animation: hideCurves 2s ease-in 0s infinite;
        }

        .hourglassCurves:before {
            left: 15px;
        }

        .hourglassCurves:after {
            left: 29px;
        }

        @-webkit-keyframes hideCurves {
            0% {
                opacity: 1;
            }

            25% {
                opacity: 0;
            }

            30% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes hideCurves {
            0% {
                opacity: 1;
            }

            25% {
                opacity: 0;
            }

            30% {
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            100% {
                opacity: 1;
            }
        }

        .hourglassSandStream:before {
            content: "";
            display: block;
            position: absolute;
            left: 24px;
            width: 3px;
            background-color: #f4c542;
            -webkit-animation: sandStream1 2s ease-in 0s infinite;
            animation: sandStream1 2s ease-in 0s infinite;
        }

        .hourglassSandStream:after {
            content: "";
            display: block;
            position: absolute;
            top: 36px;
            left: 19px;
            border-left: #f4c542;
            border-right: #f4c542;
            border-bottom: #f4c542;
            animation: sandStream2 2s ease-in 0s infinite;
        }

        @-webkit-keyframes sandStream1 {
            0% {
                height: 0;
                top: 35px;
            }

            50% {
                height: 0;
                top: 45px;
            }

            60% {
                height: 35px;
                top: 8px;
            }

            85% {
                height: 35px;
                top: 8px;
            }

            100% {
                height: 0;
                top: 8px;
            }
        }

        @keyframes sandStream1 {
            0% {
                height: 0;
                top: 35px;
            }

            50% {
                height: 0;
                top: 45px;
            }

            60% {
                height: 35px;
                top: 8px;
            }

            85% {
                height: 35px;
                top: 8px;
            }

            100% {
                height: 0;
                top: 8px;
            }
        }

        @-webkit-keyframes sandStream2 {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 0;
            }

            51% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            91% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes sandStream2 {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 0;
            }

            51% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            91% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        .hourglassSand:before,
        .hourglassSand:after {
            content: "";
            display: block;
            position: absolute;
            left: 6px;
            background-color: #f4c542;
            perspective: 500px;
        }

        .hourglassSand:before {
            top: 8px;
            width: 39px;
            border-radius: 3px 3px 30px 30px;
            animation: sandFillup 2s ease-in 0s infinite;
        }

        .hourglassSand:after {
            border-radius: 30px 30px 3px 3px;
            animation: sandDeplete 2s ease-in 0s infinite;
        }

        @-webkit-keyframes sandFillup {
            0% {
                opacity: 0;
                height: 0;
            }

            60% {
                opacity: 1;
                height: 0;
            }

            100% {
                opacity: 1;
                height: 17px;
            }
        }

        @keyframes sandFillup {
            0% {
                opacity: 0;
                height: 0;
            }

            60% {
                opacity: 1;
                height: 0;
            }

            100% {
                opacity: 1;
                height: 17px;
            }
        }

        @-webkit-keyframes sandDeplete {
            0% {
                opacity: 0;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            1% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            24% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            25% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            50% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            90% {
                opacity: 1;
                top: 41px;
                height: 0;
                width: 10px;
                left: 20px;
            }
        }

        @keyframes sandDeplete {
            0% {
                opacity: 0;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            1% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            24% {
                opacity: 1;
                top: 45px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            25% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            50% {
                opacity: 1;
                top: 41px;
                height: 17px;
                width: 38px;
                left: 6px;
            }

            90% {
                opacity: 1;
                top: 41px;
                height: 0;
                width: 10px;
                left: 20px;
            }
        }

        /* Order Status Notifications */
        .order-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 10000;
            text-align: center;
            max-width: 400px;
            width: 90%;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .order-notification.active {
            opacity: 1;
            visibility: visible;
        }

        .order-notification.success {
            border-left: 4px solid #4CAF50;
        }

        .order-notification.error {
            border-left: 4px solid #f44336;
        }

        .notification-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .notification-icon.success {
            color: #4CAF50;
        }

        .notification-icon.error {
            color: #f44336;
        }

        .notification-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .notification-message {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .notification-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn.primary {
            background: #2a5c8d;
            color: white;
        }

        .notification-btn.primary:hover {
            background: #1e4a7a;
        }

        .notification-btn.secondary {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }

        .notification-btn.secondary:hover {
            background: #e9e9e9;
        }
        
        /* Order Items Styles */
        .order-items {
            margin: 20px 0;
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .order-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 4px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .item-variants {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #2a5c8d;
            font-weight: 600;
        }
        
        .item-quantity {
            margin-left: 15px;
            font-weight: 500;
        }
        
        .item-total {
            font-weight: 700;
            color: #2a5c8d;
            margin-left: auto;
            padding-left: 15px;
        }
        
        /* Email Display */
        .email-display {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        
        .email-display strong {
            color: #2a5c8d;
        }
        
        /* Button Focus Animation */
        .btn-next-step {
            animation: pulse 2s infinite;
            position: relative;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(42, 92, 141, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(42, 92, 141, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(42, 92, 141, 0);
            }
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .checkout-actions {
                flex-direction: column-reverse;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
            }
            
            .order-item {
                flex-wrap: wrap;
                position: relative;
                padding-bottom: 40px;
            }

            .item-price {
                position: absolute;
                bottom: 10px;
                left: 15px;
            }
            
            .item-total {
                position: absolute;
                bottom: 10px;
                right: 15px;
                margin-left: 0;
                width: auto;
                text-align: right;
                margin-top: 0;
                padding-left: 0;
            }
        }

        /* Footer */
        .main-footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 60px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .footer-col h3 {
            color: var(--white);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col li {
            margin-bottom: 10px;
        }

        .footer-col a {
            color: #ccc;
            transition: var(--transition);
        }

        .footer-col a:hover {
            color: var(--secondary-color);
        }

        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px 0 4px 0;
            outline: none;
        }

        .newsletter-form button {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary-color);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-methods {
            display: flex;
            gap: 10px;
        }

        .payment-methods img {
            height: 25px;
            width: auto;
        }
    @media (max-width: 768px) {
            /* ... your existing mobile styles ... */
            .order-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px 10px;
        position: relative;
    }

    .item-image {
        width: 80px; /* or slightly smaller for mobile */
        height: 80px;
        margin-bottom: 10px;
        margin-right: 0;
    }

    .item-details {
        flex: none;
        width: 100%;
        margin-bottom: 10px;
    }

    /* Create a container for price, quantity, subtotal, and remove */
    .item-meta {
        display: flex;
        width: 100%;
        justify-content: flex-start;
        gap: 15px;
        flex-wrap: wrap;
        font-weight: 600;
        color: #2a5c8d;
    }

    .item-price,
    .item-quantity,
    .item-total,
    .item-remove {
        position: static;
        margin: 0;
        padding: 0;
    }

            
            .item-variants {
                font-size: 13px;
                margin-bottom: 5px;
            }
            

            
            /* Summary adjustments */
            .summary-row {
                font-size: 14px;
            }
            
            .summary-row.total {
                font-size: 16px;
            }
        }

        /* For very small screens (below 480px) */
        @media (max-width: 480px) {
            .order-item {
                padding: 12px 8px;
            }
            
            .item-image {
                width: 60px;
                height: 60px;
                margin-right: 8px;
            }
            
            .item-details {
                min-width: calc(100% - 68px);
            }
            
            .item-name {
                font-size: 15px;
            }
            
            .item-price {
                left: 73px;
                bottom: 12px;
                font-size: 13px;
            }
            
            .item-quantity {
                left: 120px;
                bottom: 12px;
                font-size: 13px;
            }
            
            .item-total {
                right: 8px;
                bottom: 12px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.html'; ?>

    <main class="checkout-page">
        <div class="container">
            <div class="page-header">
                <h1>Checkout</h1>
            </div>
            
            <div class="checkout-content">
                <!-- Shipping Information -->
                <form class="checkout-form active" id="shipping-form">
                    <h2 style="margin-bottom: 20px; color: #2a5c8d;">Shipping Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping-email">Email Address</label>
                            <input type="email" id="shipping-email" class="form-control" required>
                            <div class="error-message">Please enter a valid email address</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping-first-name">First Name</label>
                            <input type="text" id="shipping-first-name" class="form-control" required>
                            <div class="error-message">Please enter your first name</div>
                        </div>
                        <div class="form-group">
                            <label for="shipping-last-name">Last Name</label>
                            <input type="text" id="shipping-last-name" class="form-control" required>
                            <div class="error-message">Please enter your last name</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping-address">Address</label>
                        <input type="text" id="shipping-address" class="form-control" required>
                        <div class="error-message">Please enter your address</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping-address2">Apartment, suite, etc. (optional)</label>
                        <input type="text" id="shipping-address2" class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping-city">City</label>
                            <input type="text" id="shipping-city" class="form-control" required>
                            <div class="error-message">Please enter your city</div>
                        </div>
                        <div class="form-group">
                            <label for="shipping-country">Country</label>
                            <select id="shipping-country" class="form-control" required>
                                <option value="">Select Country</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="AU">Australia</option>
                                <option value="DE">Germany</option>
                                <option value="FR">France</option>
                            </select>
                            <div class="error-message">Please select your country</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="shipping-state">State/Province</label>
                            <input type="text" id="shipping-state" class="form-control" required>
                            <div class="error-message">Please enter your state/province</div>
                        </div>
                        <div class="form-group">
                            <label for="shipping-zip">ZIP/Postal Code</label>
                            <input type="text" id="shipping-zip" class="form-control" required>
                            <div class="error-message">Please enter your ZIP/postal code</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping-phone">Phone</label>
                        <input type="tel" id="shipping-phone" class="form-control" required>
                        <div class="error-message">Please enter your phone number</div>
                    </div>
                    
                    <div class="form-group form-group-checkbox" style="margin-top: 25px;">
                        <input type="checkbox" id="save-address">
                        <label for="save-address">Save this information for next time</label>
                    </div>
                    
                    <div class="checkout-actions" id="next-button-section">
                        <a href="cart.html" class="btn btn-outline">Back to Cart</a>
                        <button type="button" class="btn btn-primary btn-next-step">Continue to Payment</button>
                    </div>
                </form>
                
                <!-- Payment Information -->
                <form class="checkout-form" id="payment-form" style="display: none;">
                    <h2 style="margin-bottom: 20px; color: #2a5c8d;">Payment Method</h2>
                    
                    <div class="payment-methods-section">
                        <!-- Card Payment -->
                        <div class="payment-option">
                            <input type="radio" id="card-payment" name="payment-method" value="credit-card" checked>
                            <label for="card-payment">Pay with Card</label>
                            <div class="payment-details" id="card-payment-details">
                                <div class="form-group">
                                    <label for="cardholder-name">Name on Card</label>
                                    <input type="text" id="cardholder-name" class="form-control" placeholder="John Smith" required>
                                    <div class="error-message">Please enter the name on your card</div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Card Number</label>
                                    <div id="card-number-element" class="form-control">
                                        <!-- Stripe Card Number Element will be inserted here -->
                                    </div>
                                    <div id="card-number-errors" role="alert" class="error-message"></div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group" style="flex: 1;">
                                        <label>Expiration Date</label>
                                    <div id="card-expiry-element" class="form-control">
                                        <!-- Stripe Card Expiry Element will be inserted here -->
                                    </div>
                                    <div id="card-expiry-errors" role="alert" class="error-message"></div>
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label>CVC</label>
                                    <div id="card-cvc-element" class="form-control">
                                        <!-- Stripe Card CVC Element will be inserted here -->
                                    </div>
                                    <div id="card-cvc-errors" role="alert" class="error-message"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Postal Code</label>
                                <div id="card-postal-element" class="form-control">
                                    <!-- Stripe Card Postal Code Element will be inserted here -->
                                </div>
                                <div id="card-postal-errors" role="alert" class="error-message"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash on Delivery -->
                    <div class="payment-option">
                        <input type="radio" id="cash-delivery" name="payment-method" value="cash-on-delivery">
                        <label for="cash-delivery">Pay on Delivery</label>
                        <div class="payment-details" id="cash-delivery-details">
                            <p style="margin-bottom: 15px;">Pay with cash upon delivery. An additional $5.00 fee will be charged for this service.</p>
                            <p><i class="fas fa-info-circle" style="color: #2a5c8d; margin-right: 5px;"></i> Please have exact change ready for the delivery person.</p>
                        </div>
                    </div>
                </div>

                <div class="checkout-actions">
                    <button type="button" class="btn btn-outline btn-prev-step">Back to Shipping</button>
                    <button type="button" class="btn btn-primary btn-next-step">Review Order</button>
                </div>
            </form>

            <!-- Order Review -->
            <form class="checkout-form" id="review-form" style="display: none;">
                <h2 style="margin-bottom: 20px; color: #2a5c8d;">Review Your Order</h2>

                <div class="review-section" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <h3>Shipping Information</h3>
                        <a href="#" class="edit-link" data-step="0" style="color: #2a5c8d; text-decoration: none;">Edit</a>
                    </div>
                    <div class="review-info" id="shipping-review" style="line-height: 1.8;">
                        <!-- Filled by JavaScript -->
                    </div>
                </div>

                <div class="review-section" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <h3>Payment Method</h3>
                        <a href="#" class="edit-link" data-step="1" style="color: #2a5c8d; text-decoration: none;">Edit</a>
                    </div>
                    <div class="review-info" id="payment-review" style="font-weight: 500;">
                        <!-- Filled by JavaScript -->
                    </div>
                </div>

                <div class="review-section" style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <h3>Order Items</h3>
                    </div>
                    <div class="order-items" id="order-items-container">
                        <!-- Order items will be populated by JavaScript -->
                    </div>
                </div>

                <div class="review-section" style="margin-bottom: 25px;">
                    <h3 style="margin-bottom: 15px;">Order Summary</h3>
                    <div class="order-summary">
                        <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div class="summary-label">Subtotal</div>
                            <div class="summary-value" id="summary-subtotal">$0.00</div>
                        </div>
                        <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div class="summary-label">Shipping</div>
                            <div class="summary-value" id="summary-shipping">Free</div>
                        </div>
                        <div class="summary-row" id="payment-fee-row" style="display: none; justify-content: space-between; margin-bottom: 10px;">
                            <div class="summary-label">Payment Fee</div>
                            <div class="summary-value" id="payment-fee-value">$5.00</div>
                        </div>
                        <div class="summary-row total" style="display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; font-weight: 600; font-size: 18px;">
                            <div class="summary-label">Total</div>
                            <div class="summary-value" id="summary-total">$0.00</div>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group-checkbox" style="margin: 25px 0;">
                    <input type="checkbox" id="terms-agree" required>
                    <label for="terms-agree">I agree to the <a href="#" style="color: #2a5c8d;">Terms and Conditions</a> and <a href="#" style="color: #2a5c8d;">Privacy Policy</a></label>
                    <div class="error-message">You must agree to the terms to proceed</div>
                </div>

                <div class="checkout-actions">
                    <button type="button" class="btn btn-outline btn-prev-step">Back to Payment</button>
                    <button type="submit" class="btn btn-primary" id="submit-order">
                        <span>Place Order</span>
                        <div class="spinner" id="spinner"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Footer -->
<?php include 'footer.html'; ?>

<!-- Hourglass Loading Animation -->
<div class="hourglassBackground" id="hourglassLoader">
    <div class="hourglassContainer">
        <div class="hourglassCurves"></div>
        <div class="hourglassCapTop"></div>
        <div class="hourglassGlassTop"></div>
        <div class="hourglassSand"></div>
        <div class="hourglassSandStream"></div>
        <div class="hourglassCapBottom"></div>
        <div class="hourglassGlass"></div>
    </div>
</div>

<!-- Order Status Notifications -->
<div class="order-notification" id="orderSuccessNotification">
    <div class="notification-icon success">
        <i class="fas fa-check-circle"></i>
    </div>
    <h3 class="notification-title">Order Placed Successfully!</h3>
    <p class="notification-message">Thank you for your purchase. Your order has been received and is being processed. A confirmation email has been sent to your email address.</p>
    <div class="notification-actions">
        <button class="notification-btn primary" onclick="redirectToConfirmation()">View Order Details</button>
        <button class="notification-btn secondary" onclick="closeNotification()">Continue Shopping</button>
    </div>
</div>

<div class="order-notification" id="orderErrorNotification">
    <div class="notification-icon error">
        <i class="fas fa-exclamation-circle"></i>
    </div>
    <h3 class="notification-title">Order Failed</h3>
    <p class="notification-message" id="errorMessage">There was an error processing your order. Please try again or contact support if the problem persists.</p>
    <div class="notification-actions">
        <button class="notification-btn primary" onclick="closeNotification()">Try Again</button>
        <button class="notification-btn secondary" onclick="closeNotification()">Cancel</button>
    </div>
</div>

<script>
// Initialize EmailJS
emailjs.init('jwAK1lWQGhjwf2PL8');

// Initialize Stripe
// Get Stripe publishable key from PHP configuration
const stripe = Stripe('<?php echo getenv("STRIPE_PUBLISHABLE_KEY") ?: "pk_test_51Rvl7rRiB8E6Od2OC3RWdJeSeYdOjNmMaplRhfYzzk9DfZmGZNSXicKE30KtSXs7V5pFTVilTFvY1SDSKQaWgSAi00JGTyAq6n"; ?>');
const elements = stripe.elements();
let cardNumber, cardExpiry, cardCvc, cardPostal;

// API Configuration
        const API_BASE = window.location.origin + '/api';

document.addEventListener('DOMContentLoaded', function() {
    // Scroll to top on page load
    window.scrollTo(0, 0);

    // Initialize cart and wishlist counts
    updateCartCount();
    updateWishlistCount();

    // Fetch cart and wishlist counts from server if user is logged in
    fetchUserData();

    // Get selected items from localStorage (set by cart page) or use all cart items as fallback
    let selectedItems = [];
    try {
        const selectedItemsStr = localStorage.getItem('selectedItems');
        if (selectedItemsStr) {
            selectedItems = JSON.parse(selectedItemsStr);
        }
    } catch (e) {
        console.error('Error parsing selected items from localStorage:', e);
    }

    // If no selected items in localStorage, check URL parameters
    if (selectedItems.length === 0) {
        const urlParams = new URLSearchParams(window.location.search);
        const selectedItemsParam = urlParams.get('selectedItems');

        if (selectedItemsParam) {
            try {
                selectedItems = JSON.parse(decodeURIComponent(selectedItemsParam));
            } catch (e) {
                console.error('Error parsing selected items from URL:', e);
            }
        }
    }

    // Final fallback: if still no selected items, use all cart items
    if (selectedItems.length === 0) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        selectedItems = cart.map(item => item.id);
        console.warn('No selected items found, using all cart items as fallback');
    }

    console.log('Processing selected items:', selectedItems);

    // Multi-step checkout navigation
    const checkoutForms = document.querySelectorAll('.checkout-form');
    let currentStep = 0;

    function showStep(stepIndex) {
        checkoutForms.forEach((form, index) => {
            form.style.display = index === stepIndex ? 'block' : 'none';
        });

        // Initialize Stripe elements when payment step is shown
        if (stepIndex === 1 && !cardNumber) {
            initializeStripeElements();
        }
    }

    // Initialize Stripe Elements with separate fields
    function initializeStripeElements() {
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        // Create separate card elements
        cardNumber = elements.create('cardNumber', { style: style, showIcon: true });
        cardNumber.mount('#card-number-element');

        cardExpiry = elements.create('cardExpiry', { style: style });
        cardExpiry.mount('#card-expiry-element');

        cardCvc = elements.create('cardCvc', { style: style });
        cardCvc.mount('#card-cvc-element');

        cardPostal = elements.create('postalCode', { style: style });
        cardPostal.mount('#card-postal-element');

        // Handle real-time validation errors
        cardNumber.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-number-errors');
            displayError.textContent = event.error ? event.error.message : '';
            displayError.style.display = event.error ? 'block' : 'none';
        });

        cardExpiry.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-expiry-errors');
            displayError.textContent = event.error ? event.error.message : '';
            displayError.style.display = event.error ? 'block' : 'none';
        });

        cardCvc.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-cvc-errors');
            displayError.textContent = event.error ? event.error.message : '';
            displayError.style.display = event.error ? 'block' : 'none';
        });

        cardPostal.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-postal-errors');
            displayError.textContent = event.error ? event.error.message : '';
            displayError.style.display = event.error ? 'block' : 'none';
        });
    }

    // Form validation functions
    function validateShippingForm() {
        let isValid = true;
        const form = document.getElementById('shipping-form');

        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            const errorMessage = field.parentElement.querySelector('.error-message');

            if (!field.value) {
                field.parentElement.classList.add('error');
                isValid = false;
            } else {
                field.parentElement.classList.remove('error');
            }

            // Email validation
            if (field.type === 'email' && field.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(field.value)) {
                    field.parentElement.classList.add('error');
                    errorMessage.textContent = 'Please enter a valid email address';
                    isValid = false;
                }
            }
        });

        return isValid;
    }

    function validatePaymentForm() {
        let isValid = true;
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked')?.value;

        if (!paymentMethod) {
            alert('Please select a payment method');
            return false;
        }

        if (paymentMethod === 'credit-card') {
            const cardholderName = document.getElementById('cardholder-name');

            if (!cardholderName.value.trim()) {
                cardholderName.parentElement.classList.add('error');
                isValid = false;
            } else {
                cardholderName.parentElement.classList.remove('error');
            }

            // Check if Stripe elements are complete
            const cardErrors = document.querySelectorAll('#card-number-errors, #card-expiry-errors, #card-cvc-errors, #card-postal-errors');
            let hasStripeErrors = false;

            cardErrors.forEach(errorEl => {
                if (errorEl.textContent && errorEl.style.display === 'block') {
                    hasStripeErrors = true;
                }
            });

            if (hasStripeErrors) {
                isValid = false;
            }
        }

        return isValid;
    }

    // Next step buttons
    document.querySelectorAll('.btn-next-step').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            let isValid = true;

            if (currentStep === 0) {
                isValid = validateShippingForm();
            } else if (currentStep === 1) {
                isValid = validatePaymentForm();
            }

            if (isValid) {
                if (currentStep === 1) {
                    updateReviewInformation();
                }

                currentStep++;
                showStep(currentStep);
                window.scrollTo(0, 0);
            }
        });
    });

    // Previous step buttons
    document.querySelectorAll('.btn-prev-step').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentStep--;
            showStep(currentStep);
            window.scrollTo(0, 0);
        });
    });

    // Edit links
    document.querySelectorAll('.edit-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const step = parseInt(this.getAttribute('data-step'));
            currentStep = step;
            showStep(currentStep);
            window.scrollTo(0, 0);
        });
    });

    // Add scroll-to-top when payment method is selected
    document.querySelectorAll('input[name="payment-method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Scroll to top when payment method is selected
            window.scrollTo(0, 0);
        });
    });

    // Update review information
    function updateReviewInformation() {
        // Shipping information
        const shippingInfo = `
            ${document.getElementById('shipping-email').value}<br>
            ${document.getElementById('shipping-first-name').value} ${document.getElementById('shipping-last-name').value}<br>
            ${document.getElementById('shipping-address').value}<br>
            ${document.getElementById('shipping-address2').value ? document.getElementById('shipping-address2').value + '<br>' : ''}
            ${document.getElementById('shipping-city').value}, ${document.getElementById('shipping-state').value} ${document.getElementById('shipping-zip').value}<br>
            ${document.getElementById('shipping-country').value}<br>
            ${document.getElementById('shipping-phone').value}
        `;
        document.getElementById('shipping-review').innerHTML = shippingInfo;

        // Payment method
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked');
        let paymentInfo = '';

        if (paymentMethod) {
            if (paymentMethod.value === 'credit-card') {
                // Show placeholder for card info (actual last four digits will be available during order placement)
                paymentInfo = 'Credit/Debit Card';
            } else if (paymentMethod.value === 'cash-on-delivery') {
                paymentInfo = 'Pay on Delivery (+$5.00 fee)';
            }
        }

        document.getElementById('payment-review').textContent = paymentInfo;

        // Update order items display
        updateOrderItemsDisplay();

        // Update order summary with actual values from selected items
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const selectedCartItems = cart.filter(item => selectedItems.includes(item.id));
        const subtotal = selectedCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        let shippingCost = 0; // Free shipping for now
        let paymentFee = 0;

        // Add payment fee for cash on delivery
        if (paymentMethod && paymentMethod.value === 'cash-on-delivery') {
            paymentFee = 5.00;
            document.getElementById('payment-fee-row').style.display = 'flex';
            document.getElementById('payment-fee-value').textContent = `$${paymentFee.toFixed(2)}`;
        } else {
            document.getElementById('payment-fee-row').style.display = 'none';
        }

        const total = subtotal + shippingCost + paymentFee;

        document.getElementById('summary-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('summary-shipping').textContent = `$${shippingCost.toFixed(2)}`;
        document.getElementById('summary-total').textContent = `$${total.toFixed(2)}`;
    }

    // Function to display order items
    function updateOrderItemsDisplay() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const selectedCartItems = cart.filter(item => selectedItems.includes(item.id));
        const orderItemsContainer = document.getElementById('order-items-container');

        orderItemsContainer.innerHTML = '';

        if (selectedCartItems.length === 0) {
            orderItemsContainer.innerHTML = '<p style="padding: 20px; text-align: center; color: #666;">No items in cart</p>';
            return;
        }

        selectedCartItems.forEach(item => {
            const orderItem = document.createElement('div');
            orderItem.className = 'order-item';

            orderItem.innerHTML = `
                <div class="item-image">
                    <img src="${item.image}" alt="${item.title}">
                </div>
                <div class="item-details">
                    <div class="item-name">${item.title}</div>
                    ${item.size ? `<div class="item-variants">Size: ${item.size}</div>` : ''}
                    ${item.color ? `<div class="item-variants">Color: ${item.color}</div>` : ''}
                    <div class="item-price">$${item.price.toFixed(2)} <span class="item-quantity">× ${item.quantity}</span></div>
                </div>
                <div class="item-total">$${(item.price * item.quantity).toFixed(2)}</div>
            `;

            orderItemsContainer.appendChild(orderItem);
        });
    }

    // Function to send order email to customer only
    async function sendOrderEmail(order) {
        try {
            console.log('Preparing to send email for order:', order);

            // Format items for email
            const itemsHtml = order.items.map(item =>
                `<tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">${item.title}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;">${item.quantity}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right;">$${item.price.toFixed(2)}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right;">$${(item.price * item.quantity).toFixed(2)}</td>
                </tr>`
            ).join('');

            // Prepare email parameters for customer
            const customerEmailParams = {
                to_email: order.shippingAddress.email, // This should send to customer's email
                to_name: order.shippingAddress.name,
                from_name: 'FashionHub',
                from_email: 'saadedits95@gmail.com', // Your sending email
                reply_to: 'support@fashionhub.com', // Where replies should go
                order_id: dbResponse.order_number, // Use the same order number for consistency
                customer_name: order.shippingAddress.name,
                customer_email: order.shippingAddress.email,
                customer_phone: order.shippingAddress.phone,
                shipping_address: `
                    ${order.shippingAddress.name}<br>
                    ${order.shippingAddress.address}<br>
                    ${order.shippingAddress.address2 ? order.shippingAddress.address2 + '<br>' : ''}
                    ${order.shippingAddress.city}, ${order.shippingAddress.state} ${order.shippingAddress.zip}<br>
                    ${order.shippingAddress.country}
                `,
                payment_method: order.lastFourDigits && order.paymentMethod.includes('Credit Card')
                    ? `Credit/Debit Card ending in ${order.lastFourDigits}`
                    : order.paymentMethod,
                payment_status: order.paymentStatus,
                order_date: new Date(order.date).toLocaleDateString(),
                subtotal: `$${order.subtotal.toFixed(2)}`,
                shipping: `$${order.shipping.toFixed(2)}`,
                payment_fee: `$${order.paymentFee.toFixed(2)}`,
                total: `$${order.total.toFixed(2)}`,
                items: itemsHtml
            };

            console.log('Customer email params:', customerEmailParams);

            // Send email to customer using EmailJS
            const customerResponse = await emailjs.send(
                'service_jsn6z3m', // Your EmailJS service ID
                'template_edmpgzi', // Your EmailJS template ID
                customerEmailParams
            );

            console.log('Customer email successfully sent:', customerResponse);

            return true;
        } catch (error) {
            console.error('Email sending failed:', {
                status: error.status,
                text: error.text,
                message: error.message
            });
            return false;
        }
    }

    // Process payment with Stripe
    async function processStripePayment(paymentMethodId, amount, order) {
        try {
            console.log('Processing payment with Stripe:', {
                paymentMethodId: paymentMethodId,
                amount: amount,
                currency: 'usd'
            });

            // For demo purposes, simulate successful payment processing
            // In a production environment, you would send this to your server
            // to create a PaymentIntent and confirm the payment

            // Simulate processing delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            // Return mock payment intent data
            const mockPaymentIntent = {
                id: 'pi_' + Date.now(),
                status: 'succeeded',
                amount: Math.round(amount * 100),
                currency: 'usd',
                payment_method: paymentMethodId,
                client_secret: 'pi_' + Date.now() + '_secret_' + Math.random().toString(36).substring(2)
            };

            console.log('Payment processed successfully:', mockPaymentIntent);
            return mockPaymentIntent;
        } catch (error) {
            console.error('Payment processing error:', error);
            throw error;
        }
    }

    // Function to save order to database
    async function saveOrderToDatabase(orderData) {
        try {
            const response = await fetch(`${API_BASE}/create_order.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify(orderData)
            });

            const data = await response.json();

            if (data.status === 'success') {
                console.log('Order saved to database successfully:', data);
                return data;
            } else {
                console.error('Error saving order to database:', data.message);
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error saving order to database:', error);
            throw error;
        }
    }

    // Function to remove purchased items from database cart
    async function removePurchasedItemsFromDatabase(selectedItems) {
        try {
            const response = await fetch(`${API_BASE}/remove_purchased_items.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify({
                    product_ids: selectedItems
                })
            });

            const data = await response.json();

            if (data.status === 'success') {
                console.log('Successfully removed purchased items from database cart');
                return true;
            } else {
                console.error('Error removing purchased items from database:', data.message);
                return false;
            }
        } catch (error) {
            console.error('Error removing purchased items from database:', error);
            return false;
        }
    }

    // Place order
    document.getElementById('review-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Scroll to top when placing order
        window.scrollTo(0, 0);

        const termsAgree = document.getElementById('terms-agree');
        const termsError = termsAgree.parentElement.querySelector('.error-message');

        if (!termsAgree.checked) {
            termsAgree.parentElement.classList.add('error');
            termsError.style.display = 'block';
            return;
        } else {
            termsAgree.parentElement.classList.remove('error');
            termsError.style.display = 'none';
        }

        // Show hourglass loading animation
        const submitButton = document.getElementById('submit-order');
        const spinner = document.getElementById('spinner');
        const hourglassLoader = document.getElementById('hourglassLoader');

        submitButton.disabled = true;
        spinner.style.display = 'inline-block';
        hourglassLoader.classList.add('active');

        // Prevent user from closing the page
        window.onbeforeunload = function() {
            return "Your order is being processed. Please wait...";
        };

        try {
            // Get the selected payment method
            const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const selectedCartItems = cart.filter(item => selectedItems.includes(item.id));

            // Calculate total amount
            const subtotal = selectedCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            let paymentFee = 0;

            if (paymentMethod === 'cash-on-delivery') {
                paymentFee = 5.00;
            }

            const total = subtotal + paymentFee;

            // Create order object - using the email from the shipping form
            const order = {
                id: Date.now(), // Temporary ID, will be replaced by database ID
                date: new Date().toISOString(),
                items: selectedCartItems.map(item => ({
                    id: item.id, // Store product ID
                    title: item.title, // Store actual product name
                    size: item.size || 'N/A',
                    color: item.color || 'N/A',
                    quantity: item.quantity || 1,
                    price: item.price || 0,
                    image: item.image, // Store product image
                    timestamp: item.timestamp || new Date().toISOString() // Add timestamp for sorting
                })),
                subtotal: subtotal,
                shipping: 0,
                paymentFee: paymentFee,
                total: total,
                status: paymentMethod === 'cash-on-delivery' ? 'pending' : 'processing',
                paymentMethod: paymentMethod === 'cash-on-delivery' ? 'Cash on Delivery' : 'Credit Card',
                paymentStatus: paymentMethod === 'cash-on-delivery' ? 'pending' : 'completed',
                lastFourDigits: null, // Will be set during credit card processing
                shippingAddress: {
                    name: `${document.getElementById('shipping-first-name').value} ${document.getElementById('shipping-last-name').value}`,
                    email: document.getElementById('shipping-email').value, // Use email from form
                    address: document.getElementById('shipping-address').value,
                    address2: document.getElementById('shipping-address2').value || '',
                    city: document.getElementById('shipping-city').value,
                    state: document.getElementById('shipping-state').value,
                    zip: document.getElementById('shipping-zip').value,
                    country: document.getElementById('shipping-country').value,
                    phone: document.getElementById('shipping-phone').value
                }
            };

            let paymentMethodId = null;
            let lastFourDigits = null;

            if (paymentMethod === 'credit-card') {
                // Create payment method with Stripe
                const { paymentMethod: stripePaymentMethod, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardNumber,
                    billing_details: {
                        name: document.getElementById('cardholder-name').value,
                        email: document.getElementById('shipping-email').value, // Use email from form
                        phone: document.getElementById('shipping-phone').value,
                        address: {
                            line1: document.getElementById('shipping-address').value,
                            line2: document.getElementById('shipping-address2').value || '',
                            city: document.getElementById('shipping-city').value,
                            state: document.getElementById('shipping-state').value,
                            postal_code: document.getElementById('shipping-zip').value,
                            country: document.getElementById('shipping-country').value,
                        }
                    }
                });

                if (error) {
                    const errorElement = document.getElementById('card-number-errors');
                    errorElement.textContent = error.message;
                    errorElement.style.display = 'block';
                    throw error;
                }

                paymentMethodId = stripePaymentMethod.id;

                // Store the last four digits for display
                lastFourDigits = stripePaymentMethod.card.last4;

                // Update order object with last four digits
                order.lastFourDigits = lastFourDigits;

                // Process payment with Stripe
                await processStripePayment(paymentMethodId, total, order);
            }

            // Save order to database
            const orderData = {
                items: order.items,
                total_amount: order.total,
                payment_method: order.paymentMethod,
                payment_status: order.paymentStatus,
                status: order.status,
                shipping_address: order.shippingAddress,
                billing_address: order.shippingAddress // Using same as shipping for now
            };

            const dbResponse = await saveOrderToDatabase(orderData);

            // Send order confirmation email to customer only
            const emailSent = await sendOrderEmail(order);
            if (!emailSent) {
                console.warn('Failed to send order confirmation email');
            }

            // Check if user is logged in
            const auth = {
                currentUser: JSON.parse(localStorage.getItem('currentUser')) || null
            };

            // Remove purchased items from database if user is logged in
            if (auth.currentUser) {
                const dbSuccess = await removePurchasedItemsFromDatabase(selectedItems);
                if (!dbSuccess) {
                    console.warn('Failed to remove items from database cart, but order was still placed');
                }
            }

            // Remove only the purchased items from localStorage cart
            const updatedCart = cart.filter(item => !selectedItems.includes(item.id));
            localStorage.setItem('cart', JSON.stringify(updatedCart));
            updateCartCount();

            // Clear the selected items from localStorage
            localStorage.removeItem('selectedItems');

            // Store order data for confirmation page
            const orderDataForConfirmation = {
                ...order,
                id: dbResponse.order_number, // Use backend-generated order number as the main ID
                order_number: dbResponse.order_number,
                order_id: dbResponse.order_id,
                // Ensure the order number is properly set for consistency
                orderNumber: dbResponse.order_number,
                // Add debug info to track order ID consistency
                debug: {
                    original_id: order.id,
                    database_order_number: dbResponse.order_number,
                    database_order_id: dbResponse.order_id,
                    timestamp: new Date().toISOString()
                }
            };

            // Debug logging
            console.log('Checkout - Order data for confirmation:', orderDataForConfirmation);
            console.log('Checkout - Database response:', dbResponse);

            // Store order data in sessionStorage for the confirmation page
            sessionStorage.setItem('pendingOrderData', JSON.stringify(orderDataForConfirmation));

            // Hide hourglass and show success notification
            hourglassLoader.classList.remove('active');
            setTimeout(() => {
                document.getElementById('orderSuccessNotification').classList.add('active');
            }, 500);
        } catch (error) {
            console.error('Payment error:', error);

            // Hide hourglass and show error notification
            hourglassLoader.classList.remove('active');
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = error.message || 'There was an error processing your order. Please try again or contact support if the problem persists.';
            setTimeout(() => {
                document.getElementById('orderErrorNotification').classList.add('active');
            }, 500);
        } finally {
            // Hide loading spinner
            submitButton.disabled = false;
            spinner.style.display = 'none';
            // Remove page unload protection
            window.onbeforeunload = null;
        }
    });

    // Initialize first step
    showStep(0);

    // Load cart data if available
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0 && window.location.pathname.includes('checkout.html')) {
        window.location.href = 'cart.html';
    }

    // Optional: Add highlight effect to the continue button after a short delay
    setTimeout(function() {
        const nextButton = document.querySelector('.btn-next-step');
        if (nextButton) {
            nextButton.style.animation = 'pulse 2s infinite';
        }
    }, 1000);
});

// Fetch user data from server if logged in
async function fetchUserData() {
    try {
        const response = await fetch(`${API_BASE}/check_auth.php`, {
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status === 'success') {
                console.log('User is logged in:', data.user.name);

                // Fetch cart count from server
                await fetchCartCount();

                // Fetch wishlist count from server
                await fetchWishlistCount();

                return data.user;
            }
        }
    } catch (error) {
        console.error('Error checking auth:', error);
    }
    return null;
}

// Fetch cart count from server
async function fetchCartCount() {
    try {
        const response = await fetch(`${API_BASE}/get_cart.php`, {
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status === 'success') {
                const cartCount = data.cart.reduce((total, item) => total + item.quantity, 0);
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = cartCount;
                    el.style.display = cartCount > 0 ? 'flex' : 'none';
                });
                console.log('Cart count updated from server:', cartCount);
            }
        }
    } catch (error) {
        console.error('Error fetching cart count:', error);
        // Fallback to localStorage
        updateCartCount();
    }
}

// Fetch wishlist count from server
async function fetchWishlistCount() {
    try {
        const response = await fetch(`${API_BASE}/get_wishlist_count.php`, {
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status === 'success') {
                const wishlistCount = data.count;
                document.querySelectorAll('.wishlist-count').forEach(el => {
                    el.textContent = wishlistCount;
                    el.style.display = wishlistCount > 0 ? 'flex' : 'none';
                });
                console.log('Wishlist count updated from server:', wishlistCount);
            }
        }
    } catch (error) {
        console.error('Error fetching wishlist count:', error);
        // Fallback to localStorage
        updateWishlistCount();
    }
}

// Update cart count in header (localStorage fallback)
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = cartCount;
        el.style.display = cartCount > 0 ? 'flex' : 'none';
    });
}

// Update wishlist count in header (localStorage fallback)
function updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const wishlistCount = wishlist.length;
    document.querySelectorAll('.wishlist-count').forEach(el => {
        el.textContent = wishlistCount;
        el.style.display = wishlistCount > 0 ? 'flex' : 'none';
    });
}

// Notification functions
function closeNotification() {
    document.getElementById('orderSuccessNotification').classList.remove('active');
    document.getElementById('orderErrorNotification').classList.remove('active');
}

function redirectToConfirmation() {
    const orderData = sessionStorage.getItem('pendingOrderData');
    if (orderData) {
        const orderDataEncoded = encodeURIComponent(orderData);
        window.location.href = 'order-confirmation.php?order=' + orderDataEncoded;
    } else {
        window.location.href = 'index.php';
    }
}

// Close notifications when clicking outside
document.addEventListener('click', function(event) {
    const successNotification = document.getElementById('orderSuccessNotification');
    const errorNotification = document.getElementById('orderErrorNotification');

    if (event.target === successNotification || event.target === errorNotification) {
        closeNotification();
    }
});

// Close notifications with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeNotification();
    }
});
</script>
</body>
</html>
   
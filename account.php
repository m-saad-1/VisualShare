<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - My Account</title>
    <link rel="stylesheet" href="header-footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Loading Animation Styles */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
            width: 100%;
        }
        
        .dot-spinner {
            --uib-size: 2.8rem;
            --uib-speed: .9s;
            --uib-color: #d4a762;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: var(--uib-size);
            width: var(--uib-size);
        }

        .dot-spinner__dot {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
            width: 100%;
        }

        .dot-spinner__dot::before {
            content: '';
            height: 20%;
            width: 20%;
            border-radius: 50%;
            background-color: var(--uib-color);
            transform: scale(0);
            opacity: 0.5;
            animation: pulse0112 calc(var(--uib-speed) * 1.111) ease-in-out infinite;
            box-shadow: 0 0 20px rgba(212, 167, 98, 0.3);
        }

        .dot-spinner__dot:nth-child(2) {
            transform: rotate(45deg);
        }

        .dot-spinner__dot:nth-child(2)::before {
            animation-delay: calc(var(--uib-speed) * -0.875);
        }

        .dot-spinner__dot:nth-child(3) {
            transform: rotate(90deg);
        }

        .dot-spinner__dot:nth-child(3)::before {
            animation-delay: calc(var(--uib-speed) * -0.75);
        }

        .dot-spinner__dot:nth-child(4) {
            transform: rotate(135deg);
        }

        .dot-spinner__dot:nth-child(4)::before {
            animation-delay: calc(var(--uib-speed) * -0.625);
        }

        .dot-spinner__dot:nth-child(5) {
            transform: rotate(180deg);
        }

        .dot-spinner__dot:nth-child(5)::before {
            animation-delay: calc(var(--uib-speed) * -0.5);
        }

        .dot-spinner__dot:nth-child(6) {
            transform: rotate(225deg);
        }

        .dot-spinner__dot:nth-child(6)::before {
            animation-delay: calc(var(--uib-speed) * -0.375);
        }

        .dot-spinner__dot:nth-child(7) {
            transform: rotate(270deg);
        }

        .dot-spinner__dot:nth-child(7)::before {
            animation-delay: calc(var(--uib-speed) * -0.25);
        }

        .dot-spinner__dot:nth-child(8) {
            transform: rotate(315deg);
        }

        .dot-spinner__dot:nth-child(8)::before {
            animation-delay: calc(var(--uib-speed) * -0.125);
        }

        @keyframes pulse0112 {
            0%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Additional styles for account page */
        .account-page {
            padding: 60px 0;
        }
        
        .account-content {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }
        
        .account-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .account-main {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .account-user {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-info h3 {
            margin: 0;
            font-size: 1.1rem;
        }
        
        .user-info p {
            margin: 5px 0 0;
            color: #777;
            font-size: 0.9rem;
        }
        
        .account-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .account-menu li {
            margin-bottom: 5px;
        }
        
        .account-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            color: #333;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .account-menu a:hover {
            background: #f9f9f9;
            color: #d4a762;
        }
        
        .account-menu a i {
            width: 20px;
            text-align: center;
        }
        
        .account-menu .active a {
            background: rgba(212, 167, 98, 0.1);
            color: #d4a762;
            font-weight: 500;
        }
        
        .account-tab {
            display: none;
        }
        
        .account-tab.active {
            display: block;
        }
        
        .account-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            background: #d4a762;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-number {
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #777;
        }

        @media (max-width: 768px) {
            .stat-card {
                padding: 10px;
                gap: 10px;
            }

            .stat-icon {
                width: 35px;
                height: 35px;
            }

            .stat-number {
                font-size: 1.1rem;
            }

            .stat-label {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .stat-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .stat-icon {
                width: 30px;
                height: 30px;
            }

            .stat-number {
                font-size: 1rem;
            }

            .stat-label {
                font-size: 0.7rem;
            }
        }
        
        /* ORDERS STYLING */
        .orders-container {
            margin-top: 20px;
        }
        
        .order-card {
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f9f9f9;
            border-bottom: 1px solid #eee;
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-number {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2a2a2a;
        }
        
        .order-date {
            color: #777;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .order-status {
            margin-left: 15px;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.processing {
            background-color: #fff8e1;
            color: #ff9800;
        }
        
        .status-badge.completed {
            background-color: #e6f7ee;
            color: #4caf50;
        }
        
        .status-badge.shipped {
            background-color: #e3f2fd;
            color: #2196f3;
        }
        
        .status-badge.cancelled {
            background-color: #ffebee;
            color: #f44336;
        }
        
        .order-items {
            padding: 15px 20px;
        }
        
        .order-item-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .order-item-preview:last-child {
            margin-bottom: 0;
        }
        
        .order-item-preview .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .item-info {
            flex: 1;
        }
        
        .item-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .item-quantity {
            color: #777;
            font-size: 0.9rem;
        }
        
        .more-items {
            text-align: center;
            padding: 10px;
            color: #777;
            font-size: 0.9rem;
            background: #f9f9f9;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-total {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2a2a2a;
            flex-shrink: 0;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .order-fee-note {
            font-size: 0.85rem;
            color: #777;
            margin: 0;
            flex-basis: 100%;
            order: 2;
        }
        
        .order-details {
            padding: 0 20px 20px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }
        
        .details-section {
            margin-bottom: 20px;
        }
        
        .details-section:last-child {
            margin-bottom: 0;
        }
        
        .details-section h5 {
            margin: 0 0 10px 0;
            font-size: 1rem;
            color: #2a2a2a;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .detail-item .item-info {
            flex: 1;
        }
        
        .item-variant {
            color: #777;
            font-size: 0.9rem;
            margin: 3px 0;
        }
        
        .item-price {
            color: #777;
            font-size: 0.9rem;
        }
        
        .item-total {
            font-weight: 600;
            color: #2a2a2a;
        }
        
        .order-summary {
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-row.total {
            font-weight: 600;
            font-size: 1.1rem;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .payment-status {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .payment-status.paid {
            background-color: #e6f7ee;
            color: #4caf50;
        }
        
        .payment-status.pending {
            background-color: #fff8e1;
            color: #ff9800;
        }
        
        .payment-status.failed {
            background-color: #ffebee;
            color: #f44336;
        }
        
        .empty-orders {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .empty-orders i {
            font-size: 3rem;
            color: #d4a762;
            margin-bottom: 20px;
        }
        
        .empty-orders h3 {
            margin-bottom: 10px;
            color: #2a2a2a;
        }
        
        .empty-orders p {
            color: #777;
            margin-bottom: 20px;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .orders-table th {
            font-weight: 500;
            color: #777;
        }
        
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status.completed {
            background: #e6f7ee;
            color: #4caf50;
        }
        
        .status.processing {
            background: #fff8e1;
            color: #ff9800;
        }
        
        .status.cancelled {
            background: #ffebee;
            color: #f44336;
        }
        
        .address-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .address-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
        }
        
        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .address-header h3 {
            margin: 0;
            font-size: 1.1rem;
        }
        
        .edit-link {
            color: #d4a762;
            font-size: 0.9rem;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        #account .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        #account .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        #account .form-group input:focus {
            outline: none;
            border-color: #d4a762;
            box-shadow: 0 0 0 2px rgba(212, 167, 98, 0.2);
        }

        .form-row .form-group {
            flex: 1;
        }
        
        .form-note {
            font-size: 0.85rem;
            color: #777;
            margin-top: 5px;
        }
        
        .not-logged-in {
            text-align: center;
            padding: 40px 20px;
        }
        
        .not-logged-in h2 {
            margin-bottom: 20px;
        }
        
        .not-logged-in p {
            margin-bottom: 30px;
            color: #777;
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Password field styling - FIXED */
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            z-index: 2; /* Ensure it's above the input */
            height: 20px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Account Details Tab */
        #account .form-group input[type="password"] {
            padding-right: 40px; /* Make space for the eye icon */
        }

        /* Wishlist Product Grid - Improved Styling */
        #wishlist .product-badge {
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

        #wishlist .product-badge.new {
            background-color: var(--success-color);
        }

        #wishlist .product-badge.best-seller {
            background-color: var(--secondary-color);
        }
        
        #wishlist .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        #wishlist .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #wishlist .product-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        #wishlist .product-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        #wishlist .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        #wishlist .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        #wishlist .product-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: 100%; /* Ensure full width */
        }

        #wishlist .product-title {
            font-size: 1rem;
            margin: 0 0 8px 0;
            color: #2a2a2a;
            line-height: 1.4;
            font-weight: 500;
            width: 100%; /* Full width title */
            word-wrap: break-word; /* Handle long titles */
        } 

        #wishlist .price-container {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 8px;
            margin: 5px 0;
            width: 100%; /* Full width container */
        }

        #wishlist .current-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: #d4a762;
            margin-right: 8px;
        }

        #wishlist .old-price {
            text-decoration: line-through;
            color: #777;
            font-size: 0.9rem;
            margin-right: 8px;
        }

        #wishlist .discount {
            background-color: #e53935;
            color: white;
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 4px;
            margin-right: 0; /* Remove right margin to push to edge */
        }

        #wishlist .rating-container {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
            width: 100%; /* Full width rating */
        }

        #wishlist .stars {
            color: #d4a762;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        #wishlist .review-count {
            color: #777;
            font-size: 0.85rem;
            margin-left: 5px;
        }

        #wishlist .product-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #777;
            margin-top: auto;
        }

        #wishlist .review-count {
            font-size: 0.85rem;
        }

        #wishlist .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            background-color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: center;
            gap: 8px;
            opacity: 0;
            transition: all 0.3s ease;
            min-width: -moz-min-content; /* Firefox support */
            min-width: min-content; /* Prevent container from forcing stretch */
        }
        #wishlist .product-card:hover .product-actions {
            bottom: 0;
            opacity: 1;
        }

        #wishlist .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #d4a762;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 0 0 36px; /* Prevent growing/shrinking */
            padding: 0;
        }

        #wishlist .action-btn i {
            font-size: 14px;
            display: inline-flex; /* Better icon alignment */
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        #wishlist .action-btn:hover {
            background-color: #2a2a2a;
            transform: none; /* Remove if you have transform here */
        }

        #wishlist .empty-wishlist {
            text-align: center;
            padding: 40px 20px;
            grid-column: 1 / -1;
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        #wishlist .empty-wishlist i {
            font-size: 3rem;
            color: #d4a762;
            margin-bottom: 20px;
        }

        #wishlist .empty-wishlist h3 {
            margin-bottom: 10px;
            color: #2a2a2a;
            font-size: 1.5rem;
        }

        #wishlist .empty-wishlist p {
            color: #777;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        /* Confirmation Modal */
        .confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .confirmation-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .confirmation-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .confirmation-content h3 {
            margin-top: 0;
            color: #2a2a2a;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .confirmation-buttons button {
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .confirm-btn {
            background-color: #e53935;
            color: white;
            border: none;
        }

        .cancel-btn {
            background-color: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }

        .payment-methods {
            display: flex;
            align-items: center;
            gap: 15px;
            size: 1.2rem;
            font-size: 1.8rem;
        }

        /* Product Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .product-modal {
            background-color: white;
            border-radius: 8px;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transform: translateY(50px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .product-modal {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
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
            color: #777;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            color: #d4a762;
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
            color: #d4a762;
        }

        .modal-old-price {
            text-decoration: line-through;
            color: #777;
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .modal-discount {
            background-color: #d4a762;
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .modal-description {
            color: #777;
            line-height: 1.7;
        }

        .modal-features {
            margin-top: 10px;
        }

        .modal-features ul {
            list-style-position: inside;
            margin-top: 5px;
            padding-left: 0;
        }

        .modal-features li {
            margin-bottom: 5px;
            color: #777;
        }

        .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .size-btn {
            padding: 8px 15px;
            border: 1px solid #eee;
            background: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .size-btn:hover,
        .size-btn.active {
            background-color: #d4a762;
            color: white;
            border-color: #d4a762;
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
            transition: all 0.3s ease;
        }

        .color-btn::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 1px solid #eee;
            border-radius: 50%;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .color-btn:hover::after,
        .color-btn.active::after {
            opacity: 1;
            border-color: #d4a762;
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
            background-color: #f9f9f9;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #d4a762;
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #c49555;
        }

        .btn-outline {
            background-color: transparent;
            color: #d4a762;
            border: 1px solid #d4a762;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-outline:hover {
            background-color: rgba(212, 167, 98, 0.1);
            color: #d4a762;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            color: #777;
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
            background-color: #d4a762;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
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
            font-weight: 500;
        }

        .account-main {
            scroll-margin-top: 20px; /* Adjust as needed */
        }

        .account-content {
            scroll-behavior: smooth;
        }

        /* Address Modal Styles */
        .address-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .address-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .address-modal-content {
            background: white;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .address-modal.active .address-modal-content {
            transform: translateY(0);
        }

        .address-modal .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
        }

        .address-modal .modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .address-modal #addressForm {
            padding: 20px;
        }

        .address-modal .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .address-modal select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .address-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .address-actions .btn {
            flex: 1;
            min-width: 200px;
        }

        @media (max-width: 576px) {
            .address-actions {
                flex-direction: column;
            }
            
            .address-actions .btn {
                width: 100%;
            }
        }

        /* Cancel order button */
        .cancel-order-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .cancel-order-btn:hover {
            background-color: #d32f2f;
        }

        .cancel-note {
            font-size: 0.85rem;
            color: #777;
            margin: 8px 0 0 0;
            font-style: italic;
            padding: 8px 12px;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            text-align: center;
            width: 100%;
        }

        .cancel-order-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            align-self: flex-start;
        }

        .cancel-order-btn:hover {
            background-color: #d32f2f;
        }

        .reorder-btn {
            background-color: #d4a762;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .reorder-btn:hover {
            background-color: #c49555;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            #wishlist .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .account-content {
                flex-direction: column;
            }
            
            .account-sidebar {
                width: 100%;
            }
            
            .account-sidebar .account-menu ul {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }
            
            .account-sidebar .account-menu li {
                margin-bottom: 0;
            }
            
            .account-sidebar .account-menu a {
                padding: 8px 12px;
                border-radius: 20px;
                background: rgba(212, 167, 98, 0.1);
                font-size: 0.9rem;
                white-space: nowrap;
            }
            
            .account-sidebar .account-menu .active a {
                background: var(--secondary-color);
                color: white;
            }
            
            .address-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .order-footer {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .order-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            #wishlist .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .modal-content {
                flex-direction: column;
            }
            
            .modal-image {
                min-height: 250px;
            }
        }

        @media (max-width: 576px) {
            #wishlist .product-grid {
                grid-template-columns: 1fr;
            }
            
            #wishlist .product-image {
                height: 200px;
            }
            
            .modal-header {
                padding: 15px;
            }
            
            .modal-content {
                padding: 15px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }
        
        .order-actions .btn,
        .order-actions .btn-outline,
        .order-actions .btn-primary {
            transition: none !important;
        }

        .order-actions .btn-outline:hover,
        .order-actions .reorder-btn:hover {
            background-color: var(--secondary-color);
            color: white !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'header.html'; ?>

    <main class="account-page">
        <div class="container">
            <div class="page-header">
                <h1>My Account</h1>
                <div class="breadcrumbs">
                    <a href="index.html">Home</a> / <span>My Account</span>
                </div>
            </div>
            
            <!-- Loading Animation Container -->
            <div id="loading-container" class="loading-container">
                <div class="dot-spinner">
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                </div>
            </div>
            
            <div id="account-content-container" style="display: none;">
                <!-- Content will be loaded dynamically based on auth status -->
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'footer.html'; ?>

    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <h3>Remove from Wishlist</h3>
            <p>Are you sure you want to remove this item from your wishlist?</p>
            <div class="confirmation-buttons">
                <button class="confirm-btn" id="confirmRemove">Remove</button>
                <button class="cancel-btn" id="cancelRemove">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal-overlay" id="productModal">
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
                    <p class="modal-description" id="modalProductDescription">
                        This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. 
                        The tailored fit provides a modern silhouette while allowing freedom of movement. 
                        Perfect for both casual and business casual occasions.
                    </p>
                    <div class="modal-features">
                        <h4>Features:</h4>
                        <ul id="modalProductFeatures">
                            <li>100% Organic Cotton</li>
                            <li>Button-down collar</li>
                            <li>Single chest pocket</li>
                            <li>Tailored fit</li>
                            <li>Machine washable</li>
                        </ul>
                    </div>
                    <div class="size-selection">
                        <h4>Size:</h4>
                        <div class="size-options" id="modalSizeOptions">
                            <!-- Size options will be loaded via JavaScript -->
                        </div>
                    </div>
                    <div class="color-selection">
                        <h4>Color:</h4>
                        <div class="color-options" id="modalColorOptions">
                            <!-- Color options will be loaded via JavaScript -->
                        </div>
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
    </div>

    <!-- Address Modal -->
    <div class="address-modal" id="addressModal">
        <div class="address-modal-content">
            <div class="modal-header">
                <h3 id="addressModalTitle">Edit Address</h3>
                <button class="close-modal" id="closeAddressModal">&times;</button>
            </div>
            <form id="addressForm">
                <input type="hidden" id="addressType" value="">
                
                <div class="form-group">
                    <label for="addressName">Full Name</label>
                    <input type="text" id="addressName" required>
                </div>
                
                <div class="form-group">
                    <label for="addressStreet">Street Address</label>
                    <input type="text" id="addressStreet" required>
                </div>
                
                <div class="form-group">
                    <label for="addressCity">City</label>
                    <input type="text" id="addressCity" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="addressState">State/Province</label>
                        <input type="text" id="addressState" required>
                    </div>
                    <div class="form-group">
                        <label for="addressZip">ZIP/Postal Code</label>
                        <input type="text" id="addressZip" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="addressCountry">Country</label>
                    <select id="addressCountry" required>
                        <option value="">Select Country</option>
                        <option value="US">United States</option>
                        <option value="UK">United Kingdom</option>
                        <option value="PK">Pakistan</option>
                        <option value="CA">Canada</option>
                        <option value="AU">Australia</option>
                        <option value="DE">Germany</option>
                        <option value="FR">France</option>
                        <option value="IT">Italy</option>
                        <option value="ES">Spain</option>
                        <option value="JP">Japan</option>
                        <option value="CN">China</option>
                        <option value="BR">Brazil</option>
                        <option value="IN">India</option>
                        <option value="RU">Russia</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Address</button>
                    <button type="button" class="btn btn-outline" id="cancelAddress">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ==============================================
        // LOADING STATE MANAGEMENT
        // ==============================================
        function showLoading() {
            document.getElementById('loading-container').style.display = 'flex';
            document.getElementById('account-content-container').style.display = 'none';
        }
        
        function hideLoading() {
            document.getElementById('loading-container').style.display = 'none';
            document.getElementById('account-content-container').style.display = 'block';
        }

        // ==============================================
        // PRODUCT DATA
        // ==============================================
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
                featured: true,
                newArrival: false,
                sku: "FH-001",
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
                featured: false,
                newArrival: true,
                sku: "FH-002",
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
                badge: null,
                featured: true,
                newArrival: false,
                sku: "FH-003",
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
                featured: false,
                newArrival: false,
                sku: "FH-004",
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
            },
            {
                id: 5,
                title: "Leather Crossbody Bag",
                category: "accessories",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1543076447-215ad9ba6923?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "beige"],
                sizes: ["One Size"],
                rating: 4.5,
                reviews: 27,
                badge: null,
                featured: true,
                newArrival: false,
                sku: "FH-005",
                description: "This stylish crossbody bag is crafted from genuine leather that develops a beautiful patina over time. The compact design fits all your essentials while keeping your hands free. Features multiple compartments for organization and an adjustable strap for comfort.",
                features: [
                    "Genuine leather",
                    "Adjustable crossbody strap",
                    "Main zip compartment",
                    "Interior slip pocket",
                    "Exterior back zip pocket"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 6,
                title: "Premium Wool Coat",
                category: "jackets",
                price: 129.99,
                image: "https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["black", "beige"],
                sizes: ["S", "M", "L"],
                rating: 4.0,
                reviews: 15,
                badge: "new",
                featured: false,
                newArrival: true,
                sku: "FH-006",
                description: "This premium wool coat is perfect for transitional weather. Made from a wool blend that provides warmth without bulk. The tailored silhouette and notched lapel create a polished look that works from office to evening.",
                features: [
                    "70% Wool, 30% Polyester",
                    "Notched lapel",
                    "Single-breasted button front",
                    "Flap pockets",
                    "Lined interior"
                ],
                colorCodes: {
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 7,
                title: "Linen Button-Up Shirt",
                category: "shirts",
                price: 49.99,
                oldPrice: 59.99,
                image: "https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["white", "blue"],
                sizes: ["S", "M", "L", "XL"],
                rating: 4.5,
                reviews: 19,
                badge: "sale",
                featured: true,
                newArrival: false,
                sku: "FH-007",
                description: "This lightweight linen shirt is perfect for warm weather. The breathable fabric and relaxed fit keep you cool and comfortable all day long. The button-up design makes it versatile enough for both casual and dressier occasions.",
                features: [
                    "100% Linen",
                    "Button-up front",
                    "Chest pocket",
                    "Relaxed fit",
                    "Machine washable"
                ],
                colorCodes: {
                    "white": "#e6e6e6",
                    "blue": "#5a8ac1"
                }
            },
            {
                id: 8,
                title: "Cashmere Scarf",
                category: "accessories",
                price: 59.99,
                image: "https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["red", "black", "beige"],
                sizes: ["One Size"],
                rating: 5.0,
                reviews: 12,
                badge: null,
                featured: false,
                newArrival: false,
                sku: "FH-008",
                description: "This luxurious cashmere scarf is the perfect accessory for cooler weather. The ultra-soft cashmere provides warmth without bulk, and the generous size allows for versatile styling. A timeless piece that will last for years.",
                features: [
                    "100% Cashmere",
                    "Generous size: 70\" x 12\"",
                    "Fringed ends",
                    "Ultra-soft hand feel",
                    "Dry clean recommended"
                ],
                colorCodes: {
                    "red": "#ff0000",
                    "black": "#3a3a3a",
                    "beige": "#d4a762"
                }
            },
            {
                id: 9,
                title: "Classic White Sneakers",
                category: "shoes",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["white", "black"],
                sizes: ["US 7", "US 8", "US 9", "US 10", "US 11"],
                rating: 4.8,
                reviews: 42,
                badge: "best-seller",
                featured: true,
                newArrival: false,
                sku: "FH-009",
                description: "These classic white sneakers are a wardrobe staple. Made from premium leather with a comfortable cushioned insole, they're perfect for all-day wear. The timeless design pairs well with any outfit.",
                features: [
                    "Premium leather upper",
                    "Cushioned insole for comfort",
                    "Rubber outsole for traction",
                    "Lace-up closure",
                    "Machine washable"
                ],
                colorCodes: {
                    "white": "#ffffff",
                    "black": "#3a3a3a"
                }
            },
            {
                id: 10,
                title: "Wool Blend Sweater",
                category: "sweaters",
                price: 79.99,
                oldPrice: 99.99,
                image: "https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["navy", "gray", "burgundy"],
                sizes: ["S", "M", "L", "XL"],
                rating: 4.3,
                reviews: 28,
                badge: "sale",
                featured: true,
                newArrival: false,
                sku: "FH-010",
                description: "This wool blend sweater offers warmth and style for the cooler months. The relaxed fit and ribbed cuffs provide both comfort and a polished look. Perfect for layering or wearing on its own.",
                features: [
                    "70% Wool, 30% Acrylic",
                    "Ribbed cuffs and hem",
                    "Relaxed fit",
                    "Crew neckline",
                    "Machine wash cold"
                ],
                colorCodes: {
                    "navy": "#001f3f",
                    "gray": "#aaaaaa",
                    "burgundy": "#800020"
                }
            },
            {
                id: 11,
                title: "Silk Blouse",
                category: "shirts",
                price: 89.99,
                image: "https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["ivory", "black", "sage"],
                sizes: ["XS", "S", "M", "L"],
                rating: 4.6,
                reviews: 35,
                badge: null,
                featured: false,
                newArrival: true,
                sku: "FH-011",
                description: "This elegant silk blouse features a delicate drape and subtle sheen that elevates any outfit. The button-front design and pointed collar create a sophisticated look perfect for work or special occasions.",
                features: [
                    "100% Silk",
                    "Button-front closure",
                    "Pointed collar",
                    "Long sleeves with button cuffs",
                    "Dry clean only"
                ],
                colorCodes: {
                    "ivory": "#fffff0",
                    "black": "#3a3a3a",
                    "sage": "#b2ac88"
                }
            },
            {
                id: 12,
                title: "Leather Belt",
                category: "accessories",
                price: 49.99,
                image: "https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80",
                colors: ["brown", "black"],
                sizes: ["S (30\")", "M (32\")", "L (34\")", "XL (36\")"],
                rating: 4.2,
                reviews: 19,
                badge: null,
                featured: false,
                newArrival: false,
                sku: "FH-012",
                description: "This genuine leather belt features a classic design with a polished buckle. The high-quality leather develops a beautiful patina over time, making it a durable and stylish accessory.",
                features: [
                    "Genuine leather",
                    "Polished metal buckle",
                    "Adjustable fit",
                    "Reversible (black/brown option)",
                    "Width: 1.5 inches"
                ],
                colorCodes: {
                    "brown": "#964B00",
                    "black": "#3a3a3a"
                }
            }
        ];

        // ==============================================
        // API CONFIGURATION
        // ==============================================
        const API_BASE = window.location.origin + '/fashionhub-old/api';
        
        // ==============================================
        // AUTHENTICATION SYSTEM
        // ==============================================
        const auth = {
            currentUser: null,
            apiBaseUrl: window.location.pathname.includes('fashionhub-old') 
                    ? '/fashionhub-old/api' 
                    : '/api',
            
           init: async function() {
               showLoading(); // Show loading animation
               
               try {
                   // Check server-side authentication
                   const response = await fetch(`${this.apiBaseUrl}/check_auth.php`, {
                       credentials: 'include'
                   });

                   if (response.ok) {
                       const data = await response.json();
                       if (data.status === 'success') {
                           this.currentUser = {
                               ...data.user,
                               avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.user.name) + '&background=random',
                               joinDate: new Date().toISOString(),
                               orders: [],
                               wishlist: [],
                               addresses: {
                                   billing: null,
                                   shipping: null
                               }
                           };

                           // Load addresses, wishlist items and orders from database
                           await this.loadAddresses();
                           await this.loadWishlistItems();
                           await this.loadUserCart(); // Load user's cart from database
                           await loadOrders();

                           // Load additional user data from localStorage if available
                           const localUserData = JSON.parse(localStorage.getItem('currentUser'));
                           if (localUserData) {
                               this.currentUser = {
                                   ...this.currentUser,
                                   addresses: localUserData.addresses || {
                                       billing: null,
                                       shipping: null
                                   }
                               };
                           }

                           localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
                           this.updateUI();
                           hideLoading(); // Hide loading animation when done
                           return;
                       }
                   }

                   // If not authenticated, show the login/register message
                   this.clearUserCart();
                   this.updateUI();
                   hideLoading(); // Hide loading animation when done

               } catch (error) {
                   console.error('Auth init error:', error);
                   this.clearUserCart();
                   this.updateUI();
                   hideLoading(); // Hide loading animation when done
               }
           },
            
            // Load wishlist items from database
            loadWishlistItems: async function() {
                if (!this.currentUser) return;
                
                try {
                    const response = await fetch(`${this.apiBaseUrl}/get_wishlist.php`, {
                        credentials: 'include'
                    });
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        // Update user's wishlist with product details
                        this.currentUser.wishlist = data.wishlist.map(item => ({
                            id: item.product_id,
                            title: item.title,
                            price: item.price,
                            image: item.image
                        }));
                        
                        // Update localStorage for consistency
                        localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
                    }
                } catch (error) {
                    console.error('Error loading wishlist:', error);
                    // Fallback to localStorage if API call fails
                    if (!this.currentUser.wishlist) {
                        this.currentUser.wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
                    }
                }
            },

            // Add this method to your auth object
            loadAddresses: async function() {
                if (!this.currentUser) return;
                
                try {
                    const response = await fetch(`${this.apiBaseUrl}/get_addresses.php`, {
                        credentials: 'include'
                    });
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        // If not JSON, get the text response for debugging
                        const text = await response.text();
                        console.error('Non-JSON response from get_addresses:', text.substring(0, 200));
                        return;
                    }
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        // Reset addresses
                        this.currentUser.addresses = {
                            billing: null,
                            shipping: null
                        };
                        
                        // Update user's addresses
                        data.addresses.forEach(address => {
                            this.currentUser.addresses[address.type] = {
                                name: address.name,
                                street: address.street,
                                city: address.city,
                                state: address.state,
                                zip: address.zip,
                                country: address.country
                            };
                        });
                        
                        // Update localStorage for consistency
                        localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
                    }
                } catch (error) {
                    console.error('Error loading addresses:', error);
                    // Fallback to localStorage if API call fails
                    if (!this.currentUser.addresses) {
                        this.currentUser.addresses = {
                            billing: null,
                            shipping: null
                        };
                    }
                }
            },

            logout: async function() {
                try {
                    // Server-side logout
                    await fetch(`${this.apiBaseUrl}/logout.php`, {
                        method: 'POST',
                        credentials: 'include'
                    });

                    // Client-side cleanup
                    this.currentUser = null;
                    localStorage.removeItem('currentUser');
                    this.clearUserCart(); // Clear cart on logout

                    // Dispatch logout event to update UI across all pages
                    window.dispatchEvent(new CustomEvent('userLogout'));

                    window.location.href = 'login.php';
                } catch (error) {
                    console.error('Logout error:', error);
                    this.clearUserCart(); // Clear cart even on error

                    // Dispatch logout event even on error
                    window.dispatchEvent(new CustomEvent('userLogout'));

                    window.location.href = 'login.php';
                }
            },

            // Load user's cart from database and sync with localStorage
            loadUserCart: async function() {
                if (!this.currentUser) return;

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

                            // Update cart count
                            cartCount = dbCart.reduce((total, item) => total + item.quantity, 0);
                            updateCartCount();
                        }
                    }
                } catch (error) {
                    console.error('Error loading user cart:', error);
                    // Fallback to empty cart if API fails
                    this.clearUserCart();
                }
            },

            // Clear user's cart from localStorage
            clearUserCart: function() {
                localStorage.removeItem('cart');
                cart = [];
                cartCount = 0;
                updateCartCount();
            },
            
            updateUI: function() {
                const container = document.getElementById('account-content-container');
                
                if (!this.currentUser) {
                    // Show not logged in state
                    container.innerHTML = `
                        <div class="not-logged-in">
                            <h2>You are not logged in</h2>
                            <p>Please log in to view your account details, orders, and wishlist.</p>
                            <div class="auth-buttons">
                                <a href="login.php" class="btn btn-primary">Login</a>
                                <a href="login.php" class="btn btn-outline">Create Account</a>
                            </div>
                        </div>
                    `;
                } else {
                    // Show account content
                    container.innerHTML = `
                        <div class="account-content">
                            <aside class="account-sidebar">
                                <div class="account-user">
                                    <div class="user-avatar">
                                        <img src="${this.currentUser.avatar}" alt="User Avatar">
                                    </div>
                                    <div class="user-info">
                                        <h3>${this.currentUser.name}</h3>
                                        <p>Member since ${new Date(this.currentUser.joinDate).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                
                                <nav class="account-menu">
                                    <ul>
                                        <li class="active"><a href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                        <li><a href="#orders"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                                        <li><a href="#wishlist"><i class="far fa-heart"></i> Wishlist</a></li>
                                        <li><a href="#addresses"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                                        <li><a href="#account"><i class="far fa-user"></i> Account Details</a></li>
                                        <li><a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                    </ul>
                                </nav>
                            </aside>
                            
                            <div class="account-main">
                                <!-- Dashboard Tab -->
                                <div class="account-tab active" id="dashboard">
                                    <h2>Dashboard</h2>
                                    <p>Hello <strong>${this.currentUser.name}</strong> (not <strong>${this.currentUser.name}</strong>? <a href="#" id="logout-link">Log out</a>)</p>
                                    <p>From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
                                    
                                    <div class="account-stats">
                                        <div class="stat-card">
                                            <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                                            <div class="stat-info">
                                                <div class="stat-number">${this.currentUser.orders.length}</div>
                                                <div class="stat-label">Orders</div>
                                            </div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-icon"><i class="far fa-heart"></i></div>
                                            <div class="stat-info">
                                                <div class="stat-number">${this.currentUser.wishlist.length}</div>
                                                <div class="stat-label">Wishlist</div>
                                            </div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                                            <div class="stat-info">
                                                <div class="stat-number">${(this.currentUser.addresses.billing ? 1 : 0) + (this.currentUser.addresses.shipping ? 1 : 0)}</div>
                                                <div class="stat-label">Addresses</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Orders Tab -->
                                <div class="account-tab" id="orders">
                                    <h2>Orders</h2>
                                    ${this.currentUser.orders.length > 0 ? `
                                    <div class="recent-orders">
                                        <h3>Recent Orders</h3>
                                        <div class="orders-container">
                                            ${this.currentUser.orders.slice(0, 2).map(order => `
                                                <div class="order-card">
                                                    <div class="order-header">
                                                        <div class="order-info">
                                                            <div class="order-number">Order #${order.order_number || order.id}</div>
                                                            <div class="order-date">Placed on ${new Date(order.date || order.order_date).toLocaleDateString()}</div>
                                                        </div>
                                                        <div class="order-status">
                                                            <span class="status-badge ${order.status}">${(order.status || 'processing').charAt(0).toUpperCase() + (order.status || 'processing').slice(1)}</span>
                                                        </div>
                                                    </div>
                                                    <div class="order-footer">
                                                        <div class="order-total">Total: $${parseFloat(order.total || 0).toFixed(2)}</div>
                                                        ${order.status === 'cancelled' ? `
                                                        <div class="order-fee-note" style="font-size: 0.8rem; color: #777; margin-top: 5px;">
                                                            Canceled items will be removed in 24 hours.
                                                        </div>
                                                        ` : ''}
                                                        <div class="order-actions">
                                                            <button class="btn btn-outline view-order-details" data-order-id="${order.id}">View Details</button>
                                                            ${order.status === 'delivered' ? `
                                                                <button class="btn btn-primary reorder-btn" data-order-id="${order.id}">Reorder</button>
                                                            ` : order.status === 'cancelled' ? `
                                                                <button class="btn btn-primary reorder-btn" data-order-id="${order.id}">Reorder</button>
                                                            ` : ''}
                                                        </div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                    ` : '<p>You have not placed any orders yet.</p>'}
                                    <div class="orders-container">
                                        <!-- Orders will be loaded here -->
                                    </div>
                                </div>
                                
                                <!-- Wishlist Tab -->
                                <div class="account-tab" id="wishlist">
                                    <h2>Wishlist</h2>
                                    <div class="product-grid">
                                        <!-- Wishlist items will be loaded here -->
                                    </div>
                                </div>
                                
                                <!-- Addresses Tab -->
                                <div class="account-tab" id="addresses">
                                    <h2>Addresses</h2>
                                    <p>No addresses have been set up yet.</p>
                                    
                                    <div class="address-grid">
                                        <div class="address-card">
                                            <div class="address-header">
                                                <h3>Billing Address</h3>
                                                <a href="#" class="edit-address" data-type="billing">Edit</a>
                                            </div>
                                         
                                            ${auth.currentUser.addresses.billing ? `
                                            <address>
                                                ${auth.currentUser.addresses.billing.name}<br>
                                                ${auth.currentUser.addresses.billing.street}<br>
                                                ${auth.currentUser.addresses.billing.city}, ${auth.currentUser.addresses.billing.state} ${auth.currentUser.addresses.billing.zip}<br>
                                                ${auth.currentUser.addresses.billing.country}
                                            </address>
                                            ` : '<p>No billing address set.</p>'}
                                        </div>
                                        
                                        <div class="address-card">
                                            <div class="address-header">
                                                <h3>Shipping Address</h3>
                                                <a href="#" class="edit-address" data-type="shipping">Edit</a>
                                            </div>
                                            ${this.currentUser.addresses.shipping ? `
                                            <address>
                                                ${this.currentUser.addresses.shipping.name}<br>
                                                ${this.currentUser.addresses.shipping.street}<br>
                                                ${this.currentUser.addresses.shipping.city}, ${this.currentUser.addresses.shipping.state} ${this.currentUser.addresses.shipping.zip}<br>
                                                ${this.currentUser.addresses.shipping.country}
                                            </address>
                                            ` : '<p>No shipping address set.</p>'}
                                        </div>
                                    </div>
                                    
                                    <div class="address-actions">
                                        <button class="btn btn-primary" id="add-billing-address">Add Billing Address</button>
                                        <button class="btn btn-primary" id="add-shipping-address">Add Shipping Address</button>
                                    </div>
                                </div>
                                
                                <!-- Account Details Tab -->
                                <div class="account-tab" id="account">
                                    <h2>Account Details</h2>
                                    <form class="account-form" id="account-form">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="first-name">First Name</label>
                                                <input type="text" id="first-name" value="${this.currentUser.name.split(' ')[0]}">
                                            </div>
                                            <div class="form-group">
                                                <label for="last-name">Last Name</label>
                                                <input type="text" id="last-name" value="${this.currentUser.name.split(' ').slice(1).join(' ') || ''}">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" id="email" value="${this.currentUser.email}">
                                        </div>
                                        
                                        <h3>Password Change</h3>
                                        
                                        <div class="form-group">
                                            <label for="current-password">Current Password</label>
                                            <input type="password" id="current-password" readonly onfocus="this.removeAttribute('readonly');">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="new-password">New Password</label>
                                            <input type="password" id="new-password" readonly onfocus="this.removeAttribute('readonly');">
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm-password">Confirm New Password</label>
                                            <input type="password" id="confirm-password" readonly onfocus="this.removeAttribute('readonly');">
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add event listeners
                    document.getElementById('logout-btn')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.logout();
                    });
                    
                    document.getElementById('logout-link')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.logout();
                    });
                    
                    document.getElementById('account-form')?.addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.updateAccountDetails();
                    });
                    
                // Add tab switching behavior for sidebar navigation
                const menuLinks = document.querySelectorAll('.account-menu a');
                const tabs = document.querySelectorAll('.account-tab');

                menuLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const tabId = this.getAttribute('href').substring(1);
                        const tab = document.getElementById(tabId);
                        
                        if (tab) {
                            // Update active states
                            document.querySelectorAll('.account-menu li').forEach(li => li.classList.remove('active'));
                            this.parentElement.classList.add('active');
                            
                            tabs.forEach(t => t.classList.remove('active'));
                            tab.classList.add('active');
                            
                            // Special handling for specific tabs
                            if (tabId === 'wishlist') {
                                renderWishlistItems();
                            } else if (tabId === 'orders') {
                                renderOrders();
                            }
                            
                            // Calculate the position to scroll to (account content minus header height)
                            const headerHeight = document.querySelector('.main-header').offsetHeight;
                            const accountContent = document.querySelector('.account-content');
                            const scrollPosition = accountContent.offsetTop - headerHeight;
                            
                            // Scroll to position with smooth behavior
                            window.scrollTo({
                                top: scrollPosition,
                                behavior: 'smooth'
                            });
                            
                            // Update URL hash
                            history.replaceState(null, null, `#${tabId}`);
                        }
                    });
                });
                    
                    // Check for hash in URL to show specific tab
                    if (window.location.hash) {
                        const tabId = window.location.hash.substring(1);
                        const tab = document.getElementById(tabId);
                        if (tab) {
                            tabs.forEach(t => t.classList.remove('active'));
                            tab.classList.add('active');
                            
                            menuLinks.forEach(l => l.parentElement.classList.remove('active'));
                            document.querySelector(`.account-menu a[href="#${tabId}"]`).parentElement.classList.add('active');
                            
                            // Special handling for specific tabs
                            if (tabId === 'wishlist') {
                                renderWishlistItems();
                            } else if (tabId === 'orders') {
                                renderOrders();
                            }
                        }
                    }
                    
                    // Initialize address buttons
                    initAddressButtons();
                    
                    // Initialize password toggles
                    initPasswordToggles();
                    
                    // Render orders if we're on the orders tab
                    if (window.location.hash === '#orders') {
                        renderOrders();
                    }
                    
                    // Initialize wishlist if on that tab
                    if (window.location.hash === '#wishlist') {
                        renderWishlistItems();
                    }
                }
                
                // Update wishlist count in header
                updateWishlistCount();
            },
            
           updateAccountDetails: async function() {
                const firstName = document.getElementById('first-name').value;
                const lastName = document.getElementById('last-name').value;
                const email = document.getElementById('email').value;
                const newPassword = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;
                
                // Basic validation
                if (newPassword && newPassword !== confirmPassword) {
                    alert('New passwords do not match!');
                    return;
                }
                
                try {
                    const response = await fetch(`${this.apiBaseUrl}/update_account.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify({
                            first_name: firstName,
                            last_name: lastName,
                            email: email,
                            new_password: newPassword
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        // Update user
                        const updatedUser = {
                            ...this.currentUser,
                            name: `${firstName} ${lastName}`.trim(),
                            email: email
                        };
                        
                        // Save to localStorage
                        localStorage.setItem('currentUser', JSON.stringify(updatedUser));
                        this.currentUser = updatedUser;
                        
                        alert('Account details updated successfully!');
                        
                        // Clear password fields
                        document.getElementById('new-password').value = '';
                        document.getElementById('confirm-password').value = '';
                    } else {
                        alert('Error updating account: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error updating account:', error);
                    alert('Error updating account. Please try again.');
                }
            }
        };

        // ==============================================
        // ORDER MANAGEMENT
        // ==============================================
        async function loadOrders() {
            if (!auth.currentUser) return;
            
            try {
                const response = await fetch(`${auth.apiBaseUrl}/get_orders.php`, {
                    credentials: 'include'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    console.log('Orders loaded successfully:', data.orders);
                    
                    // Update user's orders with proper data structure
                    auth.currentUser.orders = data.orders.map(order => ({
                        id: order.id,
                        order_number: order.order_number,
                        date: order.order_date || order.date,
                        status: order.status,
                        total: parseFloat(order.total_amount || order.total),
                        subtotal: parseFloat(order.subtotal_amount || order.subtotal || (order.total - (order.payment_fee || 0))),
                        payment_fee: parseFloat(order.payment_fee || (order.payment_method === 'Cash on Delivery' ? 5.00 : 0)),
                        items: order.items || [],
                        payment_method: order.payment_method,
                        payment_status: order.payment_status,
                        shipping_address: order.shipping_address,
                        billing_address: order.billing_address
                    }));
                    
                    // Update localStorage for consistency
                    localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
                    
                    // If we're on the orders tab, refresh the view
                    if (window.location.hash === '#orders') {
                        setTimeout(renderOrders, 100);
                    }
                } else {
                    console.error('Error loading orders:', data.message);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                // Fallback to localStorage if API call fails
                if (!auth.currentUser.orders) {
                    auth.currentUser.orders = JSON.parse(localStorage.getItem('orders')) || [];
                }
            }
        }

        function renderOrders() {
            const ordersContainer = document.querySelector('#orders .orders-container');
            if (!ordersContainer) {
                console.error('Orders container not found - retrying in 500ms');
                setTimeout(renderOrders, 500);
                return;
            }

            ordersContainer.innerHTML = '';

            if (!auth.currentUser || !auth.currentUser.orders || auth.currentUser.orders.length === 0) {
                ordersContainer.innerHTML = `
                    <div class="empty-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="shop.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                `;
                return;
            }

            // Sort orders: pending/processing/shipped/delivered first, then cancelled
            const sortedOrders = auth.currentUser.orders.sort((a, b) => {
                const statusOrder = {
                    'pending': 1,
                    'processing': 2,
                    'shipped': 3,
                    'delivered': 4,
                    'cancelled': 5
                };

                const aOrder = statusOrder[a.status] || 6;
                const bOrder = statusOrder[b.status] || 6;

                if (aOrder !== bOrder) {
                    return aOrder - bOrder;
                }

                // Within same status, sort by date (newest first)
                return new Date(b.date) - new Date(a.date);
            });

            sortedOrders.forEach(order => {
                const orderElement = document.createElement('div');
                orderElement.className = 'order-card';
                
                // Helper function to get product title from item
                const getItemTitle = (item) => {
                    return item.title || item.name || item.product_name || 
                           item.product_title || 'Unknown Product';
                };
                
                // Helper function to get product image
                const getItemImage = (item) => {
                    return item.image || item.product_image || 
                           'https://via.placeholder.com/60x60?text=No+Image';
                };
                
                orderElement.innerHTML = `
                    <div class="order-header">
                        <div class="order-info">
                            <div class="order-number">Order #${order.order_number || order.id}</div>
                            <div class="order-date">Placed on ${new Date(order.date || order.order_date).toLocaleDateString()}</div>
                        </div>
                        <div class="order-status">
                            <span class="status-badge ${order.status}">${(order.status || 'processing').charAt(0).toUpperCase() + (order.status || 'processing').slice(1)}</span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        ${order.items.slice(0, 3).map(item => `
                            <div class="order-item-preview">
                                <img src="${getItemImage(item)}" alt="${getItemTitle(item)}" class="item-image">
                                <div class="item-info">
                                    <div class="item-name">${getItemTitle(item)}</div>
                                    <div class="item-quantity">Qty: ${item.quantity || 1}</div>
                                </div>
                            </div>
                        `).join('')}
                        
                        ${order.items.length > 3 ? `
                            <div class="more-items">+${order.items.length - 3} more items</div>
                        ` : ''}
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">Total: $${parseFloat(order.total || 0).toFixed(2)}</div>
                        ${order.status === 'cancelled' ? `
                        <div class="order-fee-note" style="font-size: 0.8rem; color: #777; margin-top: 5px;">
                            Canceled items will be removed in 24 hours.
                        </div>
                        ` : order.payment_fee > 0 ? `
                        <div class="order-fee-note" style="font-size: 0.8rem; color: #777; margin-top: 5px;">
                            Includes $${parseFloat(order.payment_fee || 0).toFixed(2)} COD fee
                        </div>
                        ` : ''}
                        <div class="order-actions">
                            <button class="btn btn-outline view-order-details" data-order-id="${order.id}">View Details</button>
                            ${order.status === 'delivered' ? `
                                <button class="btn btn-primary reorder-btn" data-order-id="${order.id}">Reorder</button>
                            ` : order.status === 'cancelled' ? `
                                <button class="btn btn-primary reorder-btn" data-order-id="${order.id}">Reorder</button>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="order-details" id="order-details-${order.id}" style="display: none;">
                        <div class="order-details-content">
                            <h4>Order Details</h4>
                            
                            <div class="details-section">
                                <h5>Items</h5>
                                ${order.items.map(item => `
                                    <div class="detail-item">
                                        <img src="${getItemImage(item)}" alt="${getItemTitle(item)}">
                                        <div class="item-info">
                                            <div class="item-name">${getItemTitle(item)}</div>
                                            ${item.size && item.size !== 'N/A' ? `<div class="item-variant">Size: ${item.size}</div>` : ''}
                                            ${item.color && item.color !== 'N/A' ? `<div class="item-variant">Color: ${item.color}</div>` : ''}
                                            <div class="item-price">$${parseFloat(item.price || item.product_price || 0).toFixed(2)} × ${item.quantity || 1}</div>
                                        </div>
                                        <div class="item-total">$${(parseFloat(item.price || item.product_price || 0) * (item.quantity || 1)).toFixed(2)}</div>
                                    </div>
                                `).join('')}
                            </div>
                            
                            ${order.shipping_address ? `
                            <div class="details-section">
                                <h5>Shipping Address</h5>
                                <address>
                                    ${order.shipping_address.name || ''}<br>
                                    ${order.shipping_address.address || order.shipping_address.street || ''}<br>
                                    ${order.shipping_address.address2 ? order.shipping_address.address2 + '<br>' : ''}
                                    ${order.shipping_address.city || ''}, ${order.shipping_address.state || ''} ${order.shipping_address.zip || order.shipping_address.postal_code || ''}<br>
                                    ${order.shipping_address.country || ''}<br>
                                    ${order.shipping_address.phone || ''}
                                </address>
                            </div>
                            ` : ''}
                            
                            <div class="details-section">
                                <h5>Payment Information</h5>
                                <p>Method: ${order.payment_method || 'Unknown'}</p>
                                <p>Status: <span class="payment-status ${order.payment_status || 'pending'}">${(order.payment_status || 'pending').charAt(0).toUpperCase() + (order.payment_status || 'pending').slice(1)}</span></p>
                            </div>
                            
                            <div class="details-section">
                                <h5>Order Summary</h5>
                                <div class="order-summary">
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span>$${parseFloat(order.subtotal || order.total || 0).toFixed(2)}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Shipping:</span>
                                        <span>Free</span>
                                    </div>
                                    ${order.payment_fee > 0 ? `
                                    <div class="summary-row fee">
                                        <span>Payment Fee (Cash on Delivery):</span>
                                        <span>+$${parseFloat(order.payment_fee || 0).toFixed(2)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span>$${parseFloat(order.total || 0).toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                            
                            ${(order.status === 'processing' || order.status === 'pending') ? `
                            <div class="details-section">
                                <button class="cancel-order-btn" data-order-id="${order.id}">Cancel Order</button>
                                <p class="cancel-note">You can cancel your order until it's shipped.</p>
                            </div>
                            ` : order.status === 'shipped' ? `
                            <div class="details-section">
                                <p class="cancel-note" style="color: #f44336; font-weight: 500;">The order has been shipped and cannot be canceled.</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                ordersContainer.appendChild(orderElement);
            });
            
            // Add event listeners for view details buttons
            document.querySelectorAll('.view-order-details').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const detailsElement = document.getElementById(`order-details-${orderId}`);
                    
                    if (detailsElement.style.display === 'none') {
                        detailsElement.style.display = 'block';
                        this.textContent = 'Hide Details';
                    } else {
                        detailsElement.style.display = 'none';
                        this.textContent = 'View Details';
                    }
                });
            });
            
            // Add event listeners for cancel order buttons
            document.querySelectorAll('.cancel-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    cancelOrder(orderId);
                });
            });

            // Add event listeners for reorder buttons
            document.querySelectorAll('.reorder-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    reorderOrder(orderId);
                });
            });
        }

        async function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                try {
                    const response = await fetch(`${auth.apiBaseUrl}/cancel_order.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        // Find and update the order in local data
                        const orderIndex = auth.currentUser.orders.findIndex(order => order.id == orderId);
                        if (orderIndex !== -1) {
                            auth.currentUser.orders[orderIndex].status = 'cancelled';
                            localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
                        }

                        // Re-render orders
                        renderOrders();

                        alert('Order has been cancelled successfully.');
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error cancelling order:', error);
                    alert('Failed to cancel order. Please try again.');
                }
            }
        }

        function reorderOrder(orderId) {
            // Find the order
            const order = auth.currentUser.orders.find(order => order.id == orderId);
            if (!order) {
                alert('Order not found.');
                return;
            }

            // Add all items from the order to cart
            order.items.forEach(item => {
                addToCart({
                    id: item.product_id || item.id,
                    title: item.product_name || item.title || item.name,
                    price: parseFloat(item.price || item.product_price || 0),
                    image: item.image || item.product_image
                }, item.quantity || 1, item.size, item.color);
            });

            // Show success message and redirect to checkout
            showCartNotification({title: 'Items added to cart'});
            setTimeout(() => {
                window.location.href = 'checkout.php';
            }, 1500);
        }

        // ==============================================
        // WISHLIST MANAGEMENT
        // ==============================================
        async function toggleWishlist(productId, productElement = null) {
            try {
                // Check if user is logged in
                if (!auth.currentUser) {
                    showAuthAlert();
                    return false;
                }
                
                // Validate product ID
                if (!productId || productId <= 0) {
                    console.error('Invalid product ID:', productId);
                    return false;
                }
                
                const response = await fetch(`${auth.apiBaseUrl}/toggle_wishlist.php`, {
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
                            auth.currentUser.wishlist.push({
                                id: product.id,
                                title: product.title,
                                price: product.price,
                                image: product.image
                            });
                        }
                        showWishlistNotification('Added to wishlist!');
                    } else {
                        auth.currentUser.wishlist = auth.currentUser.wishlist.filter(item => item.id !== productId);
                        showWishlistNotification('Removed from wishlist!');
                        
                        // If we're in the modal, update the button
                        if (currentModalProduct && currentModalProduct.id === productId) {
                            const wishlistBtn = document.getElementById('addToWishlistModal');
                            if (wishlistBtn) {
                                wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
                            }
                        }
                        
                        // If we're on the wishlist page, remove the product card
                        if (window.location.hash === '#wishlist' && productElement) {
                            productElement.remove();
                            
                            // If wishlist is now empty, show empty state
                            if (auth.currentUser.wishlist.length === 0) {
                                renderWishlistItems();
                            }
                        }
                    }
                    
                    // Update wishlist count
                    updateWishlistCount();
                    
                    // Update localStorage
                    localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
                    
                    return true;
                } else {
                    console.error('Error toggling wishlist:', data.message);
                    return false;
                }
            } catch (error) {
                console.error('Error toggling wishlist:', error);
                // Fallback to local storage if API call fails
                return toggleWishlistLocal(productId, productElement);
            }
        }

        // Fallback function for wishlist using local storage
        function toggleWishlistLocal(productId, productElement = null) {
            if (!auth.currentUser) {
                showAuthAlert();
                return false;
            }
            
            // Validate product ID
            if (!productId || productId <= 0) {
                console.error('Invalid product ID:', productId);
                return false;
            }
            
            // Initialize wishlist if it doesn't exist
            if (!auth.currentUser.wishlist) {
                auth.currentUser.wishlist = [];
            }
            
            // Check if product is already in wishlist
            const existingIndex = auth.currentUser.wishlist.findIndex(item => item.id === productId);
            
            if (existingIndex >= 0) {
                // Remove from wishlist
                auth.currentUser.wishlist.splice(existingIndex, 1);
                
                showWishlistNotification('Removed from wishlist!');
                
                // If we're in the modal, update the button
                if (currentModalProduct && currentModalProduct.id === productId) {
                    const wishlistBtn = document.getElementById('addToWishlistModal');
                    if (wishlistBtn) {
                        wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
                    }
                }
                
                // If we're on the wishlist page, remove the product card
                if (window.location.hash === '#wishlist' && productElement) {
                    productElement.remove();
                    
                    // If wishlist is now empty, show empty state
                    if (auth.currentUser.wishlist.length === 0) {
                        renderWishlistItems();
                    }
                }
            } else {
                // Add to wishlist
                const product = products.find(p => p.id === productId);
                if (product) {
                    auth.currentUser.wishlist.push({
                        id: product.id,
                        title: product.title,
                        price: product.price,
                        image: product.image
                    });
                    
                    showWishlistNotification('Added to wishlist!');
                    
                    // If we're in the modal, update the button
                    if (currentModalProduct && currentModalProduct.id === productId) {
                        const wishlistBtn = document.getElementById('addToWishlistModal');
                        if (wishlistBtn) {
                            wishlistBtn.innerHTML = '<i class="fas fa-heart"></i> Remove from Wishlist';
                        }
                    }
                }
            }
            
            // Save to localStorage
            localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
            localStorage.setItem('wishlist', JSON.stringify(auth.currentUser.wishlist));
            
            // Update UI if we're on the wishlist tab
            if (window.location.hash === '#wishlist') {
                renderWishlistItems();
            }
            
            // Update wishlist count
            updateWishlistCount();
            return true;
        }

        function renderWishlistItems() {
            const wishlistContainer = document.querySelector('#wishlist .product-grid');
            if (!wishlistContainer) return;
            
            wishlistContainer.innerHTML = '';
            
            if (!auth.currentUser || auth.currentUser.wishlist.length === 0) {
                wishlistContainer.innerHTML = `
                    <div class="empty-wishlist">
                        <i class="far fa-heart"></i>
                        <h3>Your Wishlist is Empty</h3>
                        <p>You haven't added any items to your wishlist yet.</p>
                        <a href="shop.php" class="btn btn-primary">Browse Products</a>
                    </div>
                `;
                return;
            }
            
            // Get full product details for wishlist items
            const wishlistProducts = auth.currentUser.wishlist.map(wishlistItem => {
                return products.find(p => p.id === wishlistItem.id);
            }).filter(Boolean);
            
            wishlistProducts.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.dataset.id = product.id;
                
                // Generate stars HTML
                const stars = generateStars(product.rating);
                
                // Generate old price if exists
                const oldPrice = product.oldPrice ? `<span class="old-price">$${product.oldPrice.toFixed(2)}</span>` : '';
                
                // Generate discount if exists
                let discount = '';
                if (product.oldPrice) {
                    const discountPercent = Math.round((1 - product.price / product.oldPrice) * 100);
                    discount = `<span class="discount">Save ${discountPercent}%</span>`;
                }
                
                productCard.innerHTML = `
                    <div class="product-image">
                        ${product.badge ? `<span class="product-badge ${product.badge}">${
                            product.badge === 'sale' ? 'Sale' : 
                            product.badge === 'new' ? 'New' : 'Best Seller'
                        }</span>` : ''}
                        <img src="${product.image}" alt="${product.title}">
                        <div class="product-actions">
                            <button class="action-btn quick-view" title="Quick View">
                                <i class="far fa-eye"></i>
                            </button>
                            <button class="action-btn add-to-cart" title="Add to Cart">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                            <button class="action-btn remove-from-wishlist" title="Remove from Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">${product.title}</h3>
                        <div class="price-container">
                            <span class="current-price">$${product.price.toFixed(2)}</span>
                            ${oldPrice}
                            ${discount}
                        </div>
                        <div class="rating-container">
                            <div class="stars">
                                ${stars}
                            </div>
                            <span class="review-count">(${product.reviews})</span>
                        </div>
                    </div>
                `;
                
                wishlistContainer.appendChild(productCard);
                
                // Add event listeners
                const quickViewBtn = productCard.querySelector('.quick-view');
                const addToCartBtn = productCard.querySelector('.add-to-cart');
                const removeFromWishlistBtn = productCard.querySelector('.remove-from-wishlist');
                
                quickViewBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showProductModal(product);
                });
                
                addToCartBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    addToCart(product);
                });
                
                removeFromWishlistBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showRemoveConfirmation(product, productCard);
                });
                
                productCard.addEventListener('click', function(e) {
                    if (e.target.closest('.product-actions')) return;
                    showProductModal(product);
                });
            });
        }

        // ==============================================
        // PRODUCT MODAL FUNCTIONS
        // ==============================================
        let currentModalProduct = null;
        let selectedSize = null;
        let selectedColor = null;

        function showProductModal(product) {
            const modal = document.getElementById('productModal');
            if (!modal) return; // Safety check
            
            currentModalProduct = product;
            
            // Set basic product info
            document.getElementById('modalProductTitle').textContent = product.title;
            document.getElementById('modalProductImage').src = product.image;
            document.getElementById('modalProductPrice').textContent = `$${product.price.toFixed(2)}`;
            document.getElementById('modalProductDescription').textContent = product.description;
            
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
            
            // Set rating
            document.getElementById('modalProductRating').innerHTML = generateStars(product.rating);
            document.getElementById('modalProductReviews').textContent = `(${product.reviews})`;
            
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
            product.sizes.forEach(size => {
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
            });
            
            // Set color options
            const colorOptions = document.getElementById('modalColorOptions');
            colorOptions.innerHTML = '';
            product.colors.forEach(color => {
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
            });
            
            // Set wishlist button state
            const isInWishlist = auth.currentUser ? 
                auth.currentUser.wishlist.some(item => item.id === product.id) : false;
            const wishlistBtn = document.getElementById('addToWishlistModal');
            wishlistBtn.innerHTML = isInWishlist ? 
                '<i class="fas fa-heart"></i> Remove from Wishlist' : 
                '<i class="far fa-heart"></i> Add to Wishlist';
            
            // Update wishlist button event listener
            wishlistBtn.onclick = async function() {
                if (!currentModalProduct) return;

                // Scroll to top before action
                window.scrollTo(0, 0);

                const wasInWishlist = auth.currentUser?.wishlist.some(item => item.id === currentModalProduct.id);

                await toggleWishlist(currentModalProduct.id);

                // Update button text after toggle
                const newIsInWishlist = auth.currentUser?.wishlist.some(item => item.id === currentModalProduct.id);
                this.innerHTML = newIsInWishlist ?
                    '<i class="fas fa-heart"></i> Remove from Wishlist' :
                    '<i class="far fa-heart"></i> Add to Wishlist';
            };
            
            // Reset quantity
            document.getElementById('productQuantity').value = 1;
            
            // Show modal
            modal.classList.add('active');
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore body scroll
        }

        // ==============================================
        // ADDRESS MANAGEMENT
        // ==============================================
        function openAddressModal(type) {
            const modal = document.getElementById('addressModal');
            const title = document.getElementById('addressModalTitle');
            const addressType = document.getElementById('addressType');
            
            // Set modal title and type
            title.textContent = type === 'billing' ? 'Edit Billing Address' : 'Edit Shipping Address';
            addressType.value = type;
            
            // Fill form with existing address if available
            const address = auth.currentUser.addresses[type];
            if (address) {
                document.getElementById('addressName').value = address.name || '';
                document.getElementById('addressStreet').value = address.street || '';
                document.getElementById('addressCity').value = address.city || '';
                document.getElementById('addressState').value = address.state || '';
                document.getElementById('addressZip').value = address.zip || '';
                
                // Set country using code if available, otherwise use name
                const countrySelect = document.getElementById('addressCountry');
                if (address.country_code) {
                    countrySelect.value = address.country_code;
                } else if (address.country) {
                    // Try to find the code from the name
                    const countryCodeMap = {
                        'United States': 'US',
                        'United Kingdom': 'UK',
                        'Pakistan' : 'PK',
                        'Canada': 'CA',
                        'Australia': 'AU',
                        'Germany': 'DE',
                        'France': 'FR',
                        'Italy': 'IT',
                        'Japan': 'JP',
                        'China': 'CN',
                        'Brazil': 'BR',
                        'India': 'IN',
                        'Russia': 'RU',
                        'Other': 'other'
                    };
                    countrySelect.value = countryCodeMap[address.country] || address.country;
                }
            } else {
                document.getElementById('addressForm').reset();
            }
            
            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeAddressModal() {
            const modal = document.getElementById('addressModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        async function saveAddress(type, addressData) {
            try {
                const apiUrl = `${auth.apiBaseUrl}/save_addresses.php`;
                console.log('Calling API:', apiUrl);
                
                // Map full country names to country codes
                const countryCodeMap = {
                    'United States': 'US',
                    'United Kingdom': 'UK',
                    'Pakistan' : 'PK',
                    'Canada': 'CA',
                    'Australia': 'AU',
                    'Germany': 'DE',
                    'France': 'FR',
                    'Italy': 'IT',
                    'Japan': 'JP',
                    'China': 'CN',
                    'Brazil': 'BR',
                    'India': 'IN',
                    'Russia': 'RU',
                    'Other': 'other'
                };
                
                // Convert country name to code if needed
                let countryCode = addressData.country;
                if (countryCode.length > 2 && countryCodeMap[countryCode]) {
                    countryCode = countryCodeMap[countryCode];
                }
                
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        type: type,
                        name: addressData.name,
                        street: addressData.street,
                        city: addressData.city,
                        state: addressData.state,
                        zip: addressData.zip,
                        country: countryCode // Send the country code, not the full name
                    })
                });
                
                console.log('Response status:', response.status);
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    throw new Error('Server returned non-JSON response. Status: ' + response.status);
                }
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update current user - store both code and display name
                    auth.currentUser.addresses[type] = {
                        ...addressData,
                        country_code: countryCode
                    };
                    
                    // Update localStorage
                    localStorage.setItem('currentUser', JSON.stringify(auth.currentUser));
                    
                    // Reload addresses from server to ensure consistency
                    await auth.loadAddresses();
                    
                    return true;
                } else {
                    console.error('Error saving address:', data.message);
                    alert('Error saving address: ' + data.message);
                    return false;
                }
            } catch (error) {
                console.error('Error saving address:', error);
                // Fallback to localStorage if API call fails
                auth.currentUser.addresses[type] = addressData;
                localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
                alert('Address saved locally. Changes will sync when the server connection is restored.');
                return true;
            }
        }

        // Initialize address buttons
        function initAddressButtons() {
            // Add Billing Address button
            document.getElementById('add-billing-address')?.addEventListener('click', function(e) {
                e.preventDefault();
                openAddressModal('billing');
            });
            
            // Add Shipping Address button
            document.getElementById('add-shipping-address')?.addEventListener('click', function(e) {
                e.preventDefault();
                openAddressModal('shipping');
            });
            
            // Edit Address buttons
            document.querySelectorAll('.edit-address').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = this.dataset.type;
                    openAddressModal(type);
                });
            });
            
            // Initialize address modal
            const addressModal = document.getElementById('addressModal');
            if (addressModal) {
                // Close button
                document.getElementById('closeAddressModal')?.addEventListener('click', closeAddressModal);
                document.getElementById('cancelAddress')?.addEventListener('click', closeAddressModal);
                
                // Click outside to close
                addressModal.addEventListener('click', function(e) {
                    if (e.target === addressModal) {
                        closeAddressModal();
                    }
                });
                
                // Escape key to close
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && addressModal.classList.contains('active')) {
                        closeAddressModal();
                    }
                });
                
                // Form submission
                document.getElementById('addressForm')?.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const type = document.getElementById('addressType').value;
                    const addressData = {
                        name: document.getElementById('addressName').value,
                        street: document.getElementById('addressStreet').value,
                        city: document.getElementById('addressCity').value,
                        state: document.getElementById('addressState').value,
                        zip: document.getElementById('addressZip').value,
                        country: document.getElementById('addressCountry').value
                    };
                    
                    if (saveAddress(type, addressData)) {
                        // Update UI
                        auth.updateUI();
                        closeAddressModal();
                    }
                });
            }
        }

        // ==============================================
        // CONFIRMATION MODAL FUNCTIONS
        // ==============================================
        let productToRemove = null;
        let productCardToRemove = null;

        function showRemoveConfirmation(product, productCard) {
            // Scroll to top before showing modal
            window.scrollTo(0, 0);
            productToRemove = product;
            productCardToRemove = productCard;
            document.getElementById('confirmationModal').classList.add('active');
            
            // Update confirmation message
            document.getElementById('confirmationMessage').textContent = 
                `Are you sure you want to remove "${product.title}" from your wishlist?`;
        }

        function hideRemoveConfirmation() {
            document.getElementById('confirmationModal').classList.remove('active');
        }

        // ==============================================
        // UTILITY FUNCTIONS
        // ==============================================
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

        function updateWishlistCount() {
            let wishlistCount = 0;
            
            if (auth.currentUser) {
                wishlistCount = auth.currentUser.wishlist.length;
            }
            
            const wishlistCountElement = document.querySelector('.wishlist-count');
            if (wishlistCountElement) {
                wishlistCountElement.textContent = wishlistCount;
            }
        }

        // Shopping Cart
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let cartCount = cart.reduce((total, item) => total + item.quantity, 0);

        function updateCartCount() {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
            }
        }

        async function addToCart(product, quantity = 1, size = null, color = null) {
            if (!auth.currentUser) {
                showAuthAlert();
                return;
            }

            try {
                const response = await fetch(`${auth.apiBaseUrl}/add_to_cart.php`, {
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
                    await auth.loadUserCart();

                    // Update cart count immediately
                    updateCartCount();

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

        function showWishlistNotification(message) {
            // Scroll to top before showing notification
            window.scrollTo(0, 0);
            
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

        function showAuthAlert() {
            // Scroll to top before showing alert
            window.scrollTo(0, 0);
            
            const alert = document.createElement('div');
            alert.className = 'auth-alert';
            alert.innerHTML = `
                <div class="auth-alert-content">
                    <h3>Account Required</h3>
                    <p>Please create an account or login to add items to your wishlist or cart.</p>
                    <div class="auth-alert-buttons">
                        <a href="login.php" class="btn btn-primary">Login</a>
                        <a href="login.php" class="btn btn-outline">Create Account</a>
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

        function initPasswordToggles() {
            document.querySelectorAll('.toggle-password').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    // Prevent text selection when clicking the eye icon
                    e.stopPropagation();
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.innerHTML = '<i class="far fa-eye-slash"></i>';
                    } else {
                        passwordInput.type = 'password';
                        this.innerHTML = '<i class="far fa-eye"></i>';
                    }
                });
            });
        }

        // ==============================================
        // INITIALIZATION
        // ==============================================
        document.addEventListener('DOMContentLoaded', () => {
            showLoading(); // Show loading animation on page load
            auth.init();
            updateCartCount();
            updateWishlistCount();
            
            // Initialize confirmation modal
            const confirmationModal = document.getElementById('confirmationModal');
            const confirmRemoveBtn = document.getElementById('confirmRemove');
            const cancelRemoveBtn = document.getElementById('cancelRemove');

            confirmRemoveBtn?.addEventListener('click', function() {
                // Close modal immediately
                confirmationModal.classList.remove('active');
                
                // Process removal after modal closes (for better UX)
                setTimeout(() => {
                    if (productToRemove && productCardToRemove) {
                        // Use the toggleWishlist function to properly remove from database
                        toggleWishlist(productToRemove.id, productCardToRemove);
                        
                        // Clear references
                        productToRemove = null;
                        productCardToRemove = null;
                    }
                }, 10);
            });
            
            cancelRemoveBtn?.addEventListener('click', function() {
                confirmationModal.classList.remove('active');
                productToRemove = null;
                productCardToRemove = null;
            });

            // Close modal when clicking outside
            confirmationModal?.addEventListener('click', function(e) {
                if (e.target === confirmationModal) {
                    confirmationModal.classList.remove('active');
                    productToRemove = null;
                    productCardToRemove = null;
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && confirmationModal?.classList.contains('active')) {
                    confirmationModal.classList.remove('active');
                    productToRemove = null;
                    productCardToRemove = null;
                }
            });

            // Initialize product modal
            const modal = document.getElementById('productModal');
            
            document.getElementById('closeModal')?.addEventListener('click', function() {
                closeProductModal();
            });
            
            modal?.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeProductModal();
                }
            });
            
            // Escape key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal?.classList.contains('active')) {
                    closeProductModal();
                }
            });
            
            // Quantity buttons
            document.getElementById('increaseQty')?.addEventListener('click', function() {
                const quantityInput = document.getElementById('productQuantity');
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });
            
            document.getElementById('decreaseQty')?.addEventListener('click', function() {
                const quantityInput = document.getElementById('productQuantity');
                if (parseInt(quantityInput.value) > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                }
            });
            
            // Add to cart from modal
            document.getElementById('addToCartModal')?.addEventListener('click', function() {
                if (!currentModalProduct) return;
                
                // Scroll to top before action
                window.scrollTo(0, 0);
                
                const quantity = parseInt(document.getElementById('productQuantity').value);
                const size = document.querySelector('.size-btn.active')?.dataset.size;
                const color = document.querySelector('.color-btn.active')?.dataset.color;
                
                addToCart(currentModalProduct, quantity, size, color);
                
                // Show confirmation and close modal
                showCartNotification(currentModalProduct);
                closeProductModal();
            });
        });

        // Listen for storage events to update counts when data changes in other tabs
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue) || [];
                cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                updateCartCount();
            }
            if (e.key === 'currentUser') {
                // Update the current user data
                auth.currentUser = JSON.parse(e.newValue);
                
                // Update wishlist count when user data changes
                updateWishlistCount();
                
                // If we're on the wishlist tab, refresh the view
                if (window.location.hash === '#wishlist') {
                    renderWishlistItems();
                } else if (window.location.hash === '#orders') {
                    renderOrders();
                }
            }
        });
    </script>
</body>
</html>
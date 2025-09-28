<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns & Exchanges - FashionHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.html'; ?>

    <main class="info-page">
        <div class="container">
            <h1 class="page-title">Returns & Exchanges</h1>
            <div class="info-content">

                <div class="info-section return-request-section">
                    <h2>Initiate a Return</h2>
                    <p>Fill out the form below to request a return for your order.</p>
                    <form id="returnRequestForm">
                        <div class="form-group">
                            <label for="orderNumber">Order Number:</label>
                            <input type="text" id="orderNumber" name="orderNumber" placeholder="e.g., FH12345" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" placeholder="Your email address" required>
                        </div>
                        <div class="form-group">
                            <label for="returnReason">Reason for Return:</label>
                            <textarea id="returnReason" name="returnReason" rows="4" placeholder="e.g., Wrong size, defective item, etc." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                    <div id="returnResult" class="return-result"></div>
                </div>

                <div class="info-section">
                    <h2>Our Return Policy</h2>
                    <p>We accept returns of unworn, unwashed, undamaged, or defective merchandise purchased on our website for a full refund or exchange within 30 days of the original purchase date. Items must be returned in their original packaging with all tags attached.</p>
                </div>

                <div class="info-section">
                    <h2>How to Initiate a Return or Exchange</h2>
                    <ol>
                        <li><strong>Submit Request:</strong> Fill out the return request form on this page.</li>
                        <li><strong>Receive Instructions:</strong> We will review your request and email you a Return Merchandise Authorization (RMA) number and detailed instructions within 1-2 business days.</li>
                        <li><strong>Ship Your Item:</strong> Package your item securely, include the RMA number, and ship it to the address provided. Customers are responsible for return shipping costs unless the item is defective or incorrect.</li>
                    </ol>
                </div>

                <div class="info-section">
                    <h2>Refunds</h2>
                    <p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>
                    <p>If approved, your refund will be processed, and a credit will automatically be applied to your original method of payment, within 7-10 business days.</p>
                </div>

                <div class="info-section">
                    <h2>Exchanges</h2>
                    <p>If you need to exchange an item for a different size, color, or style, please follow the return process and place a new order for the desired item. This ensures the fastest delivery of your new item. We will process your refund for the returned item separately.</p>
                </div>

                <div class="info-section">
                    <h2>Damaged or Incorrect Items</h2>
                    <p>If you received a damaged or incorrect item, please contact us immediately at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a> with photos of the item and your order number. We will arrange for a replacement or refund and cover all associated shipping costs.</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.html'; ?>

    <script>
        document.getElementById('returnRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const orderNumber = document.getElementById('orderNumber').value;
            const email = document.getElementById('email').value;
            const reason = document.getElementById('returnReason').value;
            const resultDiv = document.getElementById('returnResult');

            // Basic validation
            if (orderNumber && email && reason) {
                // In a real application, this would send a request to the server.
                resultDiv.innerHTML = `<p><strong>Thank you!</strong> Your return request for order <strong>${orderNumber}</strong> has been submitted. We will email you with further instructions at <strong>${email}</strong> within 1-2 business days.</p>`;
                document.getElementById('returnRequestForm').reset();
            } else {
                resultDiv.innerHTML = `<p>Please fill out all fields to submit a return request.</p>`;
            }
        });
    </script>
</body>
</html>

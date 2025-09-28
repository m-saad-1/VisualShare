<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Policy - FashionHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.html'; ?>

    <main class="info-page">
        <div class="container">
            <h1 class="page-title">Shipping Policy</h1>
            <div class="info-content">

                <div class="info-section shipping-calculator-section">
                    <h2>Shipping Cost Estimator</h2>
                    <p>Select your country and shipping method to get an estimated cost.</p>
                    <form id="shippingCalculatorForm">
                        <div class="form-group">
                            <label for="country">Country:</label>
                            <select id="country" name="country">
                                <option value="us">United States</option>
                                <option value="ca">Canada</option>
                                <option value="gb">United Kingdom</option>
                                <option value="au">Australia</option>
                                <option value="de">Germany</option>
                                <option value="fr">France</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shippingMethod">Shipping Method:</label>
                            <select id="shippingMethod" name="shippingMethod">
                                <option value="standard">Standard</option>
                                <option value="express">Express</option>
                                <option value="overnight">Overnight</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Calculate</button>
                    </form>
                    <div id="shippingResult" class="shipping-result"></div>
                </div>

                <div class="info-section">
                    <h2>Order Processing</h2>
                    <p>All orders are processed within 1-2 business days (excluding weekends and holidays) after receiving your order confirmation email. You will receive another notification when your order has shipped.</p>
                </div>

                <div class="info-section">
                    <h2>Domestic Shipping Rates and Estimates</h2>
                    <p>Shipping charges for your order will be calculated and displayed at checkout.</p>
                    <ul>
                        <li><strong>Standard Shipping:</strong> 5-7 business days</li>
                        <li><strong>Express Shipping:</strong> 2-3 business days</li>
                        <li><strong>Overnight Shipping:</strong> 1 business day</li>
                    </ul>
                </div>

                <div class="info-section">
                    <h2>International Shipping</h2>
                    <p>We offer international shipping to most countries. Shipping charges and delivery times for your order will be calculated and displayed at checkout. Your order may be subject to import duties and taxes (including VAT), which are incurred once a shipment reaches your destination country. FashionHub is not responsible for these charges if they are applied and are your responsibility as the customer.</p>
                </div>

                <div class="info-section">
                    <h2>How do I check the status of my order?</h2>
                    <p>When your order has shipped, you will receive an email notification from us which will include a tracking number you can use to check its status. Please allow 48 hours for the tracking information to become available.</p>
                    <p>If you haven’t received your order within 10 days of receiving your shipping confirmation email, please contact us at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a> with your name and order number, and we will look into it for you.</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.html'; ?>

    <script>
        document.getElementById('shippingCalculatorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const country = document.getElementById('country').value;
            const method = document.getElementById('shippingMethod').value;
            const resultDiv = document.getElementById('shippingResult');

            const costs = {
                us: { standard: 10, express: 20, overnight: 30 },
                ca: { standard: 15, express: 25, overnight: 40 },
                gb: { standard: 20, express: 35, overnight: 50 },
                au: { standard: 25, express: 40, overnight: 60 },
                de: { standard: 18, express: 30, overnight: 45 },
                fr: { standard: 18, express: 30, overnight: 45 },
            };

            const estimatedCost = costs[country][method];
            resultDiv.innerHTML = `<p>Estimated shipping cost: <strong>$${estimatedCost.toFixed(2)}</strong></p><p>This is an estimate. Final shipping costs will be calculated at checkout.</p>`;
        });
    </script>
</body>
</html>

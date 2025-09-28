<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - FashionHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.html'; ?>

    <main class="info-page">
        <div class="container">
            <h1 class="page-title">Frequently Asked Questions</h1>

            <div class="faq-search-section">
                <input type="text" id="faqSearch" placeholder="Search for a question...">
            </div>

            <div class="faqs-container">
                <div class="faq-item">
                    <button class="faq-question">
                        How can I track my order?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Once your order has shipped, you will receive an email with a tracking number. You can use this number on the carrier's website to track your package's journey.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        What payment methods do you accept?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>We accept major credit cards (Visa, MasterCard, American Express, Discover), PayPal, and Apple Pay.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Can I change or cancel my order after it's placed?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>We process orders quickly, so changes or cancellations may not always be possible. Please contact us immediately at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a> if you need to modify your order.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Do you offer international shipping?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, we ship internationally to most countries. Shipping costs and delivery times vary by destination and will be calculated at checkout. Please note that international orders may be subject to customs duties and taxes, which are the responsibility of the recipient.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        What is your return policy?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>We offer a 30-day return policy for unworn, unwashed, and undamaged items with tags still attached. For full details, please visit our <a href="returns-exchanges.html">Returns & Exchanges</a> page.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How do I find my size?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>You can refer to our detailed <a href="size-guide.html">Size Guide</a> available on each product page and also in the footer of our website. It provides measurements and tips to help you find the perfect fit.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How do I contact customer service?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>You can reach our customer service team via email at <a href="mailto:support@fashionhub.com">support@fashionhub.com</a> or by filling out the contact form on our <a href="contact.html">Contact Us</a> page. We aim to respond within 24-48 business hours.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Are your products sustainable?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>We are committed to sustainability and are continuously working to improve our practices. We use eco-friendly materials where possible and partner with manufacturers who share our values. Look for the 'Sustainable' badge on our product pages.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Do you have a physical store?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Currently, FashionHub is an online-only retailer. This allows us to offer a wider selection of products at more competitive prices.</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include 'footer.html'; ?>

    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.closest('.faq-item');
                faqItem.classList.toggle('active');
                const answer = faqItem.querySelector('.faq-answer');
                if (faqItem.classList.contains('active')) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                } else {
                    answer.style.maxHeight = null;
                }
            });
        });

        const faqSearch = document.getElementById('faqSearch');
        faqSearch.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();
            const questions = document.querySelectorAll('.faq-item');

            questions.forEach(question => {
                const questionText = question.querySelector('.faq-question').textContent.toLowerCase();
                const answerText = question.querySelector('.faq-answer').textContent.toLowerCase();

                if (questionText.includes(term) || answerText.includes(term)) {
                    question.style.display = 'block';
                } else {
                    question.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
<?php
require_once 'includes/config.php';
$page_title = 'Privacy Policy - VisualShare';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="privacy-page">
        <div class="privacy-header">
            <h1>Privacy Policy</h1>
            <p class="privacy-subtitle">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>

        <div class="privacy-content">
            <section class="privacy-section">
                <h2>1. Introduction</h2>
                <p>At VisualShare, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.</p>
            </section>

            <section class="privacy-section">
                <h2>2. Information We Collect</h2>
                <div class="subsection">
                    <h3>2.1 Personal Information</h3>
                    <p>We collect information you provide directly to us, including:</p>
                    <ul>
                        <li>Name and username</li>
                        <li>Email address</li>
                        <li>Profile picture and bio</li>
                        <li>Content you upload (images, videos, descriptions)</li>
                        <li>Communication preferences</li>
                    </ul>
                </div>
                <div class="subsection">
                    <h3>2.2 Usage Information</h3>
                    <p>We automatically collect certain information when you use our Service:</p>
                    <ul>
                        <li>Device information (IP address, browser type, operating system)</li>
                        <li>Usage data (pages visited, time spent, interactions)</li>
                        <li>Cookies and similar tracking technologies</li>
                        <li>Location information (if enabled)</li>
                    </ul>
                </div>
            </section>

            <section class="privacy-section">
                <h2>3. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide, maintain, and improve our Service</li>
                    <li>Process and manage your account</li>
                    <li>Communicate with you about our Service</li>
                    <li>Personalize your experience</li>
                    <li>Analyze usage patterns and trends</li>
                    <li>Ensure security and prevent fraud</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>4. Information Sharing and Disclosure</h2>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:</p>
                <ul>
                    <li><strong>With your consent:</strong> When you explicitly agree to share information</li>
                    <li><strong>Service providers:</strong> With trusted third-party service providers who assist our operations</li>
                    <li><strong>Legal requirements:</strong> When required by law or to protect our rights</li>
                    <li><strong>Business transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                    <li><strong>Public content:</strong> Information you choose to make public on our platform</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>5. Cookies and Tracking Technologies</h2>
                <p>We use cookies and similar technologies to enhance your experience:</p>
                <ul>
                    <li><strong>Essential cookies:</strong> Required for basic site functionality</li>
                    <li><strong>Analytics cookies:</strong> Help us understand how you use our Service</li>
                    <li><strong>Preference cookies:</strong> Remember your settings and preferences</li>
                    <li><strong>Marketing cookies:</strong> Used to deliver relevant advertisements</li>
                </ul>
                <p>You can control cookie settings through your browser preferences.</p>
            </section>

            <section class="privacy-section">
                <h2>6. Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>
            </section>

            <section class="privacy-section">
                <h2>7. Data Retention</h2>
                <p>We retain your personal information for as long as necessary to provide our Service and fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required by law.</p>
            </section>

            <section class="privacy-section">
                <h2>8. Your Rights and Choices</h2>
                <p>You have the following rights regarding your personal information:</p>
                <ul>
                    <li><strong>Access:</strong> Request a copy of your personal information</li>
                    <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                    <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                    <li><strong>Portability:</strong> Request transfer of your data</li>
                    <li><strong>Opt-out:</strong> Unsubscribe from marketing communications</li>
                    <li><strong>Restriction:</strong> Limit how we process your information</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>9. Third-Party Services</h2>
                <p>Our Service may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.</p>
            </section>

            <section class="privacy-section">
                <h2>10. Children's Privacy</h2>
                <p>Our Service is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we become aware that we have collected personal information from a child under 13, we will take steps to delete such information.</p>
            </section>

            <section class="privacy-section">
                <h2>11. International Data Transfers</h2>
                <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your personal information during such transfers.</p>
            </section>

            <section class="privacy-section">
                <h2>12. Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
            </section>

            <section class="privacy-section">
                <h2>13. Contact Us</h2>
                <p>If you have any questions about this Privacy Policy or our privacy practices, please contact us at:</p>
                <p><strong>Email:</strong> privacy@visualshare.com</p>
                <p><strong>Address:</strong> N/A</p>
            </section>
        </div>
    </div>
</div>

<style>
.privacy-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.privacy-header {
    text-align: center;
    margin-bottom: 3rem;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 2rem;
}

.privacy-header h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.privacy-subtitle {
    font-size: 1rem;
    color: #666;
    margin: 0;
}

.privacy-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.privacy-section {
    margin-bottom: 2rem;
}

.privacy-section h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 2px solid #4361ee;
    padding-bottom: 0.5rem;
    display: inline-block;
}

.privacy-section p {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 1rem;
}

.subsection {
    margin-bottom: 1.5rem;
}

.subsection h3 {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.privacy-section ul {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.privacy-section li {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 0.5rem;
}

.privacy-section strong {
    color: #333;
    font-weight: 600;
}

@media (max-width: 768px) {
    .privacy-header h1 {
        font-size: 2rem;
    }

    .privacy-section h2 {
        font-size: 1.3rem;
    }

    .subsection h3 {
        font-size: 1.1rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
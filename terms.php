<?php
require_once 'includes/config.php';
$page_title = 'Terms of Service - VisualShare';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="terms-page">
        <div class="terms-header">
            <h1>Terms of Service</h1>
            <p class="terms-subtitle">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>

        <div class="terms-content">
            <section class="terms-section">
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using VisualShare ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
            </section>

            <section class="terms-section">
                <h2>2. Description of Service</h2>
                <p>VisualShare is a platform that allows users to upload, share, and discover visual content including images and videos. We provide tools for content creation, community interaction, and content discovery.</p>
            </section>

            <section class="terms-section">
                <h2>3. User Accounts</h2>
                <div class="subsection">
                    <h3>3.1 Account Creation</h3>
                    <p>To use certain features of the Service, you must register for an account. You must provide accurate, complete, and current information during the registration process.</p>
                </div>
                <div class="subsection">
                    <h3>3.2 Account Security</h3>
                    <p>You are responsible for safeguarding your account credentials and for all activities that occur under your account. You must immediately notify us of any unauthorized use of your account.</p>
                </div>
                <div class="subsection">
                    <h3>3.3 Account Termination</h3>
                    <p>We reserve the right to terminate or suspend your account at our discretion, with or without cause, and with or without notice.</p>
                </div>
            </section>

            <section class="terms-section">
                <h2>4. User Content</h2>
                <div class="subsection">
                    <h3>4.1 Content Ownership</h3>
                    <p>You retain ownership of the content you upload to VisualShare. By uploading content, you grant us a non-exclusive, worldwide, royalty-free license to use, display, and distribute your content on our platform.</p>
                </div>
                <div class="subsection">
                    <h3>4.2 Content Guidelines</h3>
                    <p>You agree not to upload content that:</p>
                    <ul>
                        <li>Violates any laws or regulations</li>
                        <li>Infringes on intellectual property rights</li>
                        <li>Contains harmful, offensive, or inappropriate material</li>
                        <li>Is spam or misleading</li>
                        <li>Violates the rights of others</li>
                    </ul>
                </div>
                <div class="subsection">
                    <h3>4.3 Content Moderation</h3>
                    <p>We reserve the right to remove or disable access to any content that violates these terms or our community guidelines.</p>
                </div>
            </section>

            <section class="terms-section">
                <h2>5. Prohibited Activities</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the Service for any illegal purpose</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Impersonate others or provide false information</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                    <li>Distribute malware or harmful code</li>
                    <li>Scrape or collect user data without permission</li>
                    <li>Use automated tools to interact with the Service</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>6. Intellectual Property</h2>
                <div class="subsection">
                    <h3>6.1 Our Content</h3>
                    <p>The Service and its original content, features, and functionality are owned by VisualShare and are protected by copyright, trademark, and other intellectual property laws.</p>
                </div>
                <div class="subsection">
                    <h3>6.2 Third-Party Content</h3>
                    <p>Content from third parties may be subject to additional terms and conditions. We are not responsible for third-party content.</p>
                </div>
            </section>

            <section class="terms-section">
                <h2>7. Privacy</h2>
                <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Service, to understand our practices.</p>
            </section>

            <section class="terms-section">
                <h2>8. Disclaimers</h2>
                <p>The Service is provided on an "as is" and "as available" basis. We make no warranties, expressed or implied, and hereby disclaim all warranties including but not limited to merchantability, fitness for a particular purpose, and non-infringement.</p>
            </section>

            <section class="terms-section">
                <h2>9. Limitation of Liability</h2>
                <p>In no event shall VisualShare be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of the Service.</p>
            </section>

            <section class="terms-section">
                <h2>10. Indemnification</h2>
                <p>You agree to defend, indemnify, and hold harmless VisualShare from and against any claims, damages, costs, and expenses arising from your use of the Service or violation of these terms.</p>
            </section>

            <section class="terms-section">
                <h2>11. Termination</h2>
                <p>We may terminate or suspend your account and access to the Service immediately, without prior notice, for any reason, including breach of these terms.</p>
            </section>

            <section class="terms-section">
                <h2>12. Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. We will notify users of significant changes. Continued use of the Service after changes constitutes acceptance of the new terms.</p>
            </section>

            <section class="terms-section">
                <h2>13. Governing Law</h2>
                <p>These terms shall be governed by and construed in accordance with applicable laws, without regard to conflict of law provisions.</p>
            </section>

            <section class="terms-section">
                <h2>14. Contact Information</h2>
                <p>If you have any questions about these Terms of Service, please contact us at:</p>
                <p><strong>Email:</strong> legal@visualshare.com</p>
                <p><strong>Address:</strong> N/A </p>
            </section>
        </div>
    </div>
</div>

<style>
.terms-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.terms-header {
    text-align: center;
    margin-bottom: 3rem;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 2rem;
}

.terms-header h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.terms-subtitle {
    font-size: 1rem;
    color: #666;
    margin: 0;
}

.terms-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.terms-section {
    margin-bottom: 2rem;
}

.terms-section h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 2px solid #4361ee;
    padding-bottom: 0.5rem;
    display: inline-block;
}

.terms-section p {
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

.terms-section ul {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.terms-section li {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .terms-header h1 {
        font-size: 2rem;
    }

    .terms-section h2 {
        font-size: 1.3rem;
    }

    .subsection h3 {
        font-size: 1.1rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
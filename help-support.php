<?php
require_once 'includes/config.php';
$page_title = 'Help & Support - VisualShare';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="help-support-page">
        <div class="help-header">
            <h1>Help & Support</h1>
            <p class="help-subtitle">Find answers to your questions and get the help you need</p>
        </div>

        <div class="help-content">
            <!-- Search Bar -->
            <div class="help-search">
                <div class="search-input-group">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search help articles..." class="help-search-input" id="helpSearch">
                </div>
            </div>

            <!-- Quick Links -->
            <div class="quick-links">
                <h2>Popular Topics</h2>
                <div class="quick-links-grid">
                    <a href="#getting-started" class="quick-link">
                        <i class="fas fa-rocket"></i>
                        <span>Getting Started</span>
                    </a>
                    <a href="#account" class="quick-link">
                        <i class="fas fa-user"></i>
                        <span>Account & Profile</span>
                    </a>
                    <a href="#uploading" class="quick-link">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Uploading Content</span>
                    </a>
                    <a href="#community" class="quick-link">
                        <i class="fas fa-users"></i>
                        <span>Community Guidelines</span>
                    </a>
                    <a href="#troubleshooting" class="quick-link">
                        <i class="fas fa-tools"></i>
                        <span>Troubleshooting</span>
                    </a>
                    <a href="#contact" class="quick-link">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>

            <!-- FAQ Section -->
            <section class="faq-section" id="faq">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I create an account?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>To create an account, click on the "Register" button in the top navigation. Fill in your username, email, and password. You'll receive a confirmation email to verify your account.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I upload content?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>After logging in, click the upload button in the navigation or go to the upload page. Select your image or video file, add a title and description, and choose relevant tags. Click "Upload" to share your content.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I save content I like?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Click the bookmark icon on any content piece to save it to your personal collection. You can view your saved items in your dashboard.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I follow other users?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Visit a user's profile page and click the "Follow" button. You'll see their new content in your feed and can view all users you're following in your dashboard.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What types of files can I upload?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You can upload images (JPG, PNG, GIF) and videos (MP4, MOV, AVI). Maximum file size is 50MB. We recommend high-quality images for the best viewing experience.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I report inappropriate content?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>If you encounter content that violates our community guidelines, click the report button on the content or contact our support team directly.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Help Articles -->
            <section class="help-articles">
                <h2>Help Articles</h2>
                <div class="articles-grid">
                    <article class="help-article" id="getting-started">
                        <h3><i class="fas fa-rocket"></i> Getting Started Guide</h3>
                        <p>Welcome to VisualShare! This guide will help you get started with creating your account, uploading your first content, and exploring the platform.</p>
                        <ul>
                            <li>Create and verify your account</li>
                            <li>Set up your profile with a photo and bio</li>
                            <li>Upload your first image or video</li>
                            <li>Explore trending content and discover creators</li>
                        </ul>
                    </article>

                    <article class="help-article" id="account">
                        <h3><i class="fas fa-user"></i> Managing Your Account</h3>
                        <p>Learn how to customize your profile, manage your privacy settings, and keep your account secure.</p>
                        <ul>
                            <li>Update your profile information</li>
                            <li>Change your password</li>
                            <li>Manage privacy settings</li>
                            <li>Delete your account if needed</li>
                        </ul>
                    </article>

                    <article class="help-article" id="uploading">
                        <h3><i class="fas fa-cloud-upload-alt"></i> Uploading Content</h3>
                        <p>Everything you need to know about uploading and managing your visual content on VisualShare.</p>
                        <ul>
                            <li>Supported file formats and sizes</li>
                            <li>Adding titles, descriptions, and tags</li>
                            <li>Editing and deleting content</li>
                            <li>Best practices for engagement</li>
                        </ul>
                    </article>

                    <article class="help-article" id="community">
                        <h3><i class="fas fa-users"></i> Community Guidelines</h3>
                        <p>Our community guidelines ensure VisualShare remains a safe and welcoming space for all creators.</p>
                        <ul>
                            <li>Respect other users and their content</li>
                            <li>No spam or inappropriate content</li>
                            <li>Copyright and intellectual property</li>
                            <li>Reporting violations</li>
                        </ul>
                    </article>

                    <article class="help-article" id="troubleshooting">
                        <h3><i class="fas fa-tools"></i> Troubleshooting</h3>
                        <p>Common issues and their solutions to help you have a smooth experience on VisualShare.</p>
                        <ul>
                            <li>Upload problems and solutions</li>
                            <li>Login and password issues</li>
                            <li>Content not displaying correctly</li>
                            <li>Mobile app troubleshooting</li>
                        </ul>
                    </article>
                </div>
            </section>

            <!-- Contact Support -->
            <section class="contact-support" id="contact">
                <h2>Still Need Help?</h2>
                <p>Can't find what you're looking for? Our support team is here to help!</p>

                <div class="contact-options">
                    <div class="contact-option">
                        <i class="fas fa-envelope"></i>
                        <h3>Email Support</h3>
                        <p>Get help from our support team via email. We typically respond within 24 hours.</p>
                        <a href="mailto:support@visualshare.com" class="contact-button">Email Us</a>
                    </div>

                    <div class="contact-option">
                        <i class="fas fa-comments"></i>
                        <h3>Live Chat</h3>
                        <p>Chat with our support team in real-time during business hours.</p>
                        <button class="contact-button" onclick="alert('Live chat coming soon!')">Start Chat</button>
                    </div>

                    <div class="contact-option">
                        <i class="fas fa-question-circle"></i>
                        <h3>Help Center</h3>
                        <p>Browse our comprehensive help center for detailed guides and tutorials.</p>
                        <a href="#faq" class="contact-button">Browse Help Center</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
.help-support-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.help-header {
    text-align: center;
    margin-bottom: 3rem;
}

.help-header h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.help-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin: 0;
}

.help-content {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

/* Search */
.help-search {
    margin-bottom: 2rem;
}

.search-input-group {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.help-search-input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e0e0e0;
    border-radius: 30px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.help-search-input:focus {
    outline: none;
    border-color: #4361ee;
}

/* Quick Links */
.quick-links h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
}

.quick-links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
}

.quick-link:hover {
    background: #4361ee;
    color: white;
    transform: translateY(-2px);
}

.quick-link i {
    font-size: 1.5rem;
    color: #4361ee;
}

.quick-link:hover i {
    color: white;
}

/* FAQ */
.faq-section h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 2rem;
    text-align: center;
}

.faq-list {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 1rem;
    overflow: hidden;
}

.faq-question {
    width: 100%;
    padding: 1.5rem;
    background: white;
    border: none;
    text-align: left;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    transition: background-color 0.3s ease;
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question i {
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: #f8f9fa;
}

.faq-item.active .faq-answer {
    max-height: 200px;
}

.faq-answer p {
    margin: 0;
    padding: 1.5rem;
    color: #555;
    line-height: 1.6;
}

/* Help Articles */
.help-articles h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 2rem;
    text-align: center;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.help-article {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    border-left: 4px solid #4361ee;
}

.help-article h3 {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.help-article h3 i {
    color: #4361ee;
}

.help-article p {
    color: #555;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.help-article ul {
    color: #666;
    padding-left: 1.5rem;
}

.help-article li {
    margin-bottom: 0.5rem;
}

/* Contact Support */
.contact-support {
    text-align: center;
    background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 16px;
}

.contact-support h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.contact-support p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.contact-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.contact-option {
    background: rgba(255, 255, 255, 0.1);
    padding: 2rem;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.contact-option i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    display: block;
}

.contact-option h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.contact-option p {
    margin-bottom: 1.5rem;
    opacity: 0.9;
    line-height: 1.5;
}

.contact-button {
    display: inline-block;
    padding: 10px 20px;
    background: white;
    color: #4361ee;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.contact-button:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .help-header h1 {
        font-size: 2rem;
    }

    .quick-links-grid {
        grid-template-columns: 1fr;
    }

    .articles-grid {
        grid-template-columns: 1fr;
    }

    .contact-options {
        grid-template-columns: 1fr;
    }

    .faq-question {
        font-size: 1rem;
        padding: 1rem;
    }

    .faq-answer p {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');

            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('helpSearch');
    const helpArticles = document.querySelectorAll('.help-article');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        helpArticles.forEach(article => {
            const text = article.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                article.style.display = 'block';
            } else {
                article.style.display = 'none';
            }
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
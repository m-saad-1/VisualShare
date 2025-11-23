<?php
require_once 'includes/config.php';
$page_title = 'About - VisualShare';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="about-page">
        <div class="about-header">
            <h1>About VisualShare</h1>
            <p class="about-subtitle">Discover, share, and inspire with visual content</p>
        </div>

        <div class="about-content">
            <section class="about-section">
                <h2>Our Mission</h2>
                <p>VisualShare is a vibrant community platform where creators, artists, and enthusiasts come together to share their visual stories. We believe that every image and video has the power to inspire, educate, and connect people across the globe.</p>
            </section>

            <section class="about-section">
                <h2>What We Offer</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-camera"></i>
                        <h3>Content Sharing</h3>
                        <p>Upload and share your photos, videos, and creative work with our community of visual enthusiasts.</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-search"></i>
                        <h3>Discovery</h3>
                        <p>Explore trending content, search by tags, and discover new creators who inspire you.</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-heart"></i>
                        <h3>Community</h3>
                        <p>Connect with like-minded individuals, follow your favorite creators, and build meaningful relationships.</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-bookmark"></i>
                        <h3>Save & Organize</h3>
                        <p>Save content you love and organize your personal collection for easy access.</p>
                    </div>
                </div>
            </section>

            <section class="about-section">
                <h2>Our Story</h2>
                <p>Founded with the vision of creating a space where visual creativity knows no bounds, VisualShare has grown from a small idea into a thriving community. We started with a simple belief: that everyone has something beautiful to share, and everyone deserves to be seen and appreciated.</p>
                <p>Today, our platform serves thousands of creators and millions of viewers, fostering a supportive environment where art, photography, and visual storytelling flourish.</p>
            </section>

            <section class="about-section">
                <h2>Join Our Community</h2>
                <p>Whether you're a professional photographer, an amateur artist, or simply someone who appreciates beautiful visuals, VisualShare welcomes you. Start sharing your story today and become part of something bigger.</p>
                <div class="cta-buttons">
                    <a href="register.php" class="cta-button primary">Join VisualShare</a>
                    <a href="upload.php" class="cta-button secondary">Start Sharing</a>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
.about-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.about-header {
    text-align: center;
    margin-bottom: 3rem;
}

.about-header h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.about-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin: 0;
}

.about-content {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

.about-section {
    margin-bottom: 2rem;
}

.about-section h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 2px solid #4361ee;
    padding-bottom: 0.5rem;
    display: inline-block;
}

.about-section p {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 1rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-item {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.feature-item i {
    font-size: 3rem;
    color: #4361ee;
    margin-bottom: 1rem;
    display: block;
}

.feature-item h3 {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.feature-item p {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.5;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.cta-button {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.cta-button.primary {
    background: #4361ee;
    color: white;
}

.cta-button.primary:hover {
    background: #3a56d4;
    transform: translateY(-2px);
}

.cta-button.secondary {
    background: transparent;
    color: #4361ee;
    border: 2px solid #4361ee;
}

.cta-button.secondary:hover {
    background: #4361ee;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .about-header h1 {
        font-size: 2rem;
    }

    .about-subtitle {
        font-size: 1rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .feature-item {
        padding: 1.5rem;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .cta-button {
        width: 200px;
        text-align: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>

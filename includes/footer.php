</main>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="about.php">About</a>
                    <a href="help-support.php">Help & Support</a>
                    <a href="terms.php">Terms</a>
                    <a href="privacy.php">Privacy</a>
                </div>
                
                <div class="footer-cta">
                    <h3>Ready to Share Your Vision?</h3>
                    <p>Join thousands of creators showcasing their visual stories</p>
                    <a href="upload.php" class="cta-button">Start Creating</a>
                </div>
                
                <button id="back-to-top" class="back-to-top" aria-label="Back to top">
                    <i class="fas fa-arrow-up"></i>
                </button>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> VisualShare. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <style>
        /* Footer Styles */
        .footer {
            background-color: #ffffff;
            padding: 2rem 0;
            border-top: 1px solid #f0f0f0;
            margin-top: 3rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }
        
        .footer-links a {
            color: #555;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #4361ee;
        }
        
        .footer-cta {
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            border-radius: 16px;
            margin: 2rem 0;
            border: 1px solid #dee2e6;
        }

        .footer-cta h3 {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .footer-cta p {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .footer-cta .cta-button {
            display: inline-block;
            padding: 12px 24px;
            background: #4361ee;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 2px solid #4361ee;
        }

        .footer-cta .cta-button:hover {
            background: white;
            color: #4361ee;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }
        
        .footer-bottom p {
            color: #777;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #4361ee;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: #3a56d4;
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .footer-links {
                gap: 1rem;
            }

            .footer-cta {
                padding: 1.5rem;
                margin: 1.5rem 0;
            }

            .footer-cta h3 {
                font-size: 1.2rem;
            }

            .footer-cta p {
                font-size: 0.9rem;
            }

            .back-to-top {
                width: 40px;
                height: 40px;
                bottom: 1.5rem;
                right: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .footer-cta {
                padding: 1rem;
                margin: 1rem 0;
            }

            .footer-cta h3 {
                font-size: 1.1rem;
            }
        }
    </style>
    <script>
        // Back to top button functionality
        const backToTopButton = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>
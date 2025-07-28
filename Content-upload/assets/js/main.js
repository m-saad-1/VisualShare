document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // Masonry Grid Initialization with ImagesLoaded
    // =============================================
    const initMasonryGrid = () => {
        const grid = document.querySelector('.grid');
        if (!grid) return;

        // Wait for images to load before layout
        imagesLoaded(grid, function() {
            new Masonry(grid, {
                itemSelector: '.grid-item',
                columnWidth: '.grid-item',
                percentPosition: true,
                gutter: 20,
                horizontalOrder: true, // Ensures items fill rows in order
                fitWidth: true // Centers the grid
            });
        });
    };

    // =============================================
    // Lazy Loading with Intersection Observer
    // =============================================
    const initLazyLoading = () => {
        const lazyImages = [].slice.call(document.querySelectorAll('img[loading="lazy"]'));
        
        if ('IntersectionObserver' in window) {
            const lazyImageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src || lazyImage.src;
                        lazyImage.classList.add('loaded');
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            lazyImages.forEach(function(lazyImage) {
                lazyImage.src = lazyImage.dataset.src || lazyImage.src;
                lazyImage.classList.add('loaded');
            });
        }
    };

    // =============================================
    // Logout Functionality
    // =============================================
    const initLogout = () => {
        const logoutButtons = document.querySelectorAll('#logout');
        if (logoutButtons.length === 0) return;

        logoutButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Simple redirect approach (more reliable than fetch)
                window.location.href = 'logout.php';
            });
        });
    };

    // =============================================
    // Upload Form Enhancements
    // =============================================
    const initUploadForm = () => {
        const fileInput = document.getElementById('file');
        const preview = document.getElementById('preview');
        const uploadArea = document.querySelector('.upload-area');

        if (!fileInput || !preview || !uploadArea) return;

        // File input change handler
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        ['dragover', 'dragenter'].forEach(event => {
            uploadArea.addEventListener(event, function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
        });

        ['dragleave', 'dragend', 'drop'].forEach(event => {
            uploadArea.addEventListener(event, function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        uploadArea.addEventListener('click', () => fileInput.click());
    };

    // =============================================
    // Image View Page Enhancements
    // =============================================
    const initImageView = () => {
        // Only run on view.php
        if (!document.querySelector('.image-view-container')) return;

        const imageWrapper = document.querySelector('.image-wrapper');
        if (!imageWrapper) return;

        const img = imageWrapper.querySelector('img');
        if (!img) return;

        // Add zoom functionality on click
        img.addEventListener('click', function() {
            this.classList.toggle('zoomed');
        });

        // Prevent context menu
        img.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    };

    // =============================================
    // Initialize All Functionality
    // =============================================
    initMasonryGrid();
    initLazyLoading();
    initLogout();
    initUploadForm();
    initImageView();

    // =============================================
    // Helper Functions
    // =============================================
    function debounce(func, wait = 100) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(this, args);
            }, wait);
        };
    }

    // Re-layout masonry on window resize
    window.addEventListener('resize', debounce(function() {
        const grid = document.querySelector('.grid');
        if (grid && typeof Masonry !== 'undefined') {
            const msnry = Masonry.data(grid);
            if (msnry) {
                msnry.layout();
            }
        }
    }, 200));
});
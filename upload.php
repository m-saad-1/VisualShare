<?php
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    // For AJAX requests, return a JSON error
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Authentication required.']);
        exit;
    }
    // For regular requests, redirect to login
    header("Location: login.php");
    exit;
}

// Handle AJAX file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => 'An unknown error occurred.'];

    // Validate title and description
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($title)) {
        $response['message'] = 'Title is required.';
        echo json_encode($response);
        exit;
    }

    // File upload handling
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_type, ALLOWED_TYPES)) {
            $response['message'] = 'Only images (JPG, PNG, GIF, WEBP) are allowed.';
        } elseif ($file_size > MAX_FILE_SIZE) {
            $response['message'] = 'File size must be less than 20MB.';
        } else {
            if (!file_exists(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }

            $filename = uniqid('', true) . '.' . $file_ext;
            $filepath = UPLOAD_DIR . $filename;
            $webpath  = 'includes/uploads/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                chmod($filepath, 0644);

                $query = "INSERT INTO uploads (user_id, filename, filepath, title, description) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("issss", $_SESSION['user_id'], $filename, $webpath, $title, $description);

                if ($stmt->execute()) {
                    $upload_id = $conn->insert_id;

                    if (!empty($_POST['tags'])) {
                        $tags = array_map('trim', explode(',', $_POST['tags']));
                        foreach ($tags as $tag_name) {
                            if (!empty($tag_name)) {
                                $tag_stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
                                $tag_stmt->bind_param("s", $tag_name);
                                $tag_stmt->execute();
                                $tag_result = $tag_stmt->get_result();

                                if ($tag_result->num_rows > 0) {
                                    $tag_id = $tag_result->fetch_assoc()['id'];
                                } else {
                                    $insert_tag_stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
                                    $insert_tag_stmt->bind_param("s", $tag_name);
                                    $insert_tag_stmt->execute();
                                    $tag_id = $conn->insert_id;
                                }

                                $upload_tag_stmt = $conn->prepare("INSERT INTO upload_tags (upload_id, tag_id) VALUES (?, ?)");
                                $upload_tag_stmt->bind_param("ii", $upload_id, $tag_id);
                                $upload_tag_stmt->execute();
                            }
                        }
                    }

                    $_SESSION['success'] = 'Your image has been uploaded successfully!';
                    $response = ['success' => true, 'message' => 'Upload successful!', 'redirect' => 'index.php'];
                } else {
                    $response['message'] = 'Failed to save upload details to the database.';
                    unlink($filepath); // Clean up uploaded file
                }
            } else {
                $response['message'] = 'Failed to move uploaded file.';
            }
        }
    } else {
        $response['message'] = 'Please select an image to upload or an upload error occurred.';
        if(isset($_FILES['file']['error'])) {
            $response['error_code'] = $_FILES['file']['error'];
        }
    }

    echo json_encode($response);
    exit;
}

require_once 'includes/header.php';
?>
<style>
    .upload-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .upload-area:hover {
        border-color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .upload-area i {
        font-size: 3rem;
        color: #4361ee;
        margin-bottom: 1rem;
    }
    
    .preview-container {
        display: none;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    #image-preview {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 4px;
        display: none;
    }
    
    .btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background-color: #4361ee;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .btn:hover {
        background-color: #3f37c9;
    }
    
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    

    /* Loader and Progress Indicator Styles */
    .loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0s, opacity 0.3s linear;
    }

    .loader-overlay.visible {
        visibility: visible;
        opacity: 1;
    }

    .progress-container {
        width: 80%;
        max-width: 400px;
        background-color: #e0e0e0;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .progress-bar {
        width: 0%;
        height: 20px;
        background: linear-gradient(90deg, #4361ee, #3f37c9);
        border-radius: 25px;
        text-align: center;
        line-height: 20px;
        color: white;
        font-weight: bold;
        font-size: 0.8rem;
        transition: width 0.4s ease;
    }

    .loader-text {
        margin-top: 1rem;
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
    }
</style>

<div class="upload-container">
    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="progress-container">
            <div class="progress-bar" id="progressBar">0%</div>
        </div>
        <div class="loader-text" id="loaderText">Uploading...</div>
    </div>

    <h2>Upload Image</h2>
    
    <div id="error-container" class="alert alert-danger" style="display: none;"></div>
    
    <form id="uploadForm">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <input type="text" id="tags" name="tags" placeholder="e.g., nature, photography, art">
        </div>

        <div class="upload-area" id="uploadArea">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag & drop your image here or click to browse</p>
            <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max 20MB)</p>
        </div>
        <input type="file" id="file" name="file" accept="image/*" style="display: none;" required>
        
        <div class="preview-container" id="previewContainer">
            <img id="image-preview" alt="Image Preview">
        </div>
        <button type="submit" class="btn">Upload Image</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('uploadArea');
    const imagePreview = document.getElementById('image-preview');
    const previewContainer = document.getElementById('previewContainer');
    const loaderOverlay = document.getElementById('loaderOverlay');
    const progressBar = document.getElementById('progressBar');
    const loaderText = document.getElementById('loaderText');
    const errorContainer = document.getElementById('error-container');

    // Trigger file input click
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.style.borderColor = '#4361ee';
            uploadArea.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '';
        }, false);
    });

    uploadArea.addEventListener('drop', (e) => {
        fileInput.files = e.dataTransfer.files;
        handleFileSelect();
    }, false);

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;

        errorContainer.style.display = 'none'; // Hide previous errors

        if (!file.type.startsWith('image/')) {
            showError('Please select an image file.');
            fileInput.value = ''; // Reset file input
            return;
        }

        previewContainer.style.display = 'block';
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.style.display = 'block';
        uploadArea.style.display = 'none'; // Hide upload area
    }

    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (!fileInput.files[0]) {
            showError('Please select an image to upload.');
            return;
        }

        const xhr = new XMLHttpRequest();

        xhr.open('POST', 'upload.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percentComplete = Math.round((event.loaded / event.total) * 100);
                updateProgress(percentComplete);
            }
        };

        xhr.onloadstart = function() {
            loaderOverlay.classList.add('visible');
            updateProgress(0);
        };

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        loaderText.textContent = 'Processing...';
                        // Smoothly complete the progress bar
                        updateProgress(100, () => {
                            loaderText.textContent = 'Redirecting...';
                            window.location.href = response.redirect;
                        });
                    } else {
                        showError(response.message || 'An error occurred during upload.');
                        hideLoader();
                    }
                } catch (e) {
                    showError('Invalid response from server.');
                    hideLoader();
                }
            } else {
                showError(`Server error: ${xhr.statusText}`);
                hideLoader();
            }
        };

        xhr.onerror = function() {
            showError('A network error occurred. Please try again.');
            hideLoader();
        };

        xhr.send(formData);
    });

    function updateProgress(percent, callback) {
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
        if (percent === 100 && callback) {
            setTimeout(callback, 500); // Give a moment before executing callback
        }
    }

    function showError(message) {
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }


    function hideLoader() {
        loaderOverlay.classList.remove('visible');
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
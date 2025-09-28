<?php
require_once 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    // File upload handling
    if(isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        
        // Validate file
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if(!in_array($file_type, ALLOWED_TYPES)) {
            $error = 'Only JPG, PNG, GIF, and WEBP files are allowed.';
        } elseif($file_size > MAX_FILE_SIZE) {
            $error = 'File size must be less than 5MB.';
        } else {
            // Generate unique filename
            $filename = uniqid() . '.' . $file_ext;
            $filepath = UPLOAD_DIR . $filename;
            
            if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true); // Create directory if it doesn't exist
}

            // Move uploaded file
            if(move_uploaded_file($file['tmp_name'], $filepath)) {
    chmod($filepath, 0644);
                // Insert into database
                $query = "INSERT INTO uploads (user_id, filename, filepath, title, description) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("issss", $_SESSION['user_id'], $filename, $filepath, $title, $description);
                
                if($stmt->execute()) {
                    $_SESSION['success'] = 'Your content has been uploaded successfully!';
                    header("Location: index.php");
                    exit;
                } else {
                    $error = 'Failed to save upload details.';
                    // Delete the uploaded file if DB insert failed
                    unlink($filepath);
                }
            } else {
                $error = 'Failed to upload file.';
            }
        }
    } else {
        $error = 'Please select a file to upload.';
    }
}

require_once 'includes/header.php';
?>

<div class="upload-container">
    <h2>Upload Visual Content</h2>
    
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
        </div>
        
        <div class="upload-area">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag & drop your file here or click to browse</p>
            <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max 5MB)</p>
            <input type="file" id="file" name="file" accept="image/*" style="display: none;" required>
        </div>
        
        <img id="preview" class="upload-preview" alt="Preview">
        
        <button type="submit" class="btn btn-block">Upload</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
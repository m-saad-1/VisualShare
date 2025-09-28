<?php
include 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tags = trim($_POST['tags']);
    $uploaded_by = $_SESSION['username'];
    
    if(empty($title) || empty($description) || !isset($_FILES['image'])) {
        header("Location: upload.php?error=All fields are required");
        exit();
    }
    
    // Handle file upload
    $file = $_FILES['image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Check for errors
    if($file_error !== 0) {
        header("Location: upload.php?error=File upload error");
        exit();
    }
    
    // Check file size (5MB max)
    if($file_size > 5 * 1024 * 1024) {
        header("Location: upload.php?error=File size exceeds 5MB limit");
        exit();
    }
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png'];
    
    if(!in_array($file_ext, $allowed_exts)) {
        header("Location: upload.php?error=Only JPG, JPEG, and PNG files are allowed");
        exit();
    }
    
    // Generate unique filename
    $new_filename = uniqid('', true) . '.' . $file_ext;
    $upload_path = 'assets/uploads/' . $new_filename;
    
    // Move uploaded file
    if(move_uploaded_file($file_tmp, $upload_path)) {
        // Insert image info into database
        $sql = "INSERT INTO images (filename, title, description, tags, uploaded_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $new_filename, $title, $description, $tags, $uploaded_by);
        
        if($stmt->execute()) {
            header("Location: upload.php?success=1");
            exit();
        } else {
            // Delete the uploaded file if DB insert fails
            unlink($upload_path);
            header("Location: upload.php?error=Failed to save image details");
            exit();
        }
    } else {
        header("Location: upload.php?error=Failed to upload file");
        exit();
    }
} else {
    header("Location: upload.php");
    exit();
}
?>
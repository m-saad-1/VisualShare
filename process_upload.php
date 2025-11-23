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
    $user_id = $_SESSION['user_id'];
    
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
    $file_type = mime_content_type($file_tmp);

    // Check for errors
    if($file_error !== 0) {
        header("Location: upload.php?error=File upload error");
        exit();
    }

    // Check file size (20MB max)
    if($file_size > MAX_FILE_SIZE) {
        header("Location: upload.php?error=File size exceeds 20MB limit");
        exit();
    }

    // Check file type
    if(!in_array($file_type, ALLOWED_TYPES)) {
        header("Location: upload.php?error=Only images (JPG, JPEG, PNG, GIF, WEBP) are allowed");
        exit();
    }

    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if(!in_array($file_ext, $allowed_exts)) {
        header("Location: upload.php?error=Invalid file extension");
        exit();
    }

    // Generate unique filename
    $new_filename = uniqid('', true) . '.' . $file_ext;
    $upload_path = UPLOAD_DIR . $new_filename;

    // Move uploaded file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Insert image info into database
        $relative_filepath = 'includes/uploads/' . $new_filename;
        $sql = "INSERT INTO uploads (filepath, filename, title, description, tags, user_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $relative_filepath, $new_filename, $title, $description, $tags, $user_id);
        
        if($stmt->execute()) {
            header("Location: upload.php?success=1");
            exit();
        } else {
            // Delete the uploaded file if DB insert fails
            unlink($upload_path);
            header('Location: upload.php?error=Failed to save file details');
            exit();
        }
    } else {
        header('Location: upload.php?error=Failed to upload file');
        exit();
    }
} else {
    header('Location: upload.php');
    exit();
}

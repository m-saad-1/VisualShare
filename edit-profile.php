<?php
require_once 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Get current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    
    // Validate username
    if(empty($new_username)) {
        $error = 'Username cannot be empty';
    } elseif($new_username != $user['username']) {
        // Check if username already exists
        $check_query = "SELECT id FROM users WHERE username = ? AND id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $new_username, $user_id);
        $check_stmt->execute();
        
        if($check_stmt->get_result()->num_rows > 0) {
            $error = 'Username already taken';
        }
    }

    if(empty($error)) {
        // Handle profile picture upload
        $filepath = null;
        if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['profile_pic'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
            
            if(in_array($file_ext, $allowed_exts)) {
                $filename = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
                $upload_dir = 'assets/profile_pics/';
                $filepath = $upload_dir . $filename;
                
                // Create directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if(move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Delete old profile picture if it exists and is not the default
                    if(!empty($user['profile_pic']) && 
                       $user['profile_pic'] != 'assets/default_profile.jpg' && 
                       file_exists($user['profile_pic'])) {
                        unlink($user['profile_pic']);
                    }
                } else {
                    $error = 'Failed to upload profile picture';
                }
            } else {
                $error = 'Only JPG, PNG, and GIF files are allowed';
            }
        }

        if(empty($error)) {
            // Update database
            if($filepath) {
                // Update both username and profile pic
                $update_query = "UPDATE users SET username = ?, profile_pic = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ssi", $new_username, $filepath, $user_id);
            } else {
                // Update only username
                $update_query = "UPDATE users SET username = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $new_username, $user_id);
            }
            
            if($update_stmt->execute()) {
                if($filepath) {
                    $_SESSION['profile_pic'] = $filepath;
                }
                $_SESSION['username'] = $new_username;
                $success = 'Profile updated successfully!';
                // Refresh user data
                $user['username'] = $new_username;
                $user['profile_pic'] = $filepath ?? $user['profile_pic'];
            } else {
                $error = 'Failed to update profile in database';
                // Clean up file if DB update failed
                if($filepath && file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        }
    }
}


require_once 'includes/header.php';
?>



<style>
/* Edit Profile Styles */
.edit-profile-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.edit-profile-container h1 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.profile-preview {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f9f9f9;
    border-radius: 10px;
}

.profile-avatar {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-avatar {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #3498db;
    color: white;
    font-size: 3rem;
}

.profile-info {
    flex: 1;
}

.profile-info h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.profile-info p {
    color: #7f8c8d;
    margin-bottom: 0.3rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-control:focus {
    border-color: #3498db;
    outline: none;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    font-size: 1rem;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #f0f0f0;
    color: #333;
}

.btn-secondary:hover {
    background: #e0e0e0;
}

.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-input-label {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    background: #f0f0f0;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
    border: 1px dashed #ccc;
    text-align: center;
    width: 100%;
}

.file-input-label:hover {
    background: #e0e0e0;
}

.file-input-label i {
    margin-right: 0.5rem;
}

.file-name {
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: #666;
    display: none;
}

.form-text {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: block;
}

.form-control {
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

@media (max-width: 768px) {
    .profile-preview {
        flex-direction: column;
        text-align: center;
    }

    .btn-group {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}


</style>

<div class="edit-profile-container">
    <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
    
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="profile-preview">
        <div class="profile-avatar" id="profile-avatar">
            <?php if(!empty($user['profile_pic'])): ?>
                <img id="profile-preview-img" src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
            <?php else: ?>
                <div class="default-avatar" id="default-avatar">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h3><?php echo htmlspecialchars($user['username']); ?></h3>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <p>Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?></p>
        </div>
    </div>
    
   <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" 
               value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="profile_pic">Profile Picture</label>
        <div class="file-input-wrapper">
            <label class="file-input-label" for="profile_pic">
                <i class="fas fa-cloud-upload-alt"></i> Choose a new profile picture
            </label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
        </div>
        <div class="file-name" id="file-name">No file chosen</div>
        <small class="form-text">Leave empty to keep current picture</small>
    </div>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>

<script>


document.getElementById('profile_pic').addEventListener('change', function(e) {
    const fileName = document.getElementById('file-name');
    const avatarDiv = document.getElementById('profile-avatar');
    if(this.files.length > 0) {
        fileName.textContent = this.files[0].name;
        fileName.style.display = 'block';

        // Preview the image
        const file = this.files[0];
        if(file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarDiv.innerHTML = '<img id="profile-preview-img" src="' + e.target.result + '" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            };
            reader.readAsDataURL(file);
        }
    } else {
        fileName.style.display = 'none';
        // Reset to original
        <?php if(!empty($user['profile_pic'])): ?>
            avatarDiv.innerHTML = '<img id="profile-preview-img" src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">';
        <?php else: ?>
            avatarDiv.innerHTML = '<div class="default-avatar" id="default-avatar"><i class="fas fa-user"></i></div>';
        <?php endif; ?>
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
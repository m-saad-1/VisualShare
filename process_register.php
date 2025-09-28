<?php
include 'includes/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... [existing validation code] ...
    
    if($stmt->execute()) {
        // Get the new user ID
        $user_id = $stmt->insert_id;
        
        // Log the user in immediately
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        header("Location: index.php");
        exit();
    }
}
?>
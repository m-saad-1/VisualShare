<?php
require_once 'config.php';

// PHP logic for user data and conditional display
$user_id = $_SESSION['user_id'] ?? 0;
$user_data = null;
$initials = '';

if ($user_id) {
    $profile_query = "SELECT profile_pic, username FROM users WHERE id = ? LIMIT 1";
    $profile_stmt = $conn->prepare($profile_query);
    $profile_stmt->bind_param("i", $user_id);
    $profile_stmt->execute();
    $profile_result = $profile_stmt->get_result();
    $user_data = $profile_result->fetch_assoc();

    if (!empty($user_data['username'])) {
        $names = explode(' ', $user_data['username']);
        $initials = strtoupper(substr($names[0], 0, 1));
        if (count($names) > 1) {
            $initials .= strtoupper(substr(end($names), 0, 1));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'VisualShare'; ?></title>
    <link rel="icon" href="/assets/vs-icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Header Styles */
        .header { /* This is the main <header> tag */
            background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Frosted glass effect */
            -webkit-backdrop-filter: blur(10px); /* For Safari */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle white border */
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Keep existing shadow */
            position: sticky;
            top: 15px;
            z-index: 1000;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 30px;
            border-radius: 50px;
        }
        
        .header-container { /* This will be the inner flex container */
            display: flex;
            align-items: center;
            /* Remove justify-content: space-between; */
            /* Remove max-width, margin, padding as they are on .header now */
        }
        
        .header-left, .header-right {
            display: flex;
            align-items: center;
            gap: 10px; /* Gap between icons */
            width: 60px; /* Fixed width for balance */
            flex-shrink: 0; /* Prevent shrinking */
        }
        .header-left {
            justify-content: flex-start;
        }
        .header-right {
            justify-content: flex-end;
        }

        .header-center {
            flex: 1; /* Takes remaining space */
            display: flex;
            justify-content: center; /* Center logo */
            align-items: center;
        }

        /* Logo styles */
        .logo a {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4361ee;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .logo a:hover {
            color: #3a56d4;
        }
        .logo-icon {
            font-size: 1.8rem;
            margin-right: 0.5rem;
        }
        .logo .logo-share {
            color: black;
        }

        /* Icon button styles (from old header, keep as is) */
        /* Re-using .nav-link for general icon styling */
        .nav-link {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center; /* Center icon within its padding */
            padding: 0.6rem; /* Adjusted padding for circular buttons */
            border-radius: 50%; /* Make buttons circular */
            font-size: 0.95rem;
            width: 40px; /* Fixed width for circular button */
            height: 40px; /* Fixed height for circular button */
        }
        .nav-link:hover {
            color: #4361ee;
            background-color: #f0f0f0; /* Add hover background */
        }
        .nav-link i {
            font-size: 1.3rem;
        }

        /* Profile Picture Styles */
        .profile-pic-container {
            /* No margin-left here, gap handles spacing */
        }
        .profile-pic-link {
            display: block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .profile-pic-link:hover {
            border-color: #f5f5f5ff;
            transform: scale(1.05);
        }
        .header-profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .header-avatar-initials {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4361ee;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header {
                padding: 8px 15px;
                border-radius: 30px;
                top: 10px;
            }
            .header-left, .header-right {
                width: auto;
                gap: 5px;
            }
            .logo a {
                font-size: 1.5rem;
            }
            .logo-icon {
                font-size: 1.5rem;
            }
            .nav-link {
                width: 35px;
                height: 35px;
                padding: 0.5rem;
            }
            .nav-link i {
                font-size: 1.1rem;
            }
            .profile-pic-link {
                width: 35px;
                height: 35px;
            }
            .header-avatar-initials {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                padding: 5px 10px;
                border-radius: 25px;
                top: 5px;
            }
            .logo a {
                font-size: 1.3rem;
            }
            .logo-icon {
                font-size: 1.3rem;
            }
            .nav-link {
                width: 30px;
                height: 30px;
                padding: 0.4rem;
            }
            .nav-link i {
                font-size: 1rem;
            }
            .profile-pic-link {
                width: 30px;
                height: 30px;
            }
            .header-avatar-initials {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <a href="index.php" class="nav-link animated-icon" title="Home" data-icon="home">
                    <i class="fas fa-home"></i>
                </a>
            </div>
            <div class="header-center">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-camera logo-icon"></i>
                        <span>Visual</span><span class="logo-share">Share</span>
                    </a>
                </div>
            </div>
            <div class="header-right">
                <?php if(isset($_SESSION['user_id'])): ?>
               
                    <div class="profile-pic-container">
                        <a href="dashboard.php" class="profile-pic-link" title="Dashboard" >
                            <?php if(!empty($user_data['profile_pic'])): ?>
                                <img src="<?php echo htmlspecialchars($user_data['profile_pic']); ?>" 
                                     alt="Profile Picture" class="header-profile-pic">
                            <?php else: ?>
                                <div class="header-avatar-initials">
                                    <?php echo $initials; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="nav-link" title="Login">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                    <a href="register.php" class="nav-link" title="Register">
                        <i class="fas fa-user-plus"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-content">
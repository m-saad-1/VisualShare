<?php
require_once 'includes/config.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$profile_id = intval($_GET['id']);
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Get user information (without email for other users)
$select_fields = $current_user_id == $profile_id ? 
    "username, email, created_at, gender, profile_pic" : 
    "username, created_at, gender, profile_pic";
    
$user_query = "SELECT $select_fields FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $profile_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if($user_result->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$user = $user_result->fetch_assoc();


// Check if current user is following this profile
$is_following = false;
if($current_user_id > 0) {
    $follow_query = "SELECT 1 FROM user_follows WHERE follower_id = ? AND following_id = ?";
    $follow_stmt = $conn->prepare($follow_query);
    $follow_stmt->bind_param("ii", $current_user_id, $profile_id);
    $follow_stmt->execute();
    $is_following = $follow_stmt->get_result()->num_rows > 0;
}

// Get follower/following counts
$follower_count = 0;
$following_count = 0;
$count_stmt = $conn->prepare("SELECT 
    (SELECT COUNT(*) FROM user_follows WHERE following_id = ?) as followers,
    (SELECT COUNT(*) FROM user_follows WHERE follower_id = ?) as following");
$count_stmt->bind_param("ii", $profile_id, $profile_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
if($count_result) {
    $follower_count = $count_result['followers'];
    $following_count = $count_result['following'];
}

// Get user's uploads
$uploads_query = "SELECT *, 
                CASE 
                    WHEN thumbnail_path IS NOT NULL AND thumbnail_path != '' THEN thumbnail_path
                    WHEN filename REGEXP '\\.(mp4|mov|avi|webm)$' THEN 'assets/video-thumbnail.jpg'
                    ELSE filepath
                END AS display_image
                FROM uploads 
                WHERE user_id = ? 
                ORDER BY upload_date DESC";
$uploads_stmt = $conn->prepare($uploads_query);
$uploads_stmt->bind_param("i", $profile_id);
$uploads_stmt->execute();
$uploads = $uploads_stmt->get_result();

// Generate initials for avatar
$initials = '';
if(!empty($user['username'])) {
    $names = explode(' ', $user['username']);
    $initials = strtoupper(substr($names[0], 0, 1));
    if(count($names) > 1) {
        $initials .= strtoupper(substr(end($names), 0, 1));
    }
}

require_once 'includes/header.php';
?>

<style>
/* Dashboard Styles */
.dashboard-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

/* Profile Section */
.profile-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 2rem;
    margin-bottom: 2rem;
}

.profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.profile-avatar {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.avatar-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.profile-meta h1 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.profile-email {
    color: #666;
    margin-bottom: 0.5rem;
}

.profile-join-date {
    color: #888;
    font-size: 0.9rem;
}

.profile-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s ease;
    height: 40px;
    box-sizing: border-box;
    border: none;
    cursor: pointer;
}

.btn i {
    font-size: 14px;
    width: 14px;
    text-align: center;
}

.btn-follow {
    background: #3498db;
    color: white;
    border: 1px solid #2980b9;
}

.btn-follow:hover {
    background: #2980b9;
}

.btn-following {
    background: #2ecc71;
    border-color: #27ae60;
}

.btn-following:hover {
    background: #27ae60;
}

.profile-stats {
    display: flex;
    gap: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.stat-card {
    text-align: center;
    padding: 1rem 1.5rem;
    background: #f9f9f9;
    border-radius: 8px;
    min-width: 100px;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Content Section */
.content-section {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    font-size: 1.5rem;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.content-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    background: #fff;
}

.content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.card-media {
    position: relative;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.content-card:hover .card-media img {
    transform: scale(1.05);
}

.video-thumbnail {
    position: relative;
    height: 100%;
}

.video-badge {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.7);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.card-hover-actions {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}

.content-card:hover .card-hover-actions {
    opacity: 1;
}

.btn-view {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-view:hover {
    background: white;
    transform: scale(1.1);
}

.card-footer {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid #eee;
}

.card-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.content-title {
    font-weight: 600;
    font-size: 0.95rem;
    color: #2c3e50;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.upload-date {
    font-size: 0.8rem;
    color: #7f8c8d;
}

/* Avatar with Initials */
.avatar-with-initials {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    color: white;
    background: #3498db;
    border: 3px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Gender-specific avatars */
.avatar-male {
    background: #3498db;
}

.avatar-female {
    background: #e91e63;
}

.avatar-other {
    background: #9c27b0;
}

/* Video thumbnail fallback */
.video-thumbnail-fallback {
    width: 100%;
    height: 100%;
    background: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 3rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.3rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #7f8c8d;
    margin-bottom: 1.5rem;
}

.btn-primary {
    background: #3498db;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #2980b9;
}

/* Keep follow button in place below 768px */
@media (max-width: 768px) {
    .profile-header {
        flex-wrap: nowrap;
        align-items: center;
    }
    
    .profile-actions {
        position: static !important;
        width: auto !important;
        margin-left: auto;
    }
    
    .profile-stats {
        flex-wrap: wrap;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

/* Gradually decrease stats size and profile elements for medium screens */
@media (max-width: 768px) {
    .avatar-img,
    .avatar-with-initials {
        width: 80px;
        height: 80px;
    }
    
    .avatar-with-initials {
        font-size: 1.6rem;
    }
    
    .profile-meta h1 {
        font-size: 1.5rem;
    }
    
    .profile-join-date {
        font-size: 0.85rem;
    }
    
    .stat-card {
        padding: 0.8rem 1.2rem;
        min-width: 80px;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
    }
}

/* Further decrease stats size and profile elements for smaller screens */
@media (max-width: 600px) {
    .avatar-img,
    .avatar-with-initials {
        width: 70px;
        height: 70px;
    }
    
    .avatar-with-initials {
        font-size: 1.4rem;
    }
    
    .profile-meta h1 {
        font-size: 1.3rem;
    }
    
    .profile-join-date {
        font-size: 0.8rem;
    }
    
    .profile-avatar {
        gap: 1.2rem;
    }
    
    .stat-card {
        padding: 0.6rem 1rem;
        min-width: 70px;
    }
    
    .stat-value {
        font-size: 1.3rem;
    }
    
    .stat-label {
        font-size: 0.75rem;
    }
}

/* Below 500px: circle follow button showing only the icon and smallest stats */
@media (max-width: 500px) {
    .avatar-img,
    .avatar-with-initials {
        width: 60px;
        height: 60px;
    }
    
    .avatar-with-initials {
        font-size: 1.2rem;
    }
    
    .profile-meta h1 {
        font-size: 1.1rem;
    }
    
    .profile-join-date {
        font-size: 0.75rem;
    }
    
    .profile-avatar {
        gap: 1rem;
    }
    
    .profile-actions .btn-follow,
    .profile-actions .btn-following {
        width: 40px;
        height: 40px;
        padding: 0;
        border-radius: 50%;
        justify-content: center;
        font-size: 0; /* Hide text */
        position: relative;
    }
    
    .profile-actions .btn-follow i,
    .profile-actions .btn-following i {
        font-size: 18px;
        width: auto;
        margin: 0;
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .stat-card {
        padding: 0.5rem 0.8rem;
        min-width: 60px;
    }
    
    .stat-value {
        font-size: 1.1rem;
    }
    
    .stat-label {
        font-size: 0.7rem;
    }
}

/* Very small screens - compact stats and profile elements */
@media (max-width: 400px) {
    .avatar-img,
    .avatar-with-initials {
        width: 50px;
        height: 50px;
    }
    
    .avatar-with-initials {
        font-size: 1rem;
    }
    
    .profile-meta h1 {
        font-size: 1rem;
    }
    
    .profile-join-date {
        font-size: 0.7rem;
    }
    
    .profile-avatar {
        gap: 0.8rem;
    }
    
    .profile-stats {
        gap: 1rem;
    }
    
    .stat-card {
        padding: 0.4rem 0.6rem;
        min-width: 50px;
    }
    
    .stat-value {
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
    }
}

/* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 10000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.3s;
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    position: relative;
    width: 90%;
    max-width: 400px;
    text-align: center;
    animation: slideIn 0.3s forwards;
}

.modal-content h2 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-size: 1.8rem;
}

.modal-content p {
    margin-bottom: 25px;
    color: #555;
    line-height: 1.5;
}

.close-button {
    color: #aaa;
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-button:hover,
.close-button:focus {
    color: #333;
    text-decoration: none;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.modal-actions .btn {
    padding: 10px 25px;
    font-size: 1rem;
    border-radius: 5px;
}

.modal-actions .btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.modal-actions .btn-secondary {
    background-color: #6c757d;
    color: white;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<div class="dashboard-container">
    <!-- User Profile Section -->
    <section class="profile-section">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if(!empty($user['profile_pic'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" 
                         alt="Profile Picture" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-with-initials <?php echo 'avatar-' . ($user['gender'] ?? 'other'); ?>">
                        <?php echo $initials; ?>
                    </div>
                <?php endif; ?>
                <div class="profile-meta">
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    
                    <p class="profile-join-date">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            <?php if($current_user_id > 0 && $current_user_id != $profile_id): ?>
                <div class="profile-actions">
                    <button class="btn <?php echo $is_following ? 'btn-following' : 'btn-follow'; ?>" 
                            id="followButton" 
                            data-user-id="<?php echo $profile_id; ?>">
                        <i class="fas fa-<?php echo $is_following ? 'check' : 'user-plus'; ?>"></i>
                        <span><?php echo $is_following ? 'Following' : 'Follow'; ?></span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-value"><?php echo $uploads->num_rows; ?></div>
                <div class="stat-label">Uploads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $follower_count; ?></div>
                <div class="stat-label">Followers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $following_count; ?></div>
                <div class="stat-label">Following</div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-photo-video"></i> <?php echo htmlspecialchars($user['username']); ?>'s Content</h2>
        </div>

        <?php if($uploads->num_rows > 0): ?>
            <div class="content-grid">
                <?php while($upload = $uploads->fetch_assoc()): 
                    $is_video = preg_match('/\.(mp4|mov|avi|webm)$/i', $upload['filename']);
                    $display_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $upload['filepath']);
                    $thumbnail_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $upload['display_image']);
                ?>
                    <div class="content-card" data-id="<?php echo $upload['id']; ?>">
                        <div class="card-media" onclick="window.location.href='view.php?id=<?php echo $upload['id']; ?>'">
                            <?php if($is_video): ?>
                                <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . $thumbnail_path)): ?>
                                    <img src="<?php echo $thumbnail_path; ?>" alt="Video thumbnail">
                                <?php else: ?>
                                    <div class="video-thumbnail-fallback">
                                        <i class="fas fa-play"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="video-badge">
                                    <i class="fas fa-play"></i>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo $display_path; ?>" alt="<?php echo htmlspecialchars($upload['title']); ?>">
                            <?php endif; ?>
                            <div class="card-hover-actions">
                                <a href="view.php?id=<?php echo $upload['id']; ?>" class="btn-view">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-content">
                                <div class="content-title" title="<?php echo htmlspecialchars($upload['title']); ?>">
                                    <?php echo htmlspecialchars($upload['title']); ?>
                                </div>
                                <div class="upload-date">
                                    <?php echo date('M d, Y', strtotime($upload['upload_date'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h3>No Content Yet</h3>
                <p><?php echo htmlspecialchars($user['username']); ?> hasn't uploaded any content yet.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButton = document.getElementById('followButton');
    if (followButton) {
        followButton.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const isFollowing = this.classList.contains('btn-following');
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('action', isFollowing ? 'unfollow' : 'follow');
            
            fetch('includes/follow_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(isFollowing) {
                        this.classList.remove('btn-following');
                        this.classList.add('btn-follow');
                        this.innerHTML = '<i class="fas fa-user-plus"></i><span>Follow</span>';
                        // Update follower count
                        const followerCount = document.querySelector('.profile-stats .stat-card:nth-child(2) .stat-value');
                        if(followerCount) {
                            followerCount.textContent = parseInt(followerCount.textContent) - 1;
                        }
                    } else {
                        this.classList.remove('btn-follow');
                        this.classList.add('btn-following');
                        this.innerHTML = '<i class="fas fa-check"></i><span>Following</span>';
                        // Update follower count
                        const followerCount = document.querySelector('.profile-stats .stat-card:nth-child(2) .stat-value');
                        if(followerCount) {
                            followerCount.textContent = parseInt(followerCount.textContent) + 1;
                        }
                    }
                } else {
                    if (data.message && data.message.includes('Unauthorized')) {
                        showLoginModal();
                    } else {
                        console.error('Error:', data.message);
                        alert('Error: ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }

    function showLoginModal() {
        const authModal = document.getElementById('authModal');
        if (authModal) {
            authModal.style.display = 'flex'; // Use flex to center content
        }
    }

    // Close modal when close button is clicked
    document.querySelector('#authModal .close-button').addEventListener('click', function() {
        document.getElementById('authModal').style.display = 'none';
    });

    // Close modal when clicking outside of the modal content
    window.addEventListener('click', function(event) {
        const authModal = document.getElementById('authModal');
        if (event.target === authModal) {
            authModal.style.display = 'none';
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>

<!-- Login/Register Modal -->
<div id="authModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Join Our Community!</h2>
        <p>Like, save, and follow to connect with creators and discover more amazing content.</p>
        <div class="modal-actions">
            <a href="login.php" class="btn btn-primary">Log In</a>
            <a href="register.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>
</div>
<?php

require_once 'includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user information with profile pic
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, created_at, gender, profile_pic, bio, location FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Generate initials for avatar fallback
$initials = '';
if(!empty($user['username'])) {
    $names = explode(' ', $user['username']);
    $initials = strtoupper(substr($names[0], 0, 1));
    if(count($names) > 1) {
        $initials .= strtoupper(substr(end($names), 0, 1));
    }
}

// Get follower/following counts
$follower_count = 0;
$following_count = 0;
$count_stmt = $conn->prepare("SELECT 
    (SELECT COUNT(*) FROM user_follows WHERE following_id = ?) as followers,
    (SELECT COUNT(*) FROM user_follows WHERE follower_id = ?) as following");
$count_stmt->bind_param("ii", $user_id, $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
if($count_result) {
    $follower_count = $count_result['followers'];
    $following_count = $count_result['following'];
}

// Get recent activity with follower names
$activity_stmt = $conn->prepare("
    (SELECT 'upload' as type, u.id, u.title, u.upload_date as date, NULL as follower_name 
     FROM uploads u WHERE u.user_id = ? ORDER BY u.upload_date DESC LIMIT 3)
    UNION ALL
    (SELECT 'follow' as type, uf.follower_id as id, NULL as title, uf.follow_date as date, u.username as follower_name 
     FROM user_follows uf 
     JOIN users u ON uf.follower_id = u.id 
     WHERE uf.following_id = ? ORDER BY uf.follow_date DESC LIMIT 3)
    ORDER BY date DESC LIMIT 5
");
$activity_stmt->bind_param("ii", $user_id, $user_id);
$activity_stmt->execute();
$recent_activity = $activity_stmt->get_result();

// Check if we're showing saved content or user's own content
$show_saved = isset($_GET['view']) && $_GET['view'] === 'saved';
$view_type = $show_saved ? 'saved' : 'uploads';

// Get user's uploads or saved content with proper thumbnails and filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Check if it's an AJAX request for content
$is_ajax_request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($is_ajax_request && isset($_GET['ajax_content'])) {
    // Only process content if it's an AJAX request for content
    $content_type = $_GET['content_type'] ?? 'uploads';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;
    
    if ($content_type === 'saved') {
        // Query for saved content
        $uploads_query = "SELECT u.*, 
                        CASE 
                            WHEN u.thumbnail_path IS NOT NULL AND u.thumbnail_path != '' THEN u.thumbnail_path
                            WHEN u.filename REGEXP '\\.(mp4|mov|avi|webm)$' THEN 'assets/video-thumbnail.jpg'
                            ELSE u.filepath
                        END AS display_image,
                        GROUP_CONCAT(t.name) as tags,
                        1 as is_favorite,
                        users.username,
                        users.profile_pic as uploader_profile_pic
                        FROM uploads u
                        JOIN user_favorites uf ON u.id = uf.upload_id
                        JOIN users ON u.user_id = users.id
                        LEFT JOIN upload_tags ut ON u.id = ut.upload_id
                        LEFT JOIN tags t ON ut.tag_id = t.id
                        WHERE uf.user_id = ?";
    } else {
        // Query for user's own content
        $uploads_query = "SELECT u.*, 
                        CASE 
                            WHEN u.thumbnail_path IS NOT NULL AND u.thumbnail_path != '' THEN u.thumbnail_path
                            WHEN u.filename REGEXP '\\.(mp4|mov|avi|webm)$' THEN 'assets/video-thumbnail.jpg'
                            ELSE u.filepath
                        END AS display_image,
                        GROUP_CONCAT(t.name) as tags,
                        EXISTS(SELECT 1 FROM user_favorites WHERE user_id = ? AND upload_id = u.id) as is_favorite
                        FROM uploads u
                        LEFT JOIN upload_tags ut ON u.id = ut.upload_id
                        LEFT JOIN tags t ON ut.tag_id = t.id
                        WHERE u.user_id = ?";
    }

    if(!empty($search)) {
        $uploads_query .= " AND (u.title LIKE ? OR u.description LIKE ?)";
    }

    if(!empty($type_filter)) {
        if($type_filter === 'image') {
            $uploads_query .= " AND u.filename REGEXP '\\.(jpg|jpeg|png|gif|webp)$'";
        } elseif($type_filter === 'video') {
            $uploads_query .= " AND u.filename REGEXP '\\.(mp4|mov|avi|webm)$'";
        }
    }

    $uploads_query .= " GROUP BY u.id";

    // Add sorting
    if($sort === 'oldest') {
        $uploads_query .= " ORDER BY u.upload_date ASC";
    } else {
        $uploads_query .= " ORDER BY u.upload_date DESC";
    }

    // Get total count for pagination
    if ($content_type === 'saved') {
        $count_query = "SELECT COUNT(*) as total 
                       FROM user_favorites uf 
                       JOIN uploads u ON uf.upload_id = u.id 
                       WHERE uf.user_id = ?";
    } else {
        $count_query = "SELECT COUNT(*) as total FROM uploads WHERE user_id = ?";
    }

    if(!empty($search)) {
        $count_query .= " AND (title LIKE ? OR description LIKE ?)";
    }

    $count_stmt = $conn->prepare($count_query);
    if(!empty($search)) {
        $search_param = "%$search%";
        if ($content_type === 'saved') {
            $count_stmt->bind_param("iss", $user_id, $search_param, $search_param);
        } else {
            $count_stmt->bind_param("iss", $user_id, $search_param, $search_param);
        }
    } else {
        $count_stmt->bind_param("i", $user_id);
    }
    $count_stmt->execute();
    $total_result = $count_stmt->get_result()->fetch_assoc();
    $total_uploads = $total_result['total'];
    $total_pages = ceil($total_uploads / $per_page);

    // Apply pagination to main query
    $uploads_query .= " LIMIT ? OFFSET ?";

    $uploads_stmt = $conn->prepare($uploads_query);
    if (!$uploads_stmt) {
        die(json_encode(['error' => "Error preparing statement: " . $conn->error]));
    }

    if ($content_type === 'saved') {
        if(!empty($search)) {
            $search_param = "%$search%";
            $uploads_stmt->bind_param("issii", 
                $user_id,  
                $search_param,
                $search_param,
                $per_page,
                $offset
            );
        } else {
            $uploads_stmt->bind_param("iii", 
                $user_id,  
                $per_page,
                $offset
            );
        }
    } else {
        if(!empty($search)) {
            $search_param = "%$search%";
            $uploads_stmt->bind_param("iissii", 
                $user_id,  
                $user_id,  
                $search_param,
                $search_param,
                $per_page,
                $offset
            );
        } else {
            $uploads_stmt->bind_param("iiii", 
                $user_id,  
                $user_id,  
                $per_page,
                $offset
            );
        }
    }

    if (!$uploads_stmt->execute()) {
        die(json_encode(['error' => "Error executing statement: " . $uploads_stmt->error]));
    }
    $uploads = $uploads_stmt->get_result();
    
    // Prepare data for AJAX response
    $content_data = [];
    while($upload = $uploads->fetch_assoc()) {
        $content_data[] = $upload;
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'content' => $content_data,
        'total_uploads' => $total_uploads,
        'total_pages' => $total_pages,
        'current_page' => $page
    ]);
    exit;
}





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action'])) {
    if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
        // Decode the JSON string to get the array of IDs
        $selected_ids = json_decode($_POST['selected_items'], true);
        
        if (is_array($selected_ids) && !empty($selected_ids)) {
            // Verify ownership and delete each item
            $deleted_count = 0;
            foreach ($selected_ids as $id) {
                $id = intval($id);
                
                // Check ownership first
                $check_stmt = $conn->prepare("SELECT user_id, filepath, thumbnail_path FROM uploads WHERE id = ?");
                $check_stmt->bind_param("i", $id);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $content = $result->fetch_assoc();
                    
                    // Verify ownership
                    if ($content['user_id'] == $user_id) {
                        // Delete files from server - fix file paths
                        $base_path = $_SERVER['DOCUMENT_ROOT'];
                        
                        if (!empty($content['filepath']) && file_exists($base_path . $content['filepath'])) {
                            unlink($base_path . $content['filepath']);
                        }
                        if (!empty($content['thumbnail_path']) && file_exists($base_path . $content['thumbnail_path'])) {
                            unlink($base_path . $content['thumbnail_path']);
                        }
                        
                        // Delete from database
                        $delete_stmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
                        $delete_stmt->bind_param("i", $id);
                        if ($delete_stmt->execute()) {
                            $deleted_count++;
                        }
                    }
                }
            }
            
            // Set success message
            $_SESSION['success_message'] = "Successfully deleted $deleted_count items.";
        } else {
            $_SESSION['error_message'] = "No valid items selected for deletion.";
        }
    } else {
        $_SESSION['error_message'] = "No items selected for deletion.";
    }
    
    // Redirect back to dashboard
    header("Location: dashboard.php");
    exit;
}




require_once 'includes/header.php';
?>
<style>
:root {
    --primary-color: #4361ee;
    --primary-light: #3f37c9;
    --secondary-color: #3a0ca3;
    --accent-color: #7209b7;
    --dark-color: #1a1a2e;
    --light-color: #f8f9fa;
    --success-color: #4cc9f0;
    --warning-color: #f8961e;
    --danger-color: #f94144;
    --gray-color: #6c757d;
    --light-gray: #e9ecef;
    --border-radius: 8px;
    --box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    --transition: all 0.3s ease;
}

body {
    background-color: var(--light-color);
    color: var(--dark-color);
    transition: var(--transition);
}

.dashboard-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.dashboard-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.5rem;
}

@media (max-width: 1200px) {
    .dashboard-container {
        max-width: 1200px;
        padding: 1.5rem 1rem;
    }

    .profile-section, .content-section, .sidebar-section {
        padding: 1.5rem;
    }

    .avatar-img {
        width: 90px;
        height: 90px;
    }

    .profile-meta h1 {
        font-size: 1.6rem;
    }

    .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }

    .card-media {
        height: 180px;
    }

    .stat-value {
        font-size: 1.6rem;
    }

    .sidebar-title {
        font-size: 1.1rem;
    }
}

@media (max-width: 992px) {
    .dashboard-layout {
        grid-template-columns: 1fr;
    }

    .dashboard-container {
        max-width: 1000px;
        padding: 1rem 0.5rem;
        margin-top: -80px; /* Overlap the header */
        position: relative;
        z-index: 999; /* Lower than header's z-index of 1000 */
    }

    .profile-section, .content-section, .sidebar-section {
        padding: 1.25rem;
    }

    .profile-section {
        margin-bottom: 0;
    }

    .content-section {
        margin-top: 1rem;
    }

    .avatar-img {
        width: 80px;
        height: 80px;
    }

    .profile-meta h1 {
        font-size: 1.5rem;
    }

    .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .card-media {
        height: 160px;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .sidebar-title {
        font-size: 1rem;
    }
}

.profile-section {
    background: #fff;
    border-radius: var(--border-radius);
    padding: 2rem;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    margin-bottom: 1rem;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.profile-actions {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    display: flex;
    gap: 12px;
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
}

.profile-meta h1 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.profile-email {
    color: var(--gray-color);
    margin-bottom: 0.5rem;
}

.profile-join-date {
    color: var(--gray-color);
    font-size: 0.9rem;
}

.profile-bio {
    margin-top: 1.5rem;
    color: var(--dark-color);
    line-height: 1.6;
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
    transition: var(--transition);
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

.btn-edit {
    background: var(--light-gray);
    color: var(--dark-color);
    border: 1px solid #d1d5db;
}

.btn-edit:hover {
    background: #e5e7eb;
    border-color: #c1c5cb;
}

.btn-logout {
    background: var(--danger-color);
    color: white;
    border: 1px solid #dc2626;
}

.btn-logout:hover {
    background: #dc2626;
}

.btn-upload {
    background: var(--primary-color);
    color: white;
    border: 1px solid var(--primary-light);
}

.btn-upload:hover {
    background: var(--primary-light);
}

.profile-stats {
    display: flex;
    gap: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--light-gray);
}

.stat-card {
    text-align: center;
    padding: 1rem 1.5rem;
    background: var(--light-gray);
    border-radius: var(--border-radius);
    min-width: 100px;
    flex: 1;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stat-label {
    font-size: 0.9rem;
    color: var(--gray-color);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.content-section {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2rem;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
}

.section-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.section-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.view-toggle {
    display: flex;
    background: none;
    border-radius: var(--border-radius);
    padding: 4px;
    margin-bottom: 1rem;
}

.view-toggle-btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
}

.view-toggle-btn.active {
    background: var(--primary-color);
    color: white;
}

.search-filter-container {
    margin-top: 1rem;
    margin-bottom: 1.5rem;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.content-card {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: var(--transition);
    background: #fff;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
}

.content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.card-media {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
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
    transition: var(--transition);
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
    transition: var(--transition);
}

.btn-view:hover {
    background: white;
    transform: scale(1.1);
}

.card-footer {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid var(--light-gray);
}

.card-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.content-title {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--dark-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.upload-date {
    font-size: 0.8rem;
    color: var(--gray-color);
}

.card-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-gray);
    border: none;
    cursor: pointer;
    transition: var(--transition);
}

.btn-action:hover {
    background: #e0e0e0;
}

.btn-edit-action {
    color: var(--primary-color);
}

.btn-delete-action {
    color: var(--danger-color);
}

.btn-favorite-action {
    color: var(--warning-color);
}

.btn-favorite-action.favorited {
    color: #ffc107;
}

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
    border: 3px solid #fff;
}

.avatar-male {
    background: var(--primary-color);
}

.avatar-female {
    background: var(--accent-color);
}

.avatar-other {
    background: var(--secondary-color);
}

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

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    grid-column: 1 / -1;
}

.empty-icon {
    font-size: 3rem;
    color: var(--gray-color);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.3rem;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--gray-color);
    margin-bottom: 1.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: var(--primary-light);
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.modal.active {
    display: flex;
    opacity: 1;
}

.modal-content {
    background: #fff;
    border-radius: var(--border-radius);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    transform: translateY(-20px);
    transition: var(--transition);
}

.modal.active .modal-content {
    transform: translateY(0);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--light-gray);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--dark-color);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray-color);
    padding: 0;
    line-height: 1;
}

.modal-close:hover {
    color: var(--dark-color);
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--light-gray);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--dark-color);
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--light-gray);
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
}

.follow-modal-content {
    max-height: 70vh;
    overflow-y: auto;
}

.follow-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.follow-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    background: var(--light-gray);
    transition: var(--transition);
}

.follow-item:hover {
    background: #f0f0f0;
}

.follow-user {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    flex-grow: 1;
}

.follow-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.follow-avatar-initials {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    background: var(--primary-color);
}

.follow-username {
    font-weight: 500;
}

.follow-back {
    font-size: 0.8rem;
    color: var(--gray-color);
}

.btn-follow-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    height: auto;
}

.no-follows {
    text-align: center;
    padding: 2rem;
    color: var(--gray-color);
}

.loading-spinner {
    display: none;
    text-align: center;
    padding: 1rem;
}

.loading-spinner i {
    animation: spin 1s linear infinite;
    color: var(--primary-color);
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.stat-card.clickable {
    cursor: pointer;
    transition: var(--transition);
}

.stat-card.clickable:hover {
    background: #f5f5f5;
}

.btn-follow {
    background: var(--primary-color);
    color: white;
    border: 1px solid var(--primary-light);
}

.btn-follow:hover {
    background: var(--primary-light);
}

.btn-following {
    background: var(--success-color);
    border-color: #27ae60;
    color: white;
}

.btn-following:hover {
    background: #27ae60;
}

.search-filter-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 250px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 40px;
    border-radius: var(--border-radius);
    border: 1px solid var(--light-gray);
    background: #fff;
    color: var(--dark-color);
    transition: var(--transition);
}

.search-box input:focus {
    border-color: var(--primary-color);
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-color);
}

.filter-select {
    min-width: 150px;
    padding: 0.8rem 1rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--light-gray);
    background: #fff;
    color: var(--dark-color);
    transition: var(--transition);
}

.filter-select:focus {
    border-color: var(--primary-color);
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    gap: 0.5rem;
}

.page-item {
    list-style: none;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    background: var(--light-gray);
    color: var(--dark-color);
    text-decoration: none;
    transition: var(--transition);
}

.page-link:hover, .page-link.active {
    background: var(--primary-color);
    color: white;
}

.page-link.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.tag-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.tag {
    display: inline-block;
    padding: 2px 8px;
    background: var(--light-gray);
    color: var(--dark-color);
    border-radius: 12px;
    font-size: 0.75rem;
    white-space: nowrap;
    flex-shrink: 0;
}

.tag:hover {
    background: var(--primary-color);
    color: white;
}

.tag-container {
    display: flex;
    gap: 4px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 100%;
    margin-top: 4px;
}

.analytics-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.analytics-badge i {
    font-size: 0.7rem;
}

.btn-favorite-action {
    color: var(--gray-color);
    transition: var(--transition);
}

.btn-favorite-action:hover {
    color: var(--danger-color);
}

.btn-favorite-action.favorited {
    color: var(--primary-color);
}

.btn-favorite-action.favorited:hover {
    color: var(--primary-light);
}

.btn-favorite-action .fa-bookmark {
    font-size: 16px;
}

.sidebar-section {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0,0,0,0.05);
}

.sidebar-title {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: var(--border-radius);
    background: var(--light-gray);
    transition: var(--transition);
}

.activity-item:hover {
    background: #f0f0f0;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-color);
    color: white;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-text {
    font-size: 0.9rem;
    color: var(--dark-color);
}

.activity-date {
    font-size: 0.75rem;
    color: var(--gray-color);
    margin-top: 0.25rem;
}

.bulk-actions {
    display: none;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--light-gray);
    border-radius: var(--border-radius);
}

.bulk-actions.active {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.bulk-count {
    font-size: 0.9rem;
    color: var(--dark-color);
    margin-right: auto;
}

.checkbox-select {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    z-index: 2;
    opacity: 0;
    cursor: pointer;
}

.checkbox-custom {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: white;
    border: 2px solid var(--light-gray);
    z-index: 1;
    transition: var(--transition);
}

.checkbox-select:checked ~ .checkbox-custom {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.checkbox-select:checked ~ .checkbox-custom::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
}
/* Hide selection controls by default; show on hover or when selected */
.tabs-content-wrapper .checkbox-custom { display: none; }
.tabs-content-wrapper .content-card:hover .checkbox-custom,
.tabs-content-wrapper .checkbox-select:checked ~ .checkbox-custom { display: block; }

.content-card.selected {
    border: 2px solid var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.2);
}

.uploader-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    font-size: 0.8rem;
    color: var(--gray-color);
}

.uploader-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.uploader-avatar-initials {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    font-weight: bold;
    color: white;
    background: var(--primary-color);
}

.bulk-delete-btn {
    background: #ffffff !important;
    color: var(--danger-color) !important;
    border: 1px solid var(--danger-color) !important;
}

.bulk-delete-btn:hover {
    background: var(--danger-color) !important;
    color: white !important;
}

@media (max-width: 768px) {
    .dashboard-container {
        max-width: 100%;
        padding: 0.75rem 0.25rem;
    }

    .profile-section, .content-section, .sidebar-section {
        padding: 1rem;
    }

    .profile-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.25rem;
    }

    .profile-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: auto;
        justify-content: flex-end;
        gap: 8px;
    }
    .section-title-row {
        padding-right: 120px; /* space for button */
    }
     .btn-upload {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
    }

    .btn-edit span, .btn-logout span {
        display: none;
    }

    .btn-edit, .btn-logout {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        justify-content: center;
        padding: 0;
    }

    .profile-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }

    .avatar-img {
        width: 70px;
        height: 70px;
    }

    .profile-meta h1 {
        font-size: 1.4rem;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .search-filter-container {
        flex-direction: row;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .search-box {
        flex: 2;
        min-width: 0;
    }

    .search-box input {
        padding: 0.6rem 0.8rem 0.6rem 35px;
        font-size: 0.9rem;
    }

    .filter-select {
        flex: 1;
        min-width: 0;
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
    }

    .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.5rem;
    }

    .card-media {
        height: 140px;
    }

    .stat-value {
        font-size: 1.4rem;
    }

    .sidebar-title {
        font-size: 0.95rem;
    }

    .modal-content {
        width: 95%;
    }

    .modal-footer .btn-logout {
        width: auto;
        height: auto;
        border-radius: 6px; /* Assuming this is the default border-radius for buttons */
        padding: 10px 16px; /* Restore original padding */
    }
}

@media (max-width: 500px) {

.search-filter-container {

flex-direction: column;

gap: 10px;

padding: 0 1rem; /* Equal spacing on both sides */

box-sizing: border-box; /* Include padding in width */

}

.search-box {

width: 100%;

min-width: 100%;

}

.search-box input {

width: 100%;

}

.filter-select-wrapper {

display: flex;

justify-content: space-between;

width: 100%;

gap: 1rem;

}

.filter-select-wrapper select.filter-select {

flex: 1 1 0; /* Fill equally */

min-width: 0;

}

}

@media (max-width: 480px) {
    .dashboard-container {
        max-width: 100%;
        padding: 0.5rem 0.125rem;
    }

    .profile-section, .content-section, .sidebar-section {
        padding: 0.75rem;
    }

    .profile-header {
        gap: 1rem;
    }

    .avatar-img {
        width: 60px;
        height: 60px;
    }

    .profile-meta h1 {
        font-size: 1.3rem;
    }

    .profile-email, .profile-join-date {
        font-size: 0.85rem;
    }

    .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.375rem;
    }

    .card-media {
        height: 120px;
    }

    .content-title {
        font-size: 0.9rem;
    }

    .upload-date {
        font-size: 0.75rem;
    }

    .stat-value {
        font-size: 1.3rem;
    }

    .stat-label {
        font-size: 0.85rem;
    }

    .sidebar-title {
        font-size: 0.9rem;
    }

    .btn span {
        display: none;
    }

    .btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        justify-content: center;
        padding: 0;
        font-size: 0.9rem;
    }

    .btn i {
        margin: 0;
        font-size: 14px;
    }

    /* Keep primary buttons (Upload Now, Browse Content) normal shape below 500px */
    .btn-primary {
        width: auto !important;
        height: auto !important;
        border-radius: 6px !important;
        padding: 0.7rem 1.5rem !important;
    }

    .btn-primary span {
        display: inline !important;
    }

    .modal-content {
        width: 98%;
        max-width: 98%;
    }

    .modal-footer {
        flex-direction: column;
    }

    .modal-footer .btn {
        width: 100%;
    }

    .search-filter-container {
        flex-direction: column;
        gap: 0.5rem;
    }

    .search-box, .filter-select {
        min-width: 100%;
    }

    .search-box input {
        padding: 0.6rem 1rem 0.6rem 35px;
        font-size: 0.9rem;
    }

    .filter-select {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }
}

/* Responsive remove button */
@media (max-width: 500px) {
    .remove-text {
        display: none;
    }
    .remove-icon {
        display: inline;
    }
}

@media (min-width: 501px) {
    .remove-text {
        display: inline;
    }
    .remove-icon {
        display: none;
    }
}

.follow-actions .btn-follow-sm,
.follow-actions .btn-remove {
    border-radius: 4px !important; /* remove circle */
    padding: 6px 12px !important; /* retain comfortable padding */
    width: auto !important; /* let width be automatic */
    height: auto !important; /* let height adjust as per padding */
    min-width: unset !important;
}

.alert {
    padding: 12px 16px;
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}


.follow-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-remove {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
}

.btn-remove:hover {
    background: #e9ecef;
    color: #dc3545;
    border-color: #dc3545;
}   
/* Loading Overlay Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease;
    }

    /* From Uiverse.io by cosnametv */
    .loader {
        --color: #4361ee;
        --size: 70px;
        width: var(--size);
        height: var(--size);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 5px;
    }

    .loader span {
        width: 100%;
        height: 100%;
        background-color: var(--color);
        animation: keyframes-blink 0.6s alternate infinite linear;
    }

    .loader span:nth-child(1) {
        animation-delay: 0ms;
    }

    .loader span:nth-child(2) {
        animation-delay: 200ms;
    }

    .loader span:nth-child(3) {
        animation-delay: 300ms;
    }

    .loader span:nth-child(4) {
        animation-delay: 400ms;
    }

    .loader span:nth-child(5) {
        animation-delay: 500ms;
    }

    .loader span:nth-child(6) {
        animation-delay: 600ms;
    }

    @keyframes keyframes-blink {
        0% {
            opacity: 0.3;
            transform: scale(0.5) rotate(5deg);
        }

        50% {
            opacity: 1;
            transform: scale(1);
        }
    }
/* Tabs loading overlay (only for Your Uploads / Saved Items content area) */
.tabs-content-wrapper { position: relative; }
.tabs-loading-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.tabs-loading-overlay .loading-spinner { display: block; text-align: center; }
.tabs-loading-overlay .loading-spinner i { font-size: 2rem; color: #4361ee; margin-bottom: 10px; }
.tabs-loading-overlay .loading-spinner p { font-size: 1rem; color: #555; }
#tabsContent { transition: opacity 0.3s ease; }
</style>



<!-- Loading overlay -->
<div id="loading-overlay" class="loading-overlay">
    <!-- From Uiverse.io by cosnametv -->
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<div class="dashboard-container" style="opacity: 0;">
    <div class="dashboard-layout">
        <!-- Main Content Column -->
        <div class="main-content">
            <!-- Profile Section -->
            <section class="profile-section">
                <!-- Moved actions to top right -->
                <div class="profile-actions">
                    <a href="edit-profile.php" class="btn btn-edit">
                        <i class="fas fa-user-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="logout.php" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
                
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
                            <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                            <p class="profile-join-date">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <?php if(!empty($user['bio'])): ?>
                    <div class="profile-bio">
                        <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                    </div>
                <?php endif; ?>

                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-value" id="contentCount"><?php echo $total_uploads; ?></div>
                        <div class="stat-label" id="contentLabel"><?php echo $show_saved ? 'Saved Items' : 'Uploads'; ?></div>
                    </div>
                    <div class="stat-card clickable" id="followersStat">
                        <div class="stat-value"><?php echo $follower_count; ?></div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-card clickable" id="followingStat">
                        <div class="stat-value"><?php echo $following_count; ?></div>
                        <div class="stat-label">Following</div>
                    </div>
                </div>
            </section>

            <!-- Content Section -->
            <section class="content-section">
                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions">
                    <div class="bulk-count" id="bulkCount">0 items selected</div>
                    <button class="btn bulk-delete-btn" id="bulkDelete">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                    <button class="btn btn-secondary" id="bulkCancel">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </button>
                </div>

                <div class="section-header">
                    <!-- Content title and upload button moved to top -->
                    <div class="section-title-row">
                        <h2><i class="fas fa-photo-video"></i> Your Content</h2>
                        <a href="upload.php" class="btn btn-upload" id="uploadButton">
                            <i class="fas fa-plus"></i>
                            <span>Upload New</span>
                        </a>
                    </div>

                    <!-- View Toggle -->
                    <div class="view-toggle">
                        <button class="view-toggle-btn <?php echo !$show_saved ? 'active' : ''; ?>" 
                                data-view="uploads" id="uploadsTab">
                            Your Uploads
                        </button>
                        <button class="view-toggle-btn <?php echo $show_saved ? 'active' : ''; ?>" 
                                data-view="saved" id="savedTab">
                            Saved Items
                        </button>
                    </div>

                    <!-- Search and filters moved below -->
                    <div class="search-filter-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search content..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="filter-select-wrapper">
                            <select class="filter-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="image" <?php echo $type_filter === 'image' ? 'selected' : ''; ?>>Images</option>
                                <option value="video" <?php echo $type_filter === 'video' ? 'selected' : ''; ?>>Videos</option>
                            </select>
                            <select class="filter-select" id="sortFilter">
                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Content will be loaded here via AJAX -->
                <div id="contentContainer" class="tabs-content-wrapper">
                    <div id="tabs-loading-overlay" class="tabs-loading-overlay">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Loading content...</p>
                        </div>
                    </div>
                    <div id="tabsContent"></div>
                </div>
            </section>
        </div>

        <!-- Right Sidebar Column -->
        <div class="sidebar">
            <!-- Recent Activity Section -->
            <section class="sidebar-section">
                <h3 class="sidebar-title"><i class="fas fa-bell"></i> Recent Activity</h3>
                <div class="activity-list">
                    <?php if($recent_activity->num_rows > 0): ?>
                        <?php while($activity = $recent_activity->fetch_assoc()): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <?php if($activity['type'] === 'upload'): ?>
                                        <i class="fas fa-upload"></i>
                                    <?php else: ?>
                                        <i class="fas fa-user-plus"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <?php if($activity['type'] === 'upload'): ?>
                                            You uploaded "<?php echo htmlspecialchars($activity['title']); ?>"
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($activity['follower_name']); ?> started following you
                                        <?php endif; ?>
                                    </div>
                                    <div class="activity-date">
                                        <?php echo date('M j, Y g:i a', strtotime($activity['date'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-follows">No recent activity</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Followers/Following Modal -->
<div class="modal" id="followModal">
    <div class="modal-content follow-modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="followModalTitle">Followers</h3>
            <button class="modal-close" onclick="closeModal('followModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <div class="follow-list" id="followList"></div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Edit Content</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <div class="form-group">
                    <label for="editTitle" class="form-label">Title</label>
                    <input type="text" id="editTitle" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editDescription" class="form-label">Description</label>
                    <textarea id="editDescription" name="description" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="editTags" class="form-label">Tags (comma separated)</label>
                    <input type="text" id="editTags" name="tags" class="form-control" placeholder="tag1, tag2, tag3">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveContent()">Save Changes</button>
        </div>
    </div>
</div>

<!-- Unified Delete Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="deleteModalTitle">Delete Content</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMessage">Are you sure you want to delete this content? This action cannot be undone.</p>
            <input type="hidden" id="deleteId">
            <input type="hidden" id="deleteType" value="upload"> <!-- upload or saved -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn btn-logout" onclick="processDelete()">Delete</button>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal" id="bulkDeleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Delete Selected Content</h3>
            <button class="modal-close" onclick="closeModal('bulkDeleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete the selected content? This action cannot be undone.</p>
            <p><strong id="bulkDeleteCount">0 items</strong> will be permanently deleted.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('bulkDeleteModal')">Cancel</button>
            <button class="btn btn-logout" onclick="deleteBulkContent()">Delete</button>
        </div>
    </div>
</div>

<!-- Unsave Confirmation Modal -->
<div class="modal" id="unsaveModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Remove from Saved</h3>
            <button class="modal-close" onclick="closeModal('unsaveModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to remove this content from your saved items?</p>
            <p>This action cannot be undone.</p>
            <input type="hidden" id="unsaveContentId">
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('unsaveModal')">Cancel</button>
            <button class="btn btn-logout" onclick="confirmUnsave()">Remove</button>
        </div>
    </div>
</div>


<form id="bulkDeleteForm" method="POST" action="dashboard.php">
    <input type="hidden" name="bulk_action" value="delete">
    <input type="hidden" name="selected_items" id="selectedItemsInput" value="">
</form>












<script>
// Loading overlay handling for dashboard (same behavior as index.php)
document.addEventListener('DOMContentLoaded', function () {
    // Hide loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        const container = document.querySelector('.dashboard-container');
        
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
                if (container) container.style.opacity = '1';
            }, 500);
        } else if (container) {
            container.style.opacity = '1';
        }
    });

    // Fallback: hide overlay after 5 seconds
    setTimeout(function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        const container = document.querySelector('.dashboard-container');

        if (loadingOverlay && loadingOverlay.style.display !== 'none') {
            loadingOverlay.style.opacity = '0';
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
                if (container) container.style.opacity = '1';
            }, 500);
        }
    }, 5000);
});
// Store content data for modals
let contentData = {};
let selectedItems = [];
let currentView = '<?php echo $view_type; ?>';
let currentPage = 1;
let totalPages = 1;

// Store current modal state
let currentModalType = '';
let currentModalUserId = 0;

// Store button reference for unsave confirmation
let unsaveButtonRef = null;

document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['success_message'])): ?>
    showAlert('<?php echo addslashes($_SESSION['success_message']); ?>', 'success');
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
    showAlert('<?php echo addslashes($_SESSION['error_message']); ?>', 'error');
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    

    // Load initial content
    loadContent(currentView, 1);
    
    // Add event listeners for followers/following stats
    document.getElementById('followersStat').addEventListener('click', function() {
        openFollowModal('followers', <?php echo $user_id; ?>);
    });
    
    document.getElementById('followingStat').addEventListener('click', function() {
        openFollowModal('following', <?php echo $user_id; ?>);
    });
    
    // Tab switching
    document.getElementById('uploadsTab').addEventListener('click', function() {
        switchView('uploads');
    });
    
    document.getElementById('savedTab').addEventListener('click', function() {
        switchView('saved');
    });
    
    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    function applyFilters() {
        loadContent(currentView, 1);
    }
    
    // Debounce search input
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
    
    typeFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
    
    // Bulk selection functionality
    const bulkActions = document.getElementById('bulkActions');
    const bulkCount = document.getElementById('bulkCount');
    const bulkDeleteBtn = document.getElementById('bulkDelete');
    const bulkCancelBtn = document.getElementById('bulkCancel');

    bulkDeleteBtn.addEventListener('click', function() {
        if(selectedItems.length > 0) {
            document.getElementById('bulkDeleteCount').textContent = `${selectedItems.length} item${selectedItems.length !== 1 ? 's' : ''}`;
            document.getElementById('bulkDeleteModal').classList.add('active');
        }
    });
    
    bulkCancelBtn.addEventListener('click', function() {
        selectedItems = [];
        document.querySelectorAll('.checkbox-select').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('.content-card').forEach(card => {
            card.classList.remove('selected');
        });
        updateBulkActions();
    });
});

function loadContent(viewType, page) {
    const container = document.getElementById('tabsContent');
    const tabsOverlay = document.getElementById('tabs-loading-overlay');
    if (tabsOverlay) {
        tabsOverlay.style.display = 'flex';
        tabsOverlay.style.opacity = '1';
    }
    if (container) container.style.opacity = '0';
    
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    const params = new URLSearchParams();
    params.set('ajax_content', 'true');
    params.set('content_type', viewType);
    params.set('page', page);
    
    if(searchInput.value) params.set('search', searchInput.value);
    if(typeFilter.value) params.set('type', typeFilter.value);
    if(sortFilter.value) params.set('sort', sortFilter.value);
    
    fetch('dashboard.php?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            container.innerHTML = `<div class="empty-state"><p>Error loading content: ${data.error}</p></div>`;
            return;
        }
        
        // Update content count
        document.getElementById('contentCount').textContent = data.total_uploads;
        document.getElementById('contentLabel').textContent = viewType === 'saved' ? 'Saved Items' : 'Uploads';
        
        // Show/hide upload button based on view
        document.getElementById('uploadButton').style.display = viewType === 'saved' ? 'none' : 'flex';
        
        // Render content
        if (data.content.length > 0) {
            let html = '<div class="content-grid">';
            
            data.content.forEach(upload => {
                const is_video = /\.(mp4|mov|avi|webm)$/i.test(upload.filename);
                const display_path = upload.filepath.replace(/^.*\/htdocs/, '');
                const thumbnail_path = upload.display_image.replace(/^.*\/htdocs/, '');
                const tags = upload.tags ? upload.tags.split(',') : [];
                const is_favorite = upload.is_favorite;
                
                html += `
                    <div class="content-card" data-id="${upload.id}">
                        ${viewType === 'uploads' ? `
                            <input type="checkbox" class="checkbox-select" id="select-${upload.id}">
                            <label for="select-${upload.id}" class="checkbox-custom"></label>
                        ` : ''}
                        <div class="card-media">
                            ${is_video ? `
                                ${thumbnail_path && thumbnail_path !== 'assets/video-thumbnail.jpg' ? 
                                    `<img src="${thumbnail_path}" alt="Video thumbnail">` : 
                                    `<div class="video-thumbnail-fallback"><i class="fas fa-play"></i></div>`
                                }
                                <div class="video-badge"><i class="fas fa-play"></i></div>
                            ` : `
                                <img src="${display_path}" alt="${upload.title.replace(/"/g, '&quot;')}">
                            `}
                            <div class="card-hover-actions">
                                <a href="view.php?id=${upload.id}" class="btn-view">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                        <div class="card-content">
    <div class="content-title" title="${upload.title.replace(/"/g, '&quot;')}">
        ${upload.title}
    </div>
    <div class="upload-date">
        ${new Date(upload.upload_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
    </div>
    
    ${viewType === 'saved' && upload.username ? `
        <div class="uploader-info">
            <div class="uploader-avatar">
                ${upload.uploader_profile_pic ? 
                    `<img src="${upload.uploader_profile_pic}" alt="${upload.username}" class="uploader-avatar">` : 
                    `<div class="uploader-avatar-initials">${getInitials(upload.username)}</div>`
                }
            </div>
            <span>by ${upload.username}</span>
        </div>
    ` : ''}
</div>
                           
<div class="card-actions">
    ${viewType === 'uploads' ? `
        <button class="btn-action btn-edit-action" onclick="openEditModal(${upload.id}, event)">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn-action btn-delete-action" onclick="openDeleteModal(${upload.id}, 'upload', event)">
            <i class="fas fa-trash"></i>
        </button>
    ` : `
        <button class="btn-action btn-favorite-action ${is_favorite ? 'favorited' : ''}"
                onclick="toggleFavorite(${upload.id}, this, ${viewType === 'saved'})">
            <i class="${is_favorite ? 'fas fa-bookmark' : 'far fa-bookmark'}"></i>
        </button>
    `}
</div>
                        </div>
                    </div>
                `;
                
                // Store content data for modals
                contentData[upload.id] = {
                    id: upload.id,
                    title: upload.title,
                    description: upload.description || '',
                    tags: upload.tags || '',
                    is_favorite: is_favorite
                };
            });
            
            html += '</div>';
            
            // Add pagination if needed
            if (data.total_pages > 1) {
                html += '<ul class="pagination">';
                
                if (data.current_page > 1) {
                    html += `
                        <li class="page-item">
                            <a href="javascript:void(0);" class="page-link" onclick="loadContent('${viewType}', ${data.current_page - 1})">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    `;
                }
                
                for (let i = 1; i <= data.total_pages; i++) {
                    html += `
                        <li class="page-item">
                            <a href="javascript:void(0);" class="page-link ${i === data.current_page ? 'active' : ''}" 
                               onclick="loadContent('${viewType}', ${i})">
                                ${i}
                            </a>
                        </li>
                    `;
                }
                
                if (data.current_page < data.total_pages) {
                    html += `
                        <li class="page-item">
                            <a href="javascript:void(0);" class="page-link" onclick="loadContent('${viewType}', ${data.current_page + 1})">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    `;
                }
                
                html += '</ul>';
            }
            
            container.innerHTML = html;
            
            // Set up checkbox event listeners
            document.querySelectorAll('.checkbox-select').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const card = this.closest('.content-card');
                    const id = card.dataset.id;
                    
                    if(this.checked) {
                        selectedItems.push(id);
                        card.classList.add('selected');
                    } else {
                        selectedItems = selectedItems.filter(item => item !== id);
                        card.classList.remove('selected');
                    }
                    
                    updateBulkActions();
                });
            });
            
            // Reset selection
            selectedItems = [];
            updateBulkActions();
            
        } else {
            // No content found
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-${viewType === 'saved' ? 'bookmark' : 'cloud-upload-alt'}"></i>
                    </div>
                    <h3>No Content Found</h3>
                    <p>${searchInput.value ? 'Your search did not match any content.' : (viewType === 'saved' ? 'You haven\'t saved any content yet.' : 'You haven\'t uploaded any content yet.')}</p>
                    ${viewType === 'uploads' ? `
                        <a href="upload.php" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Now
                        </a>
                    ` : `
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-search"></i> Browse Content
                        </a>
                    `}
                </div>
            `;
        }
        
        // Hide tabs overlay and fade in content
        if (tabsOverlay) {
            tabsOverlay.style.opacity = '0';
            setTimeout(function() { tabsOverlay.style.display = 'none'; }, 300);
        }
        container.style.opacity = '1';

        // Update current page and total pages
        currentPage = data.current_page;
        totalPages = data.total_pages;
    })
    .catch(error => {
        console.error('Error:', error);
        if (tabsOverlay) {
            tabsOverlay.style.opacity = '0';
            setTimeout(function() { tabsOverlay.style.display = 'none'; }, 300);
        }
        container.style.opacity = '1';
        container.innerHTML = `<div class="empty-state"><p>Error loading content. Please try again.</p></div>`;
    });
}

function getInitials(username) {
    const names = username.split(' ');
    let initials = names[0].charAt(0).toUpperCase();
    if (names.length > 1) {
        initials += names[names.length - 1].charAt(0).toUpperCase();
    }
    return initials;
}

function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const bulkCount = document.getElementById('bulkCount');
    
    bulkCount.textContent = `${selectedItems.length} item${selectedItems.length !== 1 ? 's' : ''} selected`;
    
    if(selectedItems.length > 0) {
        bulkActions.classList.add('active');
    } else {
        bulkActions.classList.remove('active');
    }
}

function switchView(viewType) {
    // Update active tab
    document.querySelectorAll('.view-toggle-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.view-toggle-btn[data-view="${viewType}"]`).classList.add('active');

    // Update current view
    currentView = viewType;

    // Load content for the selected view
    loadContent(viewType, 1);
}

function toggleFavorite(id, button, isSavedView = false) {
    event.stopPropagation();

    const icon = button.querySelector('i');
    const isFavorite = button.classList.contains('favorited');
    const action = isFavorite ? 'remove' : 'add';

    // If removing from saved view, show confirmation modal
    if (isSavedView && action === 'remove') {
        document.getElementById('unsaveContentId').value = id;
        // Store button reference for later use
        unsaveButtonRef = button;
        document.getElementById('unsaveModal').classList.add('active');
        return;
    }

    // For adding favorites or removing from other views, proceed directly
    performFavoriteAction(id, button, action, isSavedView);
}

function performFavoriteAction(id, button, action, isSavedView = false) {
    const icon = button.querySelector('i');

    // Show loading state
    const originalIcon = icon.className;
    icon.className = 'fas fa-spinner fa-spin';

    fetch('includes/toggle_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&action=${action}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toggle visual state
            button.classList.toggle('favorited');
            icon.className = button.classList.contains('favorited') ? 'fas fa-bookmark' : 'far fa-bookmark';

            // If we're in the saved view and we're removing a favorite, remove the card
            if (isSavedView && action === 'unsave') {
                const card = button.closest('.content-card');
                if (card) {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Update the count
                        const stat = document.querySelector('.stat-card:first-child .stat-value');
                        if (stat) {
                            stat.textContent = parseInt(stat.textContent) - 1;
                        }

                        // Check if we need to show empty state
                        if (document.querySelectorAll('.content-card').length === 0) {
                            showEmptyState();
                        }
                    }, 300);
                }
            }

            showAlert(data.message, 'success');
        } else {
            // Revert to original state on error
            icon.className = originalIcon;
            showAlert(data.message || 'Failed to update favorite status', 'error');
        }
    })
    .catch(error => {
        // Revert to original state on error
        icon.className = originalIcon;
        console.error('Error:', error);
        showAlert('An error occurred while updating favorite status', 'error');
    });
}

function confirmUnsave() {
    const contentId = document.getElementById('unsaveContentId').value;

    if (contentId && unsaveButtonRef) {
        performFavoriteAction(contentId, unsaveButtonRef, 'unsave', true);
        closeModal('unsaveModal');
        // Clear the button reference
        unsaveButtonRef = null;
    }
}

function showEmptyState() {
    const contentGrid = document.querySelector('.content-grid');
    if (contentGrid) {
        contentGrid.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bookmark"></i>
                </div>
                <h3>No Saved Content</h3>
                <p>You haven't saved any content yet.</p>
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Content
                </a>
            </div>
        `;
    }
}

function openEditModal(id, event) {
    if (event) event.stopPropagation();
    const data = contentData[id];
    if (data) {
        document.getElementById('editId').value = data.id;
        document.getElementById('editTitle').value = data.title;
        document.getElementById('editDescription').value = data.description;
        document.getElementById('editTags').value = data.tags || '';
        document.getElementById('editModal').classList.add('active');
    }
}

// Open delete modal
function openDeleteModal(id, type = 'upload', event) {
    if (event) event.stopPropagation();
    
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteType').value = type;
    
    if (type === 'saved') {
        document.getElementById('deleteModalTitle').textContent = 'Remove Saved Content';
        document.getElementById('deleteModalMessage').textContent = 'Are you sure you want to remove this content from your saved items? This action cannot be undone.';
        document.querySelector('#deleteModal .btn-logout').textContent = 'Remove';
    } else {
        document.getElementById('deleteModalTitle').textContent = 'Delete Content';
        document.getElementById('deleteModalMessage').textContent = 'Are you sure you want to delete this content? This action cannot be undone.';
        document.querySelector('#deleteModal .btn-logout').textContent = 'Delete';
    }
    
    document.getElementById('deleteModal').classList.add('active');
}
// Process delete based on type
function processDelete() {
    const id = document.getElementById('deleteId').value;
    const type = document.getElementById('deleteType').value;
    
    if (type === 'saved') {
        deleteSavedContent(id);
    } else {
        deleteContent(id);
    }
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');

    // Clear button reference when unsave modal is closed
    if (modalId === 'unsaveModal') {
        unsaveButtonRef = null;
    }
}

function saveContent() {
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });
    
    fetch('includes/update_content.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => {
        // First check if the response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error(`Invalid response: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the content data
            contentData[id].title = formData.get('title');
            contentData[id].description = formData.get('description');
            contentData[id].tags = formData.get('tags');
            
            // Update the card title
            const card = document.querySelector(`.content-card[data-id="${id}"] .content-title`);
            if (card) {
                card.textContent = formData.get('title');
                card.setAttribute('title', formData.get('title'));
            }
            
            // Update tags if they exist
            const tagContainer = document.querySelector(`.content-card[data-id="${id}"] .tag-list`);
            if (tagContainer) {
                const tags = formData.get('tags').split(',').filter(tag => tag.trim() !== '');
                tagContainer.innerHTML = tags.map(tag => 
                    `<a href="dashboard.php?tag=${encodeURIComponent(tag.trim())}" class="tag">${tag.trim()}</a>`
                ).join('');
            }
            
            closeModal('editModal');
            showAlert('Content updated successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to update content', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(`Error updating content: ${error.message}`, 'error');
    });
}

function deleteContent(id = null) {
    const contentId = id || document.getElementById('deleteId').value;
    
    fetch('includes/delete_content.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${contentId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the card from DOM
            const card = document.querySelector(`.content-card[data-id="${contentId}"]`);
            if (card) {
                card.remove();
            }
            
            // Update stats count
            const stat = document.querySelector('.stat-card:first-child .stat-value');
            if (stat) {
                stat.textContent = parseInt(stat.textContent) - 1;
            }
            
            closeModal('deleteModal');
            showAlert('Content deleted successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to delete content', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while deleting content', 'error');
    });
}
// Modified deleteSavedContent function
function deleteSavedContent(id = null) {
    const contentId = id || document.getElementById('deleteId').value;
    
    // Show loading state
    const deleteBtn = document.querySelector('#deleteModal .btn-logout');
    const originalText = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
    deleteBtn.disabled = true;
    
    fetch('includes/toggle_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${contentId}&action=remove`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the card from DOM
            const card = document.querySelector(`.content-card[data-id="${contentId}"]`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                card.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    card.remove();
                    
                    // Update the count
                    const stat = document.querySelector('.stat-card:first-child .stat-value');
                    if (stat) {
                        stat.textContent = parseInt(stat.textContent) - 1;
                    }
                    
                    // Check if we need to show empty state
                    if (document.querySelectorAll('.content-card').length === 0) {
                        showEmptyState();
                    }
                }, 300);
            }
            
            closeModal('deleteModal');
            showAlert('Content removed from saved items!', 'success');
        } else {
            showAlert(data.message || 'Failed to remove content', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while removing content', 'error');
    })
    .finally(() => {
        // Restore button state
        deleteBtn.innerHTML = originalText;
        deleteBtn.disabled = false;
    });
}

function deleteBulkContent() {
    document.getElementById('selectedItemsInput').value = JSON.stringify(selectedItems);
    document.getElementById('bulkDeleteForm').submit();
}

function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const bulkCount = document.getElementById('bulkCount');
    
    bulkCount.textContent = `${selectedItems.length} item${selectedItems.length !== 1 ? 's' : ''} selected`;
    
    if(selectedItems.length > 0) {
        bulkActions.classList.add('active');
    } else {
        bulkActions.classList.remove('active');
    }
}

function openFollowModal(type, userId) {
    const modal = document.getElementById('followModal');
    const title = document.getElementById('followModalTitle');
    const list = document.getElementById('followList');
    const spinner = document.querySelector('#followModal .loading-spinner');

    currentModalType = type;
    currentModalUserId = userId;
    
    title.textContent = type === 'followers' ? 'Followers' : 'Following';
    list.innerHTML = '';
    spinner.style.display = 'block';
    modal.classList.add('active');
    
    fetch(`includes/get_${type}.php?user_id=${userId}`)
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error(`Server returned HTML instead of JSON: ${text.substring(0, 100)}...`);
                });
            }
            return response.json();
        })
        .then(data => {
            spinner.style.display = 'none';
            
            // Check if we got an error response
            if (data.error) {
                list.innerHTML = `<div class="no-follows">Error: ${data.error}</div>`;
                return;
            }
            
            if(data.length > 0) {
                data.forEach(user => {
                    const names = user.username.split(' ');
                    let initials = names[0].charAt(0).toUpperCase();
                    if(names.length > 1) {
                        initials += names[names.length - 1].charAt(0).toUpperCase();
                    }
                    
                    const genderClass = user.gender ? `avatar-${user.gender}` : 'avatar-other';
                    
                    const item = document.createElement('div');
                    item.className = 'follow-item';
                    item.setAttribute('data-user-id', user.id);
                    
                    // Determine button text and class
                    let buttonText, buttonClass, isFollowing;
                    
                    if (type === 'followers') {
                        // In followers modal, show "Follow Back" if not already following, otherwise "Following"
                        isFollowing = user.is_following;
                        buttonText = isFollowing ? 'Following' : 'Follow Back';
                        buttonClass = isFollowing ? 'btn-following' : 'btn-follow';
                    } else {
                        // In following modal, always show "Following" (for unfollow)
                        isFollowing = true;
                        buttonText = 'Following';
                        buttonClass = 'btn-following';
                    }
                    
                    item.innerHTML = `
                        <a href="profile.php?id=${user.id}" class="follow-user">
                            ${user.profile_pic ? 
                                `<img src="${user.profile_pic}" alt="${user.username}" class="follow-avatar">` : 
                                `<div class="follow-avatar-initials ${genderClass}">${initials}</div>`
                            }
                            <div>
                                <div class="follow-username">${user.username}</div>
                                ${type === 'following' && user.follows_you ? 
                                    '<div class="follow-back">Follows you</div>' : ''}
                            </div>
                        </a>
                        <div class="follow-actions">
                            <button class="btn btn-follow-sm ${buttonClass}"
                                    onclick="toggleFollow(${user.id}, this, '${type}', event)">
                                ${buttonText}
                            </button>
                            ${type === 'followers' ? `
                                <button class="btn btn-remove" onclick="removeFollower(${user.id}, this)" title="Remove follower">
                                    <span class="remove-text">Remove</span>
                                    <i class="fas fa-times remove-icon"></i>
                                </button>
                            ` : ''}
                        </div>
                    `;
                    list.appendChild(item);
                });
            } else {
                list.innerHTML = '<div class="no-follows">No ' + type + ' found</div>';
            }
        })
        .catch(error => {
            spinner.style.display = 'none';
            console.error('Error loading followers/following:', error);
            list.innerHTML = `<div class="no-follows">Error loading ${type}: ${error.message}</div>`;
        });
}

function refreshFollowList() {
    if (currentModalType && currentModalUserId) {
        openFollowModal(currentModalType, currentModalUserId);
    }
}

function toggleFollow(userId, button, modalType, event) {
    if (event) event.stopPropagation();

    const isFollowing = button.classList.contains('btn-following');
    const action = isFollowing ? 'unfollow' : 'follow';

    // Show loading state
    const originalText = button.textContent;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('includes/follow_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&action=${action}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (action === 'follow') {
                button.classList.remove('btn-follow');
                button.classList.add('btn-following');
                button.textContent = 'Following';
                
                // Update FOLLOWING count
                updateFollowingCount(1);
                
                // If in followers modal, check if the user now follows back
                if (modalType === 'followers') {
                    // No need to remove from list, just update the button
                }
            } else {
                button.classList.remove('btn-following');
                button.classList.add('btn-follow');
                
                // Set appropriate text based on modal type
                if (modalType === 'followers') {
                    button.textContent = 'Follow Back';
                } else {
                    button.textContent = 'Follow';
                    
                    // In following modal, DON'T remove the item after unfollowing
                    // The user can follow again if they want
                }
                
                // Update FOLLOWING count
                updateFollowingCount(-1);
            }

            // Reset button state
            button.disabled = false;
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message || 'Failed to update follow status', 'error');
            // Revert button state on error
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating follow status', 'error');
        // Revert button state on error
        button.textContent = originalText;
        button.disabled = false;
    });
}

function removeFollower(userId, button) {
    event.stopPropagation();
    
    if (!confirm('Are you sure you want to remove this follower?')) {
        return;
    }
    
    // Show loading state
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('includes/remove_follower.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the user from the list
            const listItem = button.closest('.follow-item');
            if (listItem) {
                listItem.style.opacity = '0';
                listItem.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    listItem.remove();
                    
                    // Update FOLLOWER count
                    updateFollowerCount(-1);
                    
                    // If no more followers, show message
                    if (document.querySelectorAll('.follow-item').length === 0) {
                        document.getElementById('followList').innerHTML = 
                            '<div class="no-follows">No followers found</div>';
                    }
                }, 300);
            }
            
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message || 'Failed to remove follower', 'error');
            // Revert button state on error
            button.innerHTML = originalHtml;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while removing follower', 'error');
        // Revert button state on error
        button.innerHTML = originalHtml;
        button.disabled = false;
    });
}

// Function to update FOLLOWING count in real-time
function updateFollowingCount(change) {
    const followingStat = document.getElementById('followingStat');
    const followingValue = followingStat.querySelector('.stat-value');
    const currentCount = parseInt(followingValue.textContent);
    const newCount = Math.max(0, currentCount + change);
    
    // Animate the count change
    animateValue(followingValue, currentCount, newCount, 500);
}

// Function to update FOLLOWER count in real-time
function updateFollowerCount(change) {
    const followerStat = document.getElementById('followersStat');
    const followerValue = followerStat.querySelector('.stat-value');
    const currentCount = parseInt(followerValue.textContent);
    const newCount = Math.max(0, currentCount + change);
    
    // Animate the count change
    animateValue(followerValue, currentCount, newCount, 500);
}

// Enhanced animateValue function with better animation
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        
        // Easing function for smoother animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const value = Math.floor(easeOutQuart * (end - start) + start);
        
        element.textContent = value;
        
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function showAlert(message, type) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.padding = '12px 20px';
    alert.style.borderRadius = 'var(--border-radius)';
    alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    alert.style.zIndex = '1100';
    alert.style.animation = 'fadeIn 0.3s';
    
    if (type === 'success') {
        alert.style.backgroundColor = '#4cc9f0';
        alert.style.color = 'white';
        alert.style.border = '1px solid #4cc9f0';
    } else {
        alert.style.backgroundColor = '#f94144';
        alert.style.color = 'white';
        alert.style.border = '1px solid #f94144';
    }
    
    document.body.appendChild(alert);
    
    // Remove alert after 3 seconds
    setTimeout(() => {
        alert.style.animation = 'fadeOut 0.3s';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, 3000);
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
});

// Animate stat counters
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

document.querySelectorAll('.stat-value').forEach(el => {
    const value = parseInt(el.textContent);
    if(value > 0) {
        el.textContent = '0';
        setTimeout(() => {
            animateValue(el, 0, value, 1000);
        }, 200);
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>


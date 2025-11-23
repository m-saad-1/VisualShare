<?php
require_once 'includes/config.php';

// --- API endpoint handling ---
if (isset($_GET['action']) && $_GET['action'] === 'api') {
    require_once 'includes/api_handlers.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $current_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    $type = $_GET['type'] ?? '';

    switch ($type) {
        case 'comment':
            handleCommentsApi($conn, $current_user_id);
            break;
        case 'like':
            handleLikesApi($conn, $current_user_id);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid API type']);
            exit;
    }
    exit; // Stop execution after handling API request
}

// --- Validate upload ID ---
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$current_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// --- Fetch upload details ---
$query = "SELECT uploads.*, users.username, users.profile_pic, users.id as user_id 
          FROM uploads 
          JOIN users ON uploads.user_id = users.id 
          WHERE uploads.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$upload = $result->fetch_assoc();

// Define paths for the media
$display_path = !empty($upload['filepath']) ? $upload['filepath'] : '';
$absolute_path = !empty($display_path) ? __DIR__ . '/' . $display_path : '';
$image_url = !empty($display_path) ? BASE_URL . '/' . $display_path : '';

// --- Check if current user follows this profile ---
$is_following = false;
if ($current_user_id > 0 && $current_user_id !== (int)$upload['user_id']) {
    $follow_query = "SELECT 1 FROM user_follows WHERE follower_id = ? AND following_id = ?";
    $follow_stmt = $conn->prepare($follow_query);
    $follow_stmt->bind_param("ii", $current_user_id, $upload['user_id']);
    $follow_stmt->execute();
    $is_following = $follow_stmt->get_result()->num_rows > 0;
}

// --- Detect media type ---
$file_ext = strtolower(pathinfo($upload['filename'], PATHINFO_EXTENSION));
$is_video = in_array($file_ext, ['mp4', 'mov', 'avi', 'wmv', 'webm']);

// --- Get tags ---
$tags = [];
$tags_query = "SELECT tags.name FROM upload_tags
               JOIN tags ON upload_tags.tag_id = tags.id
               WHERE upload_tags.upload_id = ?";
$tags_stmt = $conn->prepare($tags_query);
$tags_stmt->bind_param("i", $id);
$tags_stmt->execute();
$tags_result = $tags_stmt->get_result();
while ($tag = $tags_result->fetch_assoc()) {
    $tags[] = $tag['name'];
}

// --- Get like count ---
$like_count = 0;
$like_query = "SELECT COUNT(*) as count FROM likes WHERE upload_id = ?";
$like_stmt = $conn->prepare($like_query);
$like_stmt->bind_param("i", $id);
$like_stmt->execute();
$like_result = $like_stmt->get_result();
if ($like_result && $like_result->num_rows > 0) {
    $like_data = $like_result->fetch_assoc();
    $like_count = (int)$like_data['count'];
}

// --- Check if user liked ---
$user_liked = false;
if ($current_user_id > 0) {
    $user_like_query = "SELECT 1 FROM likes WHERE user_id = ? AND upload_id = ?";
    $user_like_stmt = $conn->prepare($user_like_query);
    $user_like_stmt->bind_param("ii", $current_user_id, $id);
    $user_like_stmt->execute();
    $user_liked = $user_like_stmt->get_result()->num_rows > 0;
}

// --- Related uploads ---
$related_query = "SELECT DISTINCT uploads.id, uploads.filepath, uploads.filename, uploads.title 
                  FROM uploads 
                  LEFT JOIN upload_tags ON uploads.id = upload_tags.upload_id
                  WHERE uploads.user_id = ? AND uploads.id != ?";

if (!empty($tags)) {
    $related_query .= " AND (upload_tags.tag_id IN (
        SELECT tag_id FROM upload_tags WHERE upload_id = ?
    ))";
}

$related_query .= " ORDER BY uploads.upload_date DESC LIMIT 4";

$related_stmt = $conn->prepare($related_query);
if (!empty($tags)) {
    $related_stmt->bind_param("iii", $upload['user_id'], $id, $id);
} else {
    $related_stmt->bind_param("ii", $upload['user_id'], $id);
}
$related_stmt->execute();
$related_result = $related_stmt->get_result();
$related_uploads = $related_result->fetch_all(MYSQLI_ASSOC);

// --- Generate initials for avatar fallback ---
$initials = '';
if (!empty($upload['username'])) {
    $names = explode(' ', $upload['username']);
    $initials = strtoupper(substr($names[0], 0, 1));
    if (count($names) > 1) {
        $initials .= strtoupper(substr(end($names), 0, 1));
    }
}

require_once 'includes/header.php';
?>

<style>
/* Full-width layout styles */
body {
}

.content-view-container {
    max-width: 100%;
    margin: 0 auto;
    background: white;
    overflow: hidden;
}

.content-wrapper {
    background: #f8f9fa;
    padding: 2rem 0;
    text-align: center;
    margin-bottom: 2rem;
}

.image-container {
    max-width: 100%;
    max-height: 80vh;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 4px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}



.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    background: #000;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.content-missing {
    padding: 4rem;
    text-align: center;
    background: #fff8f8;
    color: #d32f2f;
}

.content-missing i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.content-info {
    padding: 0 2rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.content-info h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #333;
    font-weight: 600;
}

.content-info .description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.tag {
    background: #f0f0f0;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #555;
    transition: all 0.2s;
}

.tag:hover {
    background: #e9ecef;
    color: #343a40;
}

.user-info-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    margin-bottom: 2rem;
}

.profile-link {
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.profile-link:hover {
    color: var(--primary-color);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: #f0f0f0;
}

.user-profile-pic {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-avatar-initials {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-color);
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}

.user-meta {
    display: flex;
    flex-direction: column;
}

.username {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--dark-color);
}

.upload-date {
    font-size: 0.9rem;
    color: #888;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 0.6rem 1.2rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.95rem;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.btn-follow {
    background: var(--primary-color);
    color: white;
}

.btn-follow:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.btn-following {
    background: #2ecc71;
}

.btn-share {
    background: #f0f0f0;
    color: #555;
}

.btn-share:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
}

.btn-like {
    background: #f0f0f0;
    color: #555;
}

.btn-like:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
}

.btn-liked {
    background: #e74c3c;
    color: white;
}

.btn-liked:hover {
    background: #c0392b;
}

.btn-save {
    background: #f0f0f0;
    color: #555;
}

.btn-save:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
}

.btn-save.saved {
    background: #f39c12;
    color: white;
}

.btn-save.saved:hover {
    background: #e67e22;
}

.related-section {
    margin-top: 3rem;
    padding: 0 2rem;
    max-width: 1400px;
    margin: 3rem auto 0;
}

.related-section h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #333;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.related-item {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.related-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.related-item a {
    display: block;
    text-decoration: none;
    color: inherit;
}

.related-thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.related-title {
    padding: 0.8rem;
    font-weight: 500;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 1rem 0;
    }
    
    .content-info {
        padding: 0 1rem 1rem;
    }
    
    .user-info-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: flex-end;
    }
    
    .related-section {
        padding: 0 1rem;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .related-grid {
        grid-template-columns: 1fr;
    }
}

/* Comments Section */
.comments-section {
    margin-top: 3rem;
    padding: 0 2rem;
    max-width: 1400px;
    margin: 3rem auto 0;
}

.comments-section h3 {
    font-size: 1.6rem;
    margin-bottom: 2rem;
    color: #2c3e50;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.comments-section h3 i {
    color: var(--primary-color);
}

.comment-form {
    margin-bottom: 2.5rem;
    padding: 1.5rem;
    border-radius: 10px;
    background-color: transparent;
}

.form-group {
    position: relative;
}

.comment-form textarea {
    width: 100%;
    padding: 1.2rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: white;
}

.comment-form textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.comment-form textarea::placeholder {
    color: #6c757d;
    font-style: italic;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.char-count {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.char-count.warning {
    color: #f39c12;
}

.char-count.danger {
    color: #e74c3c;
}

.comment-form button {
    padding: 0.8rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comment-form button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.comment-form button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.comments-list {
    max-height: 500px;
    overflow-y: auto;
    border-radius: 10px;
}

.comments-list::-webkit-scrollbar {
    width: 6px;
}

.comments-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.comments-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.comments-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.comment-item {
    display: flex;
    gap: 10px;
    padding: 1rem;
    background: white;
    border-bottom: 1px solid #f1f1f1;
    transition: all 0.2s ease;
}

.comment-item:last-child {
    border-bottom: none;
}



.comment-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid #e9ecef;
}

.comment-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.comment-avatar-initials {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    font-weight: 600;
    font-size: 1rem;
}

.comment-content {
    flex: 1;
    min-width: 0;
    position: relative;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.comment-username {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
}

.comment-date {
    font-size: 0.8rem;
    color: #95a5a6;
    font-weight: 500;
}

.comment-text {
    color: #34495e;
    line-height: 1.6;
    word-wrap: break-word;
    margin: 0;
}

.no-comments {
    text-align: center;
    color: #7f8c8d;
    padding: 3rem 2rem;
    border-radius: 10px;
    background: #f8f9fa;
}

.no-comments i {
    font-size: 3rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
    display: block;
}

.no-comments p {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 500;
}

.comment-login-prompt {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 2.5rem;
}

.comment-login-prompt p {
    margin: 0;
    font-size: 1.1rem;
    color: #555;
}

.comment-login-prompt a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

/* Loading state */
.comment-loading {
    text-align: center;
    padding: 2rem;
    color: #7f8c8d;
    background: #f8f9fa;
    border-radius: 10px;
}

.comment-loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Error state */
.comment-error {
    text-align: center;
    padding: 2rem;
    color: #e74c3c;
    background: #fdf2f2;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
}

.comment-error i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

/* Comment User Links */
.comment-user-link {
    text-decoration: none;
    color: inherit;
    transition: opacity 0.2s ease;
}

.comment-user-link:hover {
    opacity: 0.8;
}

.comment-user-link:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
    border-radius: 4px;
}

/* Comment Actions */
.comment-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    gap: 0.5rem;
}

.comment-edit-btn, .comment-delete-btn {
    padding: 0.4rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.8);
    color: #666;
    backdrop-filter: blur(10px);
}

.comment-edit-btn:hover {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
    transform: scale(1.1);
}

.comment-delete-btn:hover {
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
    transform: scale(1.1);
}

/* Edit mode */
.comment-edit-mode .comment-text {
    display: none;
}

.comment-edit-form {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.comment-edit-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 0.9rem;
    resize: vertical;
    min-height: 60px;
}

.comment-edit-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.comment-save-btn, .comment-cancel-btn {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.comment-save-btn {
    background: #27ae60;
    color: white;
}

.comment-save-btn:hover {
    background: #229954;
}

.comment-cancel-btn {
    background: #95a5a6;
    color: white;
}

.comment-cancel-btn:hover {
    background: #7f8c8d;
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

/* Full Image Modal Styles */
.image-viewer-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    -webkit-overflow-scrolling: touch;
}

.image-viewer-modal-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

#modalImage {
    max-width: 95%;
    max-height: 95%;
    object-fit: contain;
    cursor: grab;
    transition: transform 0.2s; /* Add a slight transition for smoother zoom */
}

#modalImage:active {
    cursor: grabbing;
}

.image-viewer-close {
    position: fixed;
    top: 20px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
    z-index: 10001;
}

.image-viewer-close:hover,
.image-viewer-close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

body.modal-open {
    overflow: hidden;
}

/* Animation for modal */
.image-viewer-modal.fade-in {
    animation: fadeIn 0.3s forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes zoomIn {
    from { transform: scale(0.8); }
    to { transform: scale(1); }
}

/* Responsive adjustments for comments */
@media (max-width: 768px) {
    .comments-section {
        padding: 0 1rem;
    }
    
    .comment-form {
        padding: 1rem;
    }

    .comment-form textarea {
        padding: 1rem;
        font-size: 0.95rem;
        min-height: 120px;
    }

    .comments-list {
        max-height: 350px;
    }
}

@media (max-width: 600px) {
    .comments-section {
        margin-top: 1.5rem;
    }

    .comments-section h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .comment-form {
        margin-bottom: 1.5rem;
        padding: 0.8rem;
    }

    .comment-form textarea {
        padding: 0.8rem;
        font-size: 0.9rem;
        min-height: 100px;
    }

    .comment-form button {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        height: 32px;
    }

    .comment-item {
        padding: 0.8rem;
        gap: 10px;
    }

    .comment-text {
        font-size: 0.9rem;
    }

    .comment-username {
        font-size: 0.95rem;
    }
}

@media (max-width: 480px) {
    .comments-section {
        margin-top: 1rem;
    }

    .comments-section h3 {
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
    }

    .comment-form {
        margin-bottom: 1rem;
        padding: 0.6rem;
    }

    .comment-form textarea {
        padding: 0.6rem;
        font-size: 0.85rem;
        min-height: 80px;
    }

    .comments-list {
        max-height: 300px;
    }

    .comment-form button {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        height: 28px;
    }

    .comment-item {
        padding: 0.6rem;
        gap: 8px;
    }

    .comment-text {
        font-size: 0.85rem;
    }

    .comment-username {
        font-size: 0.9rem;
    }

    .comment-date {
        font-size: 0.75rem;
    }
}

/* Responsive adjustments for buttons */
@media (max-width: 500px) {
    .btn-like, .btn-share, .btn-save {
        width: auto;
        padding: 0.5rem 0.6rem;
        font-size: 0;
    }

    .btn-like i,
    .btn-share i,
    .btn-save i {
        font-size: 1.2rem;
        width: auto;
    }

    .btn-like span,
    .btn-share span,
    .btn-save span {
        display: none;
    }
}
/* Responsive adjustments for buttons */
@media (max-width: 500px) {
    .btn-like, .btn-share, .btn-save {
        width: auto;
        padding: 0.5rem 0.6rem;
        font-size: 0;
    }

    .btn-like i,
    .btn-share i,
    .btn-save i {
        font-size: 1.2rem;
        width: auto;
    }

    .btn-like span,
    .btn-share span,
    .btn-save span {
        display: none;
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

<div class="content-view-container" style="opacity: 0;">
    <?php if (file_exists($absolute_path)) : ?>
        <div class="content-wrapper">
            <?php if ($is_video) : ?>
                <div class="video-container">
                    <video controls autoplay>
                        <source src="<?php echo $display_path; ?>" type="video/<?php echo $file_ext; ?>">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php else : ?>
                <div class="image-container">
                    <div class="skeleton-loader"></div>
                    <img src="<?php echo $display_path; ?>" 
                         alt="<?php echo htmlspecialchars($upload['title']); ?>" 
                         onclick="openModal('<?php echo $display_path; ?>')" 
                         style="cursor: pointer; opacity: 0;"
                         loading="lazy"
                         decoding="async">
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="content-missing">
            <i class="fas fa-exclamation-triangle"></i>
            <p>The requested content could not be found</p>
        </div>
    <?php endif; ?>
    
    <div class="content-info">
        <h2><?php echo htmlspecialchars($upload['title']); ?></h2>
        
        <?php if (!empty($upload['description'])) : ?>
            <p class="description"><?php echo htmlspecialchars($upload['description']); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($tags)) : ?>
            <div class="tags-container">
                <?php foreach ($tags as $tag) : ?>
                    <a href="index.php?search=<?php echo urlencode($tag); ?>" class="tag">
                        #<?php echo htmlspecialchars($tag); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="user-info-container">
            <a href="<?php echo ($current_user_id == $upload['user_id']) ? 'dashboard.php' : 'profile.php?id=' . $upload['user_id']; ?>" 
               class="profile-link">
                <div class="user-avatar">
                    <?php if (!empty($upload['profile_pic'])) : ?>
                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($upload['profile_pic']); ?>" 
                             alt="<?php echo htmlspecialchars($upload['username']); ?>'s profile picture"
                             class="user-profile-pic"
                             loading="lazy"
                             decoding="async">
                    <?php else : ?>
                        <div class="user-avatar-initials">
                            <?php echo $initials; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="user-meta">
                    <span class="username"><?php echo htmlspecialchars($upload['username']); ?></span>
                    <span class="upload-date">Uploaded <?php echo date('F j, Y', strtotime($upload['upload_date'])); ?></span>
                </div>
            </a>
            
            <div class="action-buttons">
                <?php if ($current_user_id != $upload['user_id']) : ?>
                    <button class="btn btn-follow <?php echo $is_following ? 'btn-following' : ''; ?>"
                            id="followButton"
                            data-user-id="<?php echo $upload['user_id']; ?>">
                        <i class="fas fa-<?php echo $is_following ? 'check' : 'user-plus'; ?>"></i>
                        <?php echo $is_following ? 'Following' : 'Follow'; ?>
                    </button>
                <?php endif; ?>

                <button class="btn btn-like <?php echo $user_liked ? 'btn-liked' : ''; ?>" id="likeButton" data-upload-id="<?php echo $id; ?>">
                        <i class="fas fa-heart"></i>
                        <span id="likeCount"><?php echo $like_count; ?></span>
                    </button>

                <button class="btn btn-share" id="shareButton" title="Share">
                    <i class="fas fa-share-alt"></i>
                </button>

                <?php if (!$is_video) : ?>
                <a href="<?php echo $display_path; ?>" download class="btn" id="downloadButton" title="Download">
                    <i class="fas fa-download"></i>
                </a>
                <?php endif; ?>

                <button class="btn btn-save" id="saveButton" data-upload-id="<?php echo $id; ?>" title="Save">
                    <i class="fas fa-bookmark"></i>
                </button>
            </div>
        </div>

        <!-- Comments Section -->
            <div class="comments-section">
                <h3><i class="fas fa-comments"></i> Comments</h3>

                <?php if ($current_user_id > 0) : ?>
                    <div class="comment-form">
                        <form id="commentForm">
                            <div class="form-group">
                                <textarea name="comment" placeholder="Share your thoughts about this content..." maxlength="500" required></textarea>
                                <div class="form-actions">
                                    <span class="char-count"><span id="charCount">0</span>/500</span>
                                    <button type="submit"><i class="fas fa-paper-plane"></i> Post Comment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="comment-login-prompt">
                        <p><a href="login.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Sign in</a> or <a href="register.php">sign up</a> to leave a comment.</p>
                    </div>
                <?php endif; ?>

                <div class="comments-list" id="commentsList">
                    <!-- Comments will be loaded here via JavaScript -->
                </div>
            </div>

        <?php if (!empty($related_uploads)) : ?>
            <div class="related-section">
                <h3>More from <?php echo htmlspecialchars($upload['username']); ?></h3>
                <div class="related-grid">
                    <?php foreach ($related_uploads as $related) :
                        $related_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $related['filepath']);
                        ?>
                        <div class="related-item">
                            <a href="view.php?id=<?php echo $related['id']; ?>">
                                <div class="image-skeleton"></div>
                                <img src="<?php echo $related_path; ?>"
                                     alt="<?php echo htmlspecialchars($related['title']); ?>"
                                     class="related-thumbnail"
                                     loading="lazy"
                                     decoding="async">
                                <div class="related-title"><?php echo htmlspecialchars($related['title']); ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Full Image Modal -->
<div id="imageViewerModal" class="image-viewer-modal">
    <span class="image-viewer-close" id="imageViewerClose">&times;</span>
    <div class="image-viewer-modal-content" id="imageViewerContent">
        <img id="modalImage" src="" alt="">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/panzoom@9.4.0/dist/panzoom.min.js"></script>
<script>
// JavaScript code remains the same as in the original

function showLoginModal() {
    const authModal = document.getElementById('authModal');
    if (authModal) {
        authModal.style.display = 'flex'; // Use flex to center content
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = <?php echo $current_user_id; ?>;
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
    // Follow button functionality
    const followButton = document.getElementById('followButton');
    if (followButton) {
        followButton.addEventListener('click', function() {
            if (currentUserId === 0) {
                showLoginModal();
                return;
            }
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
                        this.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
                    } else {
                        this.classList.add('btn-following');
                        this.innerHTML = '<i class="fas fa-check"></i> Following';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
    
    // Share button functionality
    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo addslashes($upload['title']); ?>',
                    text: 'Check out this image on VisualShare',
                    url: window.location.href
                }).catch(err => {
                    console.log('Error sharing:', err);
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = window.location.href;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // Show copied message
                const originalIcon = shareButton.innerHTML;
                shareButton.innerHTML = '<i class="fas fa-check"></i>';
                shareButton.title = 'Copied!';
                setTimeout(() => {
                    shareButton.innerHTML = originalIcon;
                    shareButton.title = 'Share';
                }, 2000);
            }
        });
    }

    // Like button functionality
    const likeButton = document.getElementById('likeButton');
    if (likeButton) {
        likeButton.addEventListener('click', function() {
            if (currentUserId === 0) {
                showLoginModal();
                return;
            }
            const uploadId = this.getAttribute('data-upload-id');
            const isLiked = this.classList.contains('btn-liked');
            const action = isLiked ? 'unlike' : 'like';

            fetch('view.php?action=api&type=like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'upload_id=' + uploadId + '&action=' + action
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const likeCountEl = document.getElementById('likeCount');
                    likeCountEl.textContent = data.like_count;

                    if(action === 'like') {
                        this.classList.add('btn-liked');
                    } else {
                        this.classList.remove('btn-liked');
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }

    let currentPanzoom = null;
    let wheelListener = null;
    let dblClickListener = null;
    let lastTap = 0;

    // Full image modal functionality
    window.openModal = function(imageSrc) {
        const modal = document.getElementById('imageViewerModal');
        const modalImg = document.getElementById('modalImage');
        const modalContent = document.getElementById('imageViewerContent');

        if (modal && modalImg && modalContent) {
            document.body.classList.add('modal-open');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
            modalImg.alt = '<?php echo htmlspecialchars($upload['title']); ?>';
            
            modalImg.onload = () => {
                if (modal.style.display !== 'block') return;

                currentPanzoom = Panzoom(modalImg, {
                    maxScale: 4,
                    minScale: 1,
                    contain: 'outside',
                    cursor: 'grab'
                });

                wheelListener = (event) => {
                    event.preventDefault();
                    currentPanzoom.zoomWithWheel(event);
                };
                modalContent.addEventListener('wheel', wheelListener);

                dblClickListener = function(event) {
                    event.preventDefault();
                    if (currentPanzoom.getScale() > 1) {
                        currentPanzoom.reset({ animate: true });
                    } else {
                        currentPanzoom.zoomToPoint(2, { clientX: event.clientX, clientY: event.clientY }, { animate: true });
                    }
                };
                modalContent.addEventListener('dblclick', dblClickListener);
            };
        }
    };

    function closeImageViewerModal() {
        const modal = document.getElementById('imageViewerModal');
        const modalImg = document.getElementById('modalImage');
        const modalContent = document.getElementById('imageViewerContent');

        if (modal && modal.style.display !== 'none') {
            document.body.classList.remove('modal-open');
            modal.style.display = 'none';
            
            if (currentPanzoom) {
                if (wheelListener && modalContent) {
                    modalContent.removeEventListener('wheel', wheelListener);
                }
                if (dblClickListener && modalContent) {
                    modalContent.removeEventListener('dblclick', dblClickListener);
                }
                currentPanzoom.destroy();
            }
            
            currentPanzoom = null;
            wheelListener = null;
            dblClickListener = null;
            if (modalImg) {
                modalImg.onload = null;
                modalImg.src = ''; // Clear src to be safe
            }
        }
    }

    // Close modal when clicking the close button
    const imageViewerCloseBtn = document.getElementById('imageViewerClose');
    if (imageViewerCloseBtn) {
        imageViewerCloseBtn.addEventListener('click', closeImageViewerModal);
    }

    // Close modal when clicking outside the image
    const imageViewerModal = document.getElementById('imageViewerModal');
    if (imageViewerModal) {
        imageViewerModal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageViewerModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (document.getElementById('imageViewerModal').style.display === 'block') {
                closeImageViewerModal();
            }
        }
    });

    const imageContainer = document.querySelector('.image-container');
    if (imageContainer) {
        const skeleton = imageContainer.querySelector('.skeleton-loader');
        const image = imageContainer.querySelector('img');

        if (image) {
            const handleImageLoad = () => {
                if (skeleton) skeleton.style.opacity = '0';
                image.style.opacity = '1';
            };

            if (image.complete) {
                handleImageLoad();
            } else {
                image.addEventListener('load', handleImageLoad);
            }
        }
    }

    // Comments functionality
    function loadComments() {
        const commentsList = document.getElementById('commentsList');
        const uploadId = <?php echo isset($id) && is_numeric($id) ? (int)$id : 0; ?>;

        if (!uploadId || uploadId <= 0) {
            console.error('Invalid or missing upload ID:', uploadId);
            commentsList.innerHTML = `<div class="comment-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Unable to load comments: Invalid upload ID.</p>
            </div>`;
            return;
        }
        commentsList.innerHTML = '<div class="comment-loading"><i class="fas fa-spinner"></i><p>Loading comments...</p></div>';
        const url = `view.php?action=api&type=comment&upload_id=${uploadId}`;
        fetch(url)
            .then(response => {
                if (!response.ok) {                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // ... (existing success handling) ...

                if (data.success) {
                    if (data.comments.length === 0) {
                        commentsList.innerHTML = `
                            <div class="no-comments">
                                <i class="fas fa-comment-dots"></i>
                                <p>No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        `;
                    } else {
                        commentsList.innerHTML = data.comments.map(comment => `
                            <div class="comment-item" data-comment-id="${comment.id}" data-is-owner="${comment.is_owner}">
                                <a href="profile.php?id=${comment.user_id}" class="comment-user-link">
                                    <div class="comment-avatar">
                                        ${comment.profile_pic ?
                                            `<img src="${comment.profile_pic}" alt="${comment.username}'s avatar" loading="lazy" decoding="async">` :
                                            `<div class="comment-avatar-initials">${comment.username.charAt(0).toUpperCase()}</div>`
                                        }
                                    </div>
                                </a>
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <a href="profile.php?id=${comment.user_id}" class="comment-user-link">
                                            <span class="comment-username">${comment.username}</span>
                                        </a>
                                        <span class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</span>
                                    </div>
                                    <div class="comment-text">${comment.comment}</div>
                                    ${comment.is_owner ? `
                                        <div class="comment-actions">
                                            <button class="comment-edit-btn" data-comment-id="${comment.id}" title="Edit comment">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="comment-delete-btn" data-comment-id="${comment.id}" title="Delete comment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `).join('');
                    }
                } else {
                    commentsList.innerHTML = `<div class="comment-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>${data.message}</p>
                    </div>`;
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error.message);
                commentsList.innerHTML = `<div class="comment-error" style="display:none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load comments. Please try again later.</p>
                    <small style="color: #666; font-size: 0.8rem;">Error: ${error.message}</small>
                </div>`;
            });
    }

    // Load comments on page load
    loadComments();

    // Character counter for comment textarea
    const commentTextarea = document.querySelector('textarea[name="comment"]');
    const charCount = document.getElementById('charCount');

    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            // Remove previous classes
            charCount.classList.remove('warning', 'danger');

            // Add appropriate class based on character count
            if (count > 450) {
                charCount.classList.add('danger');
            } else if (count > 400) {
                charCount.classList.add('warning');
            }
        });
    }

    // Handle comment form submission
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (currentUserId === 0) {
                showLoginModal();
                return;
            }

            const textarea = this.querySelector('textarea');
            const comment = textarea.value.trim();

            if (!comment) {
                alert('Please enter a comment.');
                textarea.focus();
                return;
            }

            if (comment.length > 500) {
                alert('Comment is too long. Maximum 500 characters allowed.');
                textarea.focus();
                return;
            }

            const formData = new FormData();
            formData.append('upload_id', '<?php echo $id; ?>');
            formData.append('comment', comment);

            const submitButton = this.querySelector('button');
            const originalHTML = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
            submitButton.disabled = true;
            textarea.disabled = true;

            fetch('view.php?action=api&type=comment', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.reset();
                    charCount.textContent = '0';
                    charCount.classList.remove('warning', 'danger');
                    loadComments(); // Reload comments

                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'success-message';
                    successMessage.innerHTML = '<i class="fas fa-check"></i> Comment posted successfully!';
                    successMessage.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: #27ae60;
                        color: white;
                        padding: 1rem 1.5rem;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                        z-index: 10000;
                        font-weight: 500;
                    `;
                    document.body.appendChild(successMessage);
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while posting your comment. Please try again.');
            })
            .finally(() => {
                submitButton.innerHTML = originalHTML;
                submitButton.disabled = false;
                textarea.disabled = false;
            });
        });
    }

    // Edit comment functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-edit-btn')) {
            e.preventDefault();
            const button = e.target.closest('.comment-edit-btn');
            const commentId = button.dataset.commentId;
            const commentItem = button.closest('.comment-item');
            const commentText = commentItem.querySelector('.comment-text');
            const commentContent = commentItem.querySelector('.comment-content');

            // Enter edit mode
            commentItem.classList.add('comment-edit-mode');
            const editForm = document.createElement('div');
            editForm.className = 'comment-edit-form';
            editForm.innerHTML = `
                <textarea class="comment-edit-input">${commentText.textContent}</textarea>
                <div class="comment-edit-actions">
                    <button class="comment-save-btn" data-comment-id="${commentId}">Save</button>
                    <button class="comment-cancel-btn">Cancel</button>
                </div>
            `;
            commentContent.appendChild(editForm);
        }
    });

    // Save edited comment
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-save-btn')) {
            e.preventDefault();
            const button = e.target.closest('.comment-save-btn');
            const commentId = button.dataset.commentId;
            const commentItem = button.closest('.comment-item');
            const editInput = commentItem.querySelector('.comment-edit-input');
            const newComment = editInput.value.trim();

            if (!newComment) {
                alert('Comment cannot be empty.');
                return;
            }

            if (newComment.length > 500) {
                alert('Comment is too long. Maximum 500 characters allowed.');
                return;
            }

            button.textContent = 'Saving...';
            button.disabled = true;

            const params = new URLSearchParams();
            params.append('_method', 'PUT');
            params.append('comment_id', commentId);
            params.append('comment', newComment);

            fetch('view.php?action=api&type=comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadComments(); // Reload comments to show updated content
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the comment.');
            });
        }
    });

    // Cancel edit
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-cancel-btn')) {
            e.preventDefault();
            const commentItem = e.target.closest('.comment-item');
            commentItem.classList.remove('comment-edit-mode');
            const editForm = commentItem.querySelector('.comment-edit-form');
            if (editForm) {
                editForm.remove();
            }
        }
    });

    // Delete comment functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-delete-btn')) {
            e.preventDefault();
            const button = e.target.closest('.comment-delete-btn');
            const commentId = button.dataset.commentId;

            if (confirm('Are you sure you want to delete this comment?')) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                const params = new URLSearchParams();
           
                params.append('_method', 'DELETE');
                params.append('comment_id', commentId);

                fetch('view.php?action=api&type=comment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params.toString()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadComments(); // Reload comments to remove deleted comment
                    } else {
                        alert('Error: ' + data.message);
                        button.innerHTML = '<i class="fas fa-trash"></i> Delete';
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the comment.');
                    button.innerHTML = '<i class="fas fa-trash"></i> Delete';
                    button.disabled = false;
                });
            }
        }
    });

    // Save button functionality
    const saveButton = document.getElementById('saveButton');
    if (saveButton) {
        // Check if post is already saved
        const uploadId = saveButton.dataset.uploadId;
        if (currentUserId > 0) { // Only fetch if user is logged in
            fetch(`includes/toggle_favorite.php?check=${uploadId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        saveButton.classList.add('saved');
                        saveButton.title = 'Unsave';
                    } else {
                        saveButton.title = 'Save';
                    }
                })
                .catch(error => {
                    console.error('Error checking save status:', error);
                    saveButton.title = 'Save';
                });
        } else {
            saveButton.title = 'Login to save'; // Indicate to user they need to log in
        }

        saveButton.addEventListener('click', function() {
            if (currentUserId === 0) {
                showLoginModal();
                return;
            }
            const isSaved = this.classList.contains('saved');
            const originalIcon = '<i class="fas fa-bookmark"></i>';
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch('includes/toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${uploadId}&action=${isSaved ? 'unsave' : 'save'}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = originalIcon;
                    if (isSaved) {
                        this.classList.remove('saved');
                        this.title = 'Unsave';
                    } else {
                        this.classList.add('saved');
                        this.title = 'Unsave';
                    }
                } else {
                    alert('Error: ' + data.message);
                    this.innerHTML = originalIcon;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                this.innerHTML = originalIcon;
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    }

    // Download button functionality
    const downloadButton = document.getElementById('downloadButton');
    if (downloadButton) {
        downloadButton.addEventListener('click', function(e) {
            if (currentUserId === 0) {
                e.preventDefault(); // Prevent default download behavior
                showLoginModal();
            }
        });
    }
});


    
// Loading overlay handling for view page
window.addEventListener('load', function() {
    const loadingOverlay = document.getElementById('loading-overlay');
    const container = document.querySelector('.content-view-container');
    if (loadingOverlay) {
        loadingOverlay.style.opacity = '0';
        setTimeout(function() {
            loadingOverlay.style.display = 'none';
            if (container) container.style.opacity = '1';
        }, 500);
    }
});

// Fallback to hide overlay after 5 seconds
setTimeout(function() {
    const loadingOverlay = document.getElementById('loading-overlay');
    const container = document.querySelector('.content-view-container');
    if (loadingOverlay && loadingOverlay.style.display !== 'none') {
        loadingOverlay.style.opacity = '0';
        setTimeout(function() {
            loadingOverlay.style.display = 'none';
            if (container) container.style.opacity = '1';
        }, 500);
    }
}, 5000);

</script>
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

<?php require_once 'includes/footer.php'; ?>
<?php
require_once 'includes/config.php';

// ✅ Handle AJAX "like/unlike" request before page renders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');

    if ($_POST['ajax'] === 'like') {
        $response = ['success' => false, 'message' => 'Invalid request', 'like_count' => 0];

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            $response['message'] = 'Authentication required';
            echo json_encode($response);
            exit;
        }

        $upload_id = filter_var($_POST['upload_id'] ?? 0, FILTER_VALIDATE_INT);
        $action = ($_POST['action'] === 'like') ? 'like' : 'unlike';
        $user_id = (int)$_SESSION['user_id'];

        if (!$upload_id) {
            http_response_code(400);
            $response['message'] = 'Invalid content ID';
            echo json_encode($response);
            exit;
        }

        if ($conn->connect_error) {
            http_response_code(500);
            $response['message'] = 'Database connection failed';
            echo json_encode($response);
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT id FROM uploads WHERE id = ?");
            $stmt->bind_param("i", $upload_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                http_response_code(404);
                $response['message'] = 'Content not found';
                echo json_encode($response);
                exit;
            }
            $stmt->close();

            if ($action === 'like') {
                $check = $conn->prepare("SELECT id FROM likes WHERE user_id=? AND upload_id=?");
                $check->bind_param("ii", $user_id, $upload_id);
                $check->execute();
                $check->store_result();
                if ($check->num_rows === 0) {
                    $insert = $conn->prepare("INSERT INTO likes (user_id, upload_id) VALUES (?, ?)");
                    $insert->bind_param("ii", $user_id, $upload_id);
                    $insert->execute();
                    $insert->close();
                }
                $check->close();
            } else {
                $delete = $conn->prepare("DELETE FROM likes WHERE user_id=? AND upload_id=?");
                $delete->bind_param("ii", $user_id, $upload_id);
                $delete->execute();
                $delete->close();
            }

            $count_stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE upload_id=?");
            $count_stmt->bind_param("i", $upload_id);
            $count_stmt->execute();
            $count_stmt->bind_result($count);
            $count_stmt->fetch();
            $count_stmt->close();

            $response = [
                'success' => true,
                'like_count' => $count,
                'message' => ucfirst($action) . ' successful'
            ];
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            $response['message'] = 'Server error: ' . $e->getMessage();
            echo json_encode($response);
        }
        exit;
    }
    elseif ($_POST['ajax'] === 'save') {
        $response = ['success' => false, 'message' => 'Invalid request', 'saved' => false];

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            $response['message'] = 'Authentication required';
            echo json_encode($response);
            exit;
        }

        $upload_id = filter_var($_POST['upload_id'] ?? 0, FILTER_VALIDATE_INT);
        $user_id = (int)$_SESSION['user_id'];

        if (!$upload_id) {
            http_response_code(400);
            $response['message'] = 'Invalid content ID';
            echo json_encode($response);
            exit;
        }

        if ($conn->connect_error) {
            http_response_code(500);
            $response['message'] = 'Database connection failed';
            echo json_encode($response);
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT id FROM uploads WHERE id = ?");
            $stmt->bind_param("i", $upload_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                http_response_code(404);
                $response['message'] = 'Content not found';
                echo json_encode($response);
                exit;
            }
            $stmt->close();

            $check = $conn->prepare("SELECT user_id FROM user_favorites WHERE user_id=? AND upload_id=?");
            $check->bind_param("ii", $user_id, $upload_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $insert = $conn->prepare("INSERT INTO user_favorites (user_id, upload_id) VALUES (?, ?)");
                $insert->bind_param("ii", $user_id, $upload_id);
                $insert->execute();
                $insert->close();
                $response = ['success' => true, 'saved' => true, 'message' => 'Content saved successfully'];
            } else {
                $delete = $conn->prepare("DELETE FROM user_favorites WHERE user_id=? AND upload_id=?");
                $delete->bind_param("ii", $user_id, $upload_id);
                $delete->execute();
                $delete->close();
                $response = ['success' => true, 'saved' => false, 'message' => 'Content removed from saved items'];
            }
            $check->close();
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            $response['message'] = 'Server error: ' . $e->getMessage();
            echo json_encode($response);
        }
        exit;
    }
}

// Set pagination variables
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 12;
$offset = ($page - 1) * $items_per_page;

require_once 'includes/header.php';

// Initialize search variable
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Main query with error handling
try {
    // Check if required tables exist
    $tables_exist = true;
    $required_tables = ['likes', 'upload_tags', 'tags', 'user_favorites'];
    foreach ($required_tables as $table) {
        $check = $conn->query("SHOW TABLES LIKE '$table'");
        if ($check->num_rows === 0) {
            $tables_exist = false;
            break;
        }
    }

    if ($tables_exist) {
        $query = "SELECT uploads.*, users.username, users.profile_pic,
                  COUNT(DISTINCT l.id) AS like_count,
                  MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) AS user_liked,
                  MAX(CASE WHEN uf.user_id = ? THEN 1 ELSE 0 END) AS user_saved,
                  GROUP_CONCAT(DISTINCT t.name SEPARATOR ',') AS tag_names
                  FROM uploads
                  JOIN users ON uploads.user_id = users.id
                  LEFT JOIN likes l ON l.upload_id = uploads.id
                  LEFT JOIN user_favorites uf ON uf.upload_id = uploads.id
                  LEFT JOIN upload_tags ut ON ut.upload_id = uploads.id
                  LEFT JOIN tags t ON t.id = ut.tag_id";

        $params = [$current_user_id, $current_user_id];
        $types = 'ii';

        if (!empty($search_query)) {
            $query .= " WHERE (uploads.title LIKE ? 
                         OR uploads.description LIKE ? 
                         OR users.username LIKE ?
                         OR t.name LIKE ?)";
            
            $search_param = "%$search_query%";
            array_push($params, $search_param, $search_param, $search_param, $search_param);
            $types .= 'ssss';
        }

        $query .= " GROUP BY uploads.id ORDER BY upload_date DESC LIMIT ? OFFSET ?";
        array_push($params, $items_per_page, $offset);
        $types .= 'ii';

        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            throw new Exception("Query preparation failed: " . $conn->error);
        }
    } else {
        // Fallback query if tables don't exist
        $query = "SELECT uploads.*, users.username, users.profile_pic 
                  FROM uploads 
                  JOIN users ON uploads.user_id = users.id 
                  WHERE (? = '' OR uploads.title LIKE ? OR uploads.description LIKE ? OR users.username LIKE ?)
                  ORDER BY upload_date DESC LIMIT ? OFFSET ?";
        
        $stmt = $conn->prepare($query);
        $search_param = "%$search_query%";
        $stmt->bind_param("ssssii", $search_query, $search_param, $search_param, $search_param, $items_per_page, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Initialize default values
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result = new ArrayIterator($rows);
        foreach ($result as &$row) {
            $row['like_count'] = 0;
            $row['user_liked'] = 0;
            $row['user_saved'] = 0;
            $row['tag_names'] = '';
        }
    }
    
    // Get total count for pagination
    $count_query = "SELECT COUNT(DISTINCT uploads.id) as total 
                   FROM uploads 
                   JOIN users ON uploads.user_id = users.id";
    
    if ($tables_exist && !empty($search_query)) {
        $count_query .= " LEFT JOIN upload_tags ut ON ut.upload_id = uploads.id
                         LEFT JOIN tags t ON t.id = ut.tag_id
                         WHERE (uploads.title LIKE ? 
                         OR uploads.description LIKE ? 
                         OR users.username LIKE ?
                         OR t.name LIKE ?)";
    } elseif (!empty($search_query)) {
        $count_query .= " WHERE (uploads.title LIKE ? 
                         OR uploads.description LIKE ? 
                         OR users.username LIKE ?)";
    }
    
    $count_stmt = $conn->prepare($count_query);
    if (!empty($search_query)) {
        $search_param = "%$search_query%";
        if ($tables_exist) {
            $count_stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
        } else {
            $count_stmt->bind_param("sss", $search_param, $search_param, $search_param);
        }
    }
    
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_count = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_count / $items_per_page);
    
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

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

<div class="container" style="opacity: 0;">
    <h1>Latest Visual Content</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
     <div class="search-container">
        <form method="get" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       name="search" 
                       placeholder="Search titles, descriptions, tags or users..." 
                       value="<?php echo htmlspecialchars($search_query); ?>" 
                       class="search-input"
                       aria-label="Search content">
                <?php if(!empty($search_query)): ?>
                    <button type="button" class="clear-search" aria-label="Clear search">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
                <button type="submit" class="search-button">
                    Search
                </button>
            </div>
            <div class="search-tags">
                <span class="search-tag-label">Popular: </span>
                <a href="?search=landscape" class="search-tag">landscape</a>
                <a href="?search=portrait" class="search-tag">portrait</a>
                <a href="?search=art" class="search-tag">art</a>
                <a href="?search=photography" class="search-tag">photography</a>
            </div>
        </form>
    </div>
    
    <div class="masonry-grid" id="masonryGrid">
        <?php if($result->num_rows > 0): ?>
            <?php
            $items = $result->fetch_all(MYSQLI_ASSOC);
            ?>

            <?php foreach ($items as $row): ?>
                <?php
                $file_ext = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
                $is_video = in_array($file_ext, ['mp4', 'mov', 'avi', 'wmv', 'webm']);
                
                // Correct display path using filepath from DB and BASE_URL
                $display_path = BASE_URL . '/' . $row['filepath'];

                // Correct physical absolute path to check file existence
                $absolute_path = __DIR__ . '/' . $row['filepath'];
                error_log("Checking file: " . $absolute_path . " - Exists: " . (file_exists($absolute_path) ? 'true' : 'false'));
                
                // Use the stored thumbnail if available
                $thumbnail_to_show = '';
                if($is_video && !empty($row['thumbnail_path'])) {
                    $thumbnail_display_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['thumbnail_path']);
                    $thumbnail_display_path = str_replace('\\', '/', $thumbnail_display_path);
                    if (!str_starts_with($thumbnail_display_path, '/')) {
                        $thumbnail_display_path = '/' . $thumbnail_display_path;
                    }
                    // Fix: Use absolute path for file_exists check
                    $absolute_thumbnail_path = __DIR__ . '/' . $row['thumbnail_path'];
                    error_log("Checking thumbnail file: " . $absolute_thumbnail_path . " - Exists: " . (file_exists($absolute_thumbnail_path) ? 'true' : 'false'));
                    if (file_exists($absolute_thumbnail_path)) {
                        $thumbnail_to_show = $thumbnail_display_path;
                    }
                }
                
                // Generate initials for avatar fallback
                $initials = '';
                if(!empty($row['username'])) {
                    $names = explode(' ', $row['username']);
                    $initials = strtoupper(substr($names[0], 0, 1));
                    if(count($names) > 1) {
                        $initials .= strtoupper(substr(end($names), 0, 1));
                    }
                }
                
                // Parse tags
                $tags = !empty($row['tag_names']) ? explode(',', $row['tag_names']) : [];
                ?>
                <div class="masonry-item" data-upload-id="<?php echo $row['id']; ?>">
                    <a href="view.php?id=<?php echo $row['id']; ?>" class="masonry-content">
                        <?php if(file_exists($absolute_path)): ?>
                        <?php if($is_video): ?>
                            <div class="video-thumbnail-container">
                                <div class="image-skeleton"></div>
                                <?php if(!empty($thumbnail_to_show)): ?>
                                    <img src="<?php echo $thumbnail_to_show; ?>"
                                         alt="Thumbnail for <?php echo htmlspecialchars($row['title']); ?>"
                                         loading="lazy"
                                         onload="this.classList.add('loaded'); this.previousElementSibling.style.display='none';">
                                <?php else: ?>
                                    <!-- Fallback - show first frame -->
                                    <video width="100%" preload="metadata" muted onload="this.classList.add('loaded'); this.previousElementSibling.style.display='none';">
                                        <source src="<?php echo $display_path; ?>#t=<?php echo $row['thumbnail_time']; ?>"
                                                type="video/<?php echo $file_ext; ?>">
                                    </video>
                                <?php endif; ?>
                                <div class="video-play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="image-skeleton"></div>
                            <img src="<?php echo $display_path; ?>"
                                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                                 loading="lazy"
                                 onload="this.classList.add('loaded'); this.previousElementSibling.style.display='none';">
                        <?php endif; ?>
                        <?php else: ?>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>Content not found</p>
                            </div>
                        <?php endif; ?>
                        <div class="masonry-overlay">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?></p>
                            
                            <div class="item-actions">
                                <button class="like-btn <?php echo $row['user_liked'] ? 'liked' : ''; ?>" 
                                        data-upload-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-heart"></i>
                                    <span class="like-count"><?php echo $row['like_count']; ?></span>
                                </button>
                                <button class="save-btn <?php echo $row['user_saved'] ? 'saved' : ''; ?>" 
                                        data-upload-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </div>
                            
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?php if(!empty($row['profile_pic'])): ?>
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($row['profile_pic']); ?>" 
                                             alt="<?php echo htmlspecialchars($row['username']); ?>'s profile picture"
                                             class="user-profile-pic">
                                    <?php else: ?>
                                        <div class="user-avatar-initials">
                                            <?php echo $initials; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="user-meta">
                                    <span class="username"><?php echo htmlspecialchars($row['username']); ?></span>
                                    <span class="upload-date"><?php echo date('M j, Y', strtotime($row['upload_date'])); ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-content">No content found. Try adjusting your search or <a href="upload.php">upload something</a>!</p>
        <?php endif; ?>
    </div>

    <!-- Skeleton loading templates -->
    <template id="skeletonItemTemplate">
        <div class="masonry-item skeleton-item">
            <div class="skeleton-thumbnail"></div>
            <div class="skeleton-content">
                <div class="skeleton-line skeleton-title"></div>
                <div class="skeleton-line skeleton-desc"></div>
                <div class="skeleton-tags">
                    <div class="skeleton-tag"></div>
                    <div class="skeleton-tag"></div>
                </div>
                <div class="skeleton-actions">
                    <div class="skeleton-action"></div>
                    <div class="skeleton-action"></div>
                </div>
                <div class="skeleton-user">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-user-info">
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    :root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --accent-color: #4895ef;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --warning-color: #f8961e;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f5f5f5;
    color: var(--dark-color);
    line-height: 1.6;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.header {
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
}

.logo a {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
    text-decoration: none;
}



.nav ul {
    display: flex;
    list-style: none;
}

.nav ul li {
    margin-left: 1.5rem;
}

.nav ul li a {
    text-decoration: none;
    color: var(--dark-color);
    font-weight: 500;
    transition: color 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav ul li a:hover {
    color: var(--primary-color);
}

.main-content {
    padding: 2rem 0;
}

.footer {
    background-color: var(--dark-color);
    color: white;
    padding: 1.5rem 0;
    text-align: center;
    margin-top: 2rem;
}


/* Masonry Grid Fix */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    grid-gap: 20px;
    margin: 0 auto;
}

.grid-item {
    break-inside: avoid;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}



.image-placeholder {
    width: 100%;
    height: 200px;
    background-color: #f0f0f0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #999;
}

.image-placeholder i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-content {
    text-align: center;
    padding: 2rem;
    font-size: 1.2rem;
    color: #666;
}

.no-content a {
    color: var(--primary-color);
    text-decoration: none;
}

.no-content a:hover {
    text-decoration: underline;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
    transition: opacity 0.3s;
}

img[loading="lazy"] {
    opacity: 0;
    transition: opacity 0.3s;
}

img[loading="lazy"].loaded {
    opacity: 1;
}


/* Image Zoom Styles */
.image-wrapper.zoomed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    cursor: zoom-out;
}

.image-wrapper.zoomed img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

/* Drag and Drop Styles */
.upload-area.dragover {
    border-color: var(--primary-color);
    background-color: rgba(67, 97, 238, 0.1);
}

/* Loading States */
img.loading {
    opacity: 0;
    transition: opacity 0.3s ease;
}

img.loaded {
    opacity: 1;
}
/* Auth Forms */
.auth-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.auth-container h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--primary-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: background-color 0.3s;
    text-decoration: none;
    text-align: center;
}

.btn:hover {
    background-color: var(--secondary-color);
}

.btn-block {
    display: block;
    width: 100%;
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

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}



/* Upload Form */
.upload-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.upload-preview {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    margin-bottom: 1rem;
    border-radius: 4px;
    display: none;
}

.upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    margin-bottom: 1.5rem;
    cursor: pointer;
    transition: border-color 0.3s;
}

.upload-area:hover {
    border-color: var(--primary-color);
}

.upload-area i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.upload-area p {
    margin-bottom: 0.5rem;
}

/* Image View */
.image-view {
    max-width: 800px;
    margin: 2rem auto;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.image-view img {
    width: 100%;
    max-height: 600px;
    object-fit: contain;
}

.image-info {
    padding: 1.5rem;
}

.image-info h2 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.image-info p {
    color: #666;
    margin-bottom: 1rem;
}

.image-meta {
    display: flex;
    justify-content: space-between;
    color: #888;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header .container {
        flex-direction: column;
    }
    
    .nav ul {
        margin-top: 1rem;
    }
    
    .nav ul li {
        margin-left: 1rem;
        margin-right: 1rem;
    }
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
    
    /* Skeleton Loading Styles */
    .skeleton-item {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        animation: skeletonPulse 1.5s ease-in-out infinite;
        break-inside: avoid;
    }
    
    .skeleton-thumbnail {
        width: 100%;
        height: 200px;
        background: #f0f0f0;
    }
    
    .skeleton-content {
        padding: 15px;
    }
    
    .skeleton-line {
        height: 12px;
        background: #f0f0f0;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .skeleton-title {
        width: 70%;
        height: 16px;
    }
    
    .skeleton-desc {
        width: 90%;
    }
    
    .skeleton-tags {
        display: flex;
        gap: 5px;
        margin-bottom: 10px;
    }
    
    .skeleton-tag {
        width: 40px;
        height: 20px;
        background: #f0f0f0;
        border-radius: 3px;
    }
    
    .skeleton-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .skeleton-action {
        width: 30px;
        height: 30px;
        background: #f0f0f0;
        border-radius: 4px;
    }
    
    .skeleton-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid #f0f0f0;
    }
    
    .skeleton-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f0f0f0;
        flex-shrink: 0;
    }
    
    .skeleton-user-info {
        flex-grow: 1;
    }
    
    @keyframes skeletonPulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
        100% {
            opacity: 1;
        }
    }

    /* Individual Image Skeleton Loading */
    .image-skeleton {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeletonShimmer 1.5s infinite;
        border-radius: 8px 8px 0 0;
        z-index: 1;
    }

    @keyframes skeletonShimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    .video-thumbnail-container {
        position: relative;
    }

    .video-thumbnail-container .image-skeleton {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeletonShimmer 1.5s infinite;
        border-radius: 8px 8px 0 0;
        z-index: 1;
    }
    
    .masonry-column {
        display: contents; /* This allows the grid to work properly */
    }
    
    /* Search Styles */
    .search-container {
        margin: 20px auto 40px;
        max-width: 800px;
    }
    
    .search-form {
        width: 100%;
    }
    
    .search-input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .search-input-group:focus-within {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .search-icon {
        position: absolute;
        left: 20px;
        color: #777;
        font-size: 1.1rem;
        pointer-events: none;
    }
    
    .search-input {
        flex: 1;
        padding: 15px 20px 15px 50px;
        border: none;
        background: transparent;
        font-size: 1rem;
        color: #333;
        outline: none;
    }
    
    .search-input::placeholder {
        color: #999;
    }
    
    .clear-search {
        background: none;
        border: none;
        padding: 0 10px;
        color: #999;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .clear-search:hover {
        color: #555;
    }
    
    .search-button {
        padding: 12px 28px;
        background:  #3a56d4;
        color: white;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        border-radius: 30px;
        margin: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        outline: none;
        position: relative;
        overflow: hidden;
    }

    .search-button:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s, height 0.4s;
    }

    .search-button:hover:before {
        width: 200px;
        height: 200px;
    }
    
    .search-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    }
    
    .search-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
        padding: 0 15px;
    }
    
    .search-tag-label {
        color: #666;
        font-size: 0.85rem;
    }

    .search-tag {
        color: #666;
        font-size: 0.85rem;
        text-decoration: none;
        transition: color 0.2s;
    }

    .search-tag:hover {
        color: #4dabf7;
        text-decoration: underline;
    }
    
    .input-group {
        margin-bottom: 5px;
    }
    
    /* Pinterest-Style Masonry Grid */
    .masonry-grid {
        column-count: 4;
        column-gap: 20px;
        margin: 0 auto;
        max-width: 1400px;
    }

    .masonry-item {
        display: inline-block;
        width: 100%;
        margin-bottom: 20px;
        break-inside: avoid;
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .masonry-item.loaded {
        opacity: 1;
        transform: translateY(0);
    }

    .masonry-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .masonry-content {
        display: block;
        position: relative;
        color: inherit;
        text-decoration: none;
    }

    .masonry-content img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 8px 8px 0 0;
    }

    .masonry-overlay {
        padding: 15px;
    }

    .masonry-overlay h3 {
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: #333;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .masonry-overlay .description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .item-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }
    
    .item-tags .tag {
        font-size: 0.75rem;
        padding: 3px 8px;
        background: #f1f3f5;
        border-radius: 3px;
    }
    
    .tag-more {
        font-size: 0.75rem;
        color: #868e96;
        align-self: center;
    }

    .item-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .like-btn, .save-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
        color: #495057;
        transition: all 0.2s;
    }
    
    .like-btn:hover, .save-btn:hover {
        background: #f1f3f5;
    }
    
    .like-btn.liked {
        color: #ff6b6b;
    }
    
    .save-btn.saved {
        color: #4dabf7;
    }
    
    .like-btn.liked .fa-heart {
        animation: heartBeat 0.5s;
    }
    
    @keyframes heartBeat {
        0% { transform: scale(1); }
        25% { transform: scale(1.2); }
        50% { transform: scale(1); }
        75% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
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
        font-size: 0.9rem;
    }
    
    .user-meta {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        min-width: 0;
    }
    
    .username {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dark-color);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .upload-date {
        font-size: 0.8rem;
        color: #888;
    }

    .video-thumbnail-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        overflow: hidden;
        background: #000;
    }
    
    .video-thumbnail-container img,
    .video-thumbnail-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .video-play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .image-placeholder {
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
        color: #999;
    }

    .image-placeholder i {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .no-content {
        text-align: center;
        padding: 2rem;
        font-size: 1.2rem;
        color: #666;
        column-span: all;
    }

    .no-content a {
        color: var(--primary-color);
        text-decoration: none;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .masonry-grid {
            column-count: 3;
        }
    }

    @media (max-width: 900px) {
        .masonry-grid {
            column-count: 2;
        }

        .masonry-overlay {
            padding: 12px;
        }

        .masonry-overlay h3 {
            font-size: 1rem;
        }

        .masonry-overlay .description {
            font-size: 0.85rem;
        }

        .item-actions {
            gap: 8px;
        }

        .user-info {
            padding-top: 8px;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
        }

        .username {
            font-size: 0.85rem;
        }

        .upload-date {
            font-size: 0.75rem;
        }

        /* Search box size reduction */
        .search-input {
            padding: 12px 20px 12px 45px;
            font-size: 0.9rem;
        }

        .search-button {
            padding: 10px 24px;
            font-size: 0.9rem;
        }

        .search-icon {
            font-size: 1rem;
        }

        /* Title size reduction */
        h1 {
            font-size: 2rem;
        }
    }

    @media (max-width: 600px) {
        /* Further search box size reduction */
        .search-input {
            padding: 10px 15px 10px 40px;
            font-size: 0.85rem;
        }

        .search-button {
            padding: 8px 20px;
            font-size: 0.85rem;
        }

        .search-icon {
            font-size: 0.9rem;
        }

        /* Further title size reduction */
        h1 {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 400px) {
        .masonry-grid {
            column-count: 1;
        }

        .search-section {
            padding: 15px;
        }

        /* Minimal search box size */
        .search-input {
            padding: 8px 12px 8px 35px;
            font-size: 0.8rem;
        }

        .search-button {
            padding: 6px 16px;
            font-size: 0.8rem;
        }

        .search-icon {
            font-size: 0.8rem;
        }

        /* Minimal title size */
        h1 {
            font-size: 1.5rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () { 
    // Hide loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        const container = document.querySelector('.container');
        
        // Fade out loading overlay
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
                container.style.opacity = '1';
                
                // Start loading of items
                loadItemsAtOnce();
            }, 500);
        } else {
            container.style.opacity = '1';
            loadItemsAtOnce();
        }
    });
    
    // If page takes too long to load, show content anyway after 5 seconds
    setTimeout(function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        const container = document.querySelector('.container');
        
        if (loadingOverlay && loadingOverlay.style.display !== 'none') {
            loadingOverlay.style.opacity = '0';
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
                container.style.opacity = '1';
                loadItemsAtOnce();
            }, 500);
        }
    }, 5000);

    const clearSearch = document.querySelector('.clear-search');
    if (clearSearch) {
        clearSearch.addEventListener('click', function () {
            window.location.href = '?';
        });
    }
    
    // Load all items at once function
    function loadItemsAtOnce() {
        const items = document.querySelectorAll('.masonry-item:not(.skeleton-item)');
        const skeletonTemplate = document.getElementById('skeletonItemTemplate');

        // Load all items at once without delays
        items.forEach(item => {
            item.classList.add('loaded');
        });

        // If there are more pages, load them after a brief delay
        if (<?php echo $page < $total_pages ? 'true' : 'false'; ?>) {
            setTimeout(loadNextPage, 1000);
        }
    }

    // Enhanced image loading with skeleton removal
    function handleImageLoad(img) {
        const skeleton = img.previousElementSibling;
        if (skeleton && skeleton.classList.contains('image-skeleton')) {
            skeleton.style.display = 'none';
        }
    }

    // Add load event listeners to all images
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.masonry-content img');
        images.forEach(img => {
            if (img.complete) {
                handleImageLoad(img);
            } else {
                img.addEventListener('load', function() {
                    handleImageLoad(this);
                });
                img.addEventListener('error', function() {
                    handleImageLoad(this);
                });
            }
        });
    });
    
    // Function to load next page
    function loadNextPage() {
        const nextPage = <?php echo $page + 1; ?>;
        const params = new URLSearchParams(window.location.search);
        params.set('page', nextPage);
        
        // Add skeleton items for the next page
        const masonryGrid = document.getElementById('masonryGrid');
        const skeletonTemplate = document.getElementById('skeletonItemTemplate');
        
        for (let i = 0; i < 12; i++) { // Add 12 skeleton items for next page
            const skeletonItem = skeletonTemplate.content.cloneNode(true);
            masonryGrid.appendChild(skeletonItem);
        }
        
        // Fetch next page content
        fetch(`${window.location.pathname}?${params.toString()}`)
            .then(response => response.text())
            .then(html => {
                // Parse the response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract new items
                const newItems = doc.querySelectorAll('.masonry-item');
                
                // Remove skeletons
                const skeletons = document.querySelectorAll('.skeleton-item');
                skeletons.forEach(skeleton => skeleton.remove());
                
                // Add new items and load all at once
                newItems.forEach(item => {
                    masonryGrid.appendChild(item);
                    item.classList.add('loaded');
                });
                
                // Reinitialize event listeners for new items
                initLikeButtons();
                initSaveButtons();
                
                // Check if there are more pages to load
                const hasMorePages = nextPage < <?php echo $total_pages; ?>;
                if (hasMorePages) {
                    setTimeout(loadNextPage, (newItems.length + 1) * 200 + 1000);
                }
            })
            .catch(error => {
                console.error('Error loading next page:', error);
                // Remove skeletons on error
                const skeletons = document.querySelectorAll('.skeleton-item');
                skeletons.forEach(skeleton => skeleton.remove());
            });
    }
    
    // Initialize like and save buttons
    function initLikeButtons() {
        document.querySelectorAll('.like-btn').forEach(button => {
            // Remove existing event listeners to prevent duplicates
            button.removeEventListener('click', button._likeHandler);

            button._likeHandler = async function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Prevent multiple simultaneous requests
                if (this._isProcessing) {
                    return;
                }

                this._isProcessing = true;

                const uploadId = this.dataset.uploadId;
                const wasLiked = this.classList.contains('liked');
                const likeCountEl = this.querySelector('.like-count');

                const originalLikeCount = likeCountEl ? parseInt(likeCountEl.textContent || '0', 10) : 0;
                const originalClass = this.className;

                // Optimistic UI update
                this.classList.toggle('liked');
                if (likeCountEl) {
                    likeCountEl.textContent = wasLiked ? (originalLikeCount - 1) : (originalLikeCount + 1);
                }

                // Add loading state
                this.style.opacity = '0.6';
                this.style.pointerEvents = 'none';

                try {
                    const body = new URLSearchParams({
                        ajax: 'like',
                        upload_id: uploadId,
                        action: wasLiked ? 'unlike' : 'like'
                    });

                    const response = await fetch('index.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: body.toString(),
                        credentials: 'same-origin'
                    });

                    const raw = await response.text();
                    let data;

                    try {
                        data = JSON.parse(raw);
                    } catch (parseError) {
                        console.error('JSON Parse Error:', parseError, 'Raw response:', raw);
                        throw new Error(`Invalid server response: ${raw.substring(0, 100)}...`);
                    }

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    if (!data.success) {
                        throw new Error(data.message || 'Server returned error');
                    }

                    // Update with server response if available
                    if (likeCountEl && typeof data.like_count !== 'undefined') {
                        likeCountEl.textContent = String(data.like_count);
                    }

                } catch (error) {
                    console.error('Like Error:', error);

                    // Revert optimistic UI
                    this.className = originalClass;
                    if (likeCountEl) {
                        likeCountEl.textContent = String(originalLikeCount);
                    }

                    const msg = error.message || '';
                    if (msg.includes('401') || msg.includes('Authentication')) {
                        window.location.href = 'login.php?return=' + encodeURIComponent(window.location.pathname + window.location.search);
                        return;
                    } else {
                        // Show user-friendly error message
                        showAlert('Failed to update like. Please try again.', 'error');
                    }
                } finally {
                    // Reset processing state and visual feedback
                    this._isProcessing = false;
                    this.style.opacity = '';
                    this.style.pointerEvents = '';
                }
            };

            button.addEventListener('click', button._likeHandler);
        });
    }
    
    function initSaveButtons() {
        document.querySelectorAll('.save-btn').forEach(button => {
            // Remove existing event listeners to prevent duplicates
            button.removeEventListener('click', button._saveHandler);

            button._saveHandler = async function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Prevent multiple simultaneous requests
                if (this._isProcessing) {
                    return;
                }

                this._isProcessing = true;

                const uploadId = this.dataset.uploadId;
                const wasSaved = this.classList.contains('saved');
                const originalClass = this.className;

                // Optimistic UI update
                this.classList.toggle('saved');

                // Add loading state
                this.style.opacity = '0.6';
                this.style.pointerEvents = 'none';

                try {
                    const body = new URLSearchParams({
                        ajax: 'save',
                        upload_id: uploadId
                    });

                    const response = await fetch('index.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: body.toString(),
                        credentials: 'same-origin'
                    });

                    const raw = await response.text();
                    let data;

                    try {
                        data = JSON.parse(raw);
                    } catch (parseError) {
                        console.error('JSON Parse Error:', parseError, 'Raw response:', raw);
                        throw new Error(`Invalid server response: ${raw.substring(0, 100)}...`);
                    }

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    if (!data.success) {
                        throw new Error(data.message || 'Server returned error');
                    }

                    // Update UI based on server response
                    if (data.saved) {
                        this.classList.add('saved');
                    } else {
                        this.classList.remove('saved');
                    }

                    // Show success message
                    showAlert(data.message, 'success');

                } catch (error) {
                    console.error('Save Error:', error);

                    // Revert optimistic UI
                    this.className = originalClass;

                    const msg = error.message || '';
                    if (msg.includes('401') || msg.includes('Authentication')) {
                        window.location.href = 'login.php?return=' + encodeURIComponent(window.location.pathname + window.location.search);
                        return;
                    } else {
                        // Show user-friendly error message
                        showAlert('Failed to save content. Please try again.', 'error');
                    }
                } finally {
                    // Reset processing state and visual feedback
                    this._isProcessing = false;
                    this.style.opacity = '';
                    this.style.pointerEvents = '';
                }
            };

            button.addEventListener('click', button._saveHandler);
        });
    }
    
    // Initialize buttons on page load
    initLikeButtons();
    initSaveButtons();

    // Helper function to show alerts
    function showAlert(message, type) {
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
});
</script>

<?php require_once 'includes/footer.php'; ?>
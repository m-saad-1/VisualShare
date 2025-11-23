<?php
require_once 'includes/config.php';

// Set pagination variables
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 12; // Initial load count
$offset = ($page - 1) * $items_per_page;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (
    ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) ||
    (isset($_GET['ajax']) && $_GET['ajax'] === '1')
) {
    header('Content-Type: application/json');

    try {
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
            exit;
        } elseif ($_POST['ajax'] === 'save') {
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
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit;
    }
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

try {
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
        $query = "SELECT uploads.*, users.username, users.profile_pic 
                  FROM uploads 
                  JOIN users ON uploads.user_id = users.id 
                  WHERE (? = '' OR uploads.title LIKE ? OR uploads.description LIKE ? OR users.username LIKE ?)";
        $stmt = $conn->prepare($query);
        $search_param = "%$search_query%";
        $stmt->bind_param("ssssii", $search_query, $search_param, $search_param, $search_param, $items_per_page, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result = new ArrayIterator($rows);
        foreach ($result as &$row) {
            $row['like_count'] = 0;
            $row['user_liked'] = 0;
            $row['user_saved'] = 0;
            $row['tag_names'] = '';
        }
    }

    // If it's an AJAX request for more items, output JSON and exit.
    if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
        $items = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'items' => $items, 'has_more' => count($items) === $items_per_page]);
        exit;
    }

    // For initial page load, include the header.
    require_once 'includes/header.php';

    // Fetch all items for the initial page render
    $items = $result->fetch_all(MYSQLI_ASSOC);
    if (empty($search_query)) {
        shuffle($items);
    }

    // Get total count for pagination logic in JS
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
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!-- Loading overlay -->
<div id="loading-overlay" class="loading-overlay" aria-hidden="true">
    <!-- From Uiverse.io by cosnametv -->
    <div class="loader" role="status" aria-live="polite" aria-label="Loading content">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
</div>

<div class="container" style="opacity: 0; transition: opacity 0.5s ease-in-out;" tabindex="-1">
    <h1>Latest Visual Content</h1>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="search-container">
        <form method="get" class="search-form" role="search" aria-label="Search content">
            <div class="search-input-group">
                <i class="fas fa-search search-icon" aria-hidden="true"></i>
                <input type="search" 
                       name="search" 
                       placeholder="Search titles, descriptions, tags or users..." 
                       value="<?php echo htmlspecialchars($search_query); ?>" 
                       class="search-input"
                       aria-label="Search content"
                       autocomplete="off"
                       spellcheck="false"
                       />
                <?php if (!empty($search_query)) : ?>
                    <button type="button" class="clear-search" aria-label="Clear search">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
                <button type="submit" class="search-button" aria-label="Submit search">
                    Search
                </button>
            </div>
            <div class="search-tags" aria-label="Popular search tags">
                <span class="search-tag-label">Popular: </span>
                <a href="?search=landscape" class="search-tag" role="link">landscape</a>
                <a href="?search=portrait" class="search-tag" role="link">portrait</a>
                <a href="?search=art" class="search-tag" role="link">art</a>
                <a href="?search=photography" class="search-tag" role="link">photography</a>
            </div>
        </form>
    </div>

    <div class="masonry-grid" id="masonryGrid" aria-live="polite" aria-relevant="additions">
        <div class="grid-sizer"></div>
        <?php if (count($items) > 0) : ?>
            <?php foreach ($items as $row) : ?>
                <?php
                $file_ext = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
                $is_video = in_array($file_ext, ['mp4', 'mov', 'avi', 'wmv', 'webm']);
                $display_path = BASE_URL . '/' . $row['filepath'];
                $absolute_path = __DIR__ . '/' . $row['filepath'];

                // Use the stored thumbnail if available for videos
                $thumbnail_to_show = '';
                $absolute_thumbnail_path = '';
                if ($is_video && !empty($row['thumbnail_path'])) {
                    $thumbnail_display_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['thumbnail_path']);
                    $thumbnail_display_path = str_replace('\\', '/', $thumbnail_display_path);
                    if (!str_starts_with($thumbnail_display_path, '/')) {
                        $thumbnail_display_path = '/' . $thumbnail_display_path;
                    }
                    $absolute_thumbnail_path = __DIR__ . '/' . $row['thumbnail_path'];
                    if (file_exists($absolute_thumbnail_path)) {
                        $thumbnail_to_show = $thumbnail_display_path;
                    }
                }

                // Generate initials for avatar fallback
                $initials = '';
                if (!empty($row['username'])) {
                    $names = explode(' ', $row['username']);
                    $initials = strtoupper(substr($names[0], 0, 1));
                    if (count($names) > 1) {
                        $initials .= strtoupper(substr(end($names), 0, 1));
                    }
                }

                // Parse tags
                $tags = !empty($row['tag_names']) ? explode(',', $row['tag_names']) : [];
                ?>
                <div class="masonry-item" data-upload-id="<?php echo $row['id']; ?>">
                    <a href="view.php?id=<?php echo $row['id']; ?>" class="masonry-content" tabindex="0">
                        <?php if (file_exists($absolute_path)) : ?>
                            <?php if ($is_video) : ?>
                                <div class="video-thumbnail-container">
                                    <div class="image-skeleton"></div>
                                    <?php if (!empty($thumbnail_to_show)) :
                                        $thumbnail_width = 640; // Default
                                        $thumbnail_height = 360; // Default
                                        if (!empty($thumbnail_to_show) && file_exists($absolute_thumbnail_path)) {
                                            $thumb_size = getimagesize($absolute_thumbnail_path);
                                            if ($thumb_size && $thumb_size[0] > 0 && $thumb_size[1] > 0) {
                                                $thumbnail_width = $thumb_size[0];
                                                $thumbnail_height = $thumb_size[1];
                                            }
                                        } elseif ($is_video) {
                                            $thumbnail_width = 640;
                                            $thumbnail_height = 360;
                                        }
                                        ?>
                                        <img src="<?php echo $thumbnail_to_show; ?>?quality=70"
                                             alt="Thumbnail for <?php echo htmlspecialchars($row['title']); ?>"
                                             loading="lazy"
                                             decoding="async"
                                             <?php if ($thumbnail_width && $thumbnail_height) : ?>
                                             width="<?php echo $thumbnail_width; ?>"
                                             height="<?php echo $thumbnail_height; ?>"
                                             <?php endif; ?>
                                             onload="this.classList.add('loaded'); this.previousElementSibling.style.opacity='0';">
                                    <?php else : ?>
                                        <video width="100%" preload="metadata" muted 
                                               onloadeddata="this.classList.add('loaded'); this.previousElementSibling.style.display='none';"
                                               playsinline>
                                            <source src="<?php echo $display_path; ?>#t=<?php echo $row['thumbnail_time']; ?>"
                                                    type="video/<?php echo $file_ext; ?>">
                                        </video>
                                    <?php endif; ?>
                                    <div class="video-play-icon" aria-label="Play video">
                                        <i class="fas fa-play" aria-hidden="true"></i>
                                    </div>
                                </div>
                            <?php else : ?>
                                <?php
                                // Calculate aspect ratio to prevent layout shift
                                $aspect_ratio_style = 'padding-top: 100%;';
                                $image_size = @getimagesize($absolute_path);
                                if (file_exists($absolute_path)) {
                                    if ($image_size && $image_size[0] > 0 && $image_size[1] > 0) {
                                        $aspect_ratio_style = 'padding-top: ' . ($image_size[1] / $image_size[0] * 100) . '%;';
                                    }
                                }
                                ?>
                                <div class="image-aspect-ratio-container" style="<?php echo $aspect_ratio_style; ?>">
                                    <img src="<?php echo $display_path; ?>?quality=high" 
                                         alt="<?php echo htmlspecialchars($row['title']); ?>"
                                         loading="lazy"
                                         decoding="async"
                                         <?php if ($image_size && $image_size[0] > 0 && $image_size[1] > 0) : ?>
                                         width="<?php echo $image_size[0]; ?>"
                                         height="<?php echo $image_size[1]; ?>"
                                         <?php endif; ?>
                                         class="lazy-image">
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="image-placeholder" role="img" aria-label="Content not found">
                                <i class="fas fa-image" aria-hidden="true"></i>
                                <p>Content not found</p>
                            </div>
                        <?php endif; ?>
                        <div class="masonry-overlay">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?></p>

                            <div class="masonry-footer">
                                <div class="user-info">
                                    <div class="user-avatar" aria-label="User avatar">
                                        <?php if (!empty($row['profile_pic'])) : ?>
                                            <img src="<?php echo BASE_URL . '/' . htmlspecialchars($row['profile_pic']); ?>" 
                                                 alt="<?php echo htmlspecialchars($row['username']); ?>'s profile picture"
                                                 class="user-profile-pic"
                                                 loading="lazy"
                                                 decoding="async"
                                                 onload="this.classList.add('loaded');">
                                        <?php else : ?>
                                            <div class="user-avatar-initials" aria-hidden="true">
                                                <?php echo $initials; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="user-meta">
                                        <span class="username"><?php echo htmlspecialchars($row['username']); ?></span>
                                        <span class="upload-date"><?php echo date('M j, Y', strtotime($row['upload_date'])); ?></span>
                                    </div>
                                </div>
                                <div class="item-actions">
                                    <button class="like-btn <?php echo $row['user_liked'] ? 'liked' : ''; ?>" 
                                            data-upload-id="<?php echo $row['id']; ?>" aria-label="Like">
                                        <i class="fas fa-heart" aria-hidden="true"></i>
                                        <span class="like-count"><?php echo $row['like_count']; ?></span>
                                    </button>
                                    <button class="save-btn <?php echo $row['user_saved'] ? 'saved' : ''; ?>" 
                                            data-upload-id="<?php echo $row['id']; ?>" aria-label="Save">
                                        <i class="fas fa-bookmark" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="no-content">No content found. Try adjusting your search or <a href="upload.php">upload something</a>!</p>
        <?php endif; ?>
    </div>
    <div id="loader" class="infinite-scroll-loader" style="display: none;" aria-label="Loading more content">
        <div class="loader">
            <span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    <div id="load-more-container" class="load-more-container" style="display: none;">
        <p id="load-error-message" style="display: none; color: #dc3545; margin-bottom: 10px;"></p>
        <button id="load-more-btn" class="btn btn-primary">
            <i class="fas fa-plus"></i> Load More
        </button>
    </div>
</div>

<style>
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
        z-index: 1;
        transition: opacity 0.3s;
    }

    .infinite-scroll-loader {
        text-align: center;
        padding: 20px;
        width: 100%;
    }

    @keyframes skeletonShimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
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
        color: 333;
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
        margin: 0 auto;
        max-width: 1400px;
        position: relative; /* Required for absolute positioning of items */
    }

    /* Gutter for masonry items */
    .masonry-grid:after {
        content: '';
        display: block;
        clear: both;
    }

    .grid-sizer,
    .masonry-item {
        width: 23%; /* Default for 4 columns */
    }

    .masonry-item {
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Removed break-inside and display: inline-block */
        /* Masonry.js will handle positioning */
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
        transition: opacity 0.3s;
    }

    .masonry-content img[loading="lazy"] {
        opacity: 0;
    }

    .masonry-content img.loaded {
        opacity: 1;
    }

    .masonry-overlay {
        padding: 15px;
    }

    .masonry-overlay h3 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: #333;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .masonry-overlay .description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 4px; /* Decreased gap */
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .masonry-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
    }
    
    .item-actions {
        display: flex;
        gap: 1px;
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

    /* Styles for aspect-ratio image containers to prevent layout shift */
    .image-aspect-ratio-container {
        position: relative;
        height: 0;
        overflow: hidden;
        background-color: #f0f0f0;
        border-radius: 8px 8px 0 0;
    }

    .image-aspect-ratio-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .load-more-container {
        text-align: center;
        padding: 20px;
    }

    .load-more-container .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .load-more-container .btn-primary:hover {
        background-color: var(--primary-light);
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

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .grid-sizer,
        .masonry-item {
            width: 31%; /* 3 columns */
        }
    }

    @media (max-width: 900px) {
        .grid-sizer,
        .masonry-item {
            width: 48%; /* 2 columns */
        }

        .masonry-overlay {
            padding: 12px;
        }

        .masonry-item {
            margin-bottom: 15px;
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

    @media (max-width: 768px) {
        .masonry-item {
            margin-bottom: 10px; /* Reduce spacing between image cards */

        .save-btn {
            display: none; /* Hide save button */
        }

        .masonry-overlay h3 {
            font-size: 0.9rem; /* Decrease font size */
            margin-bottom: 3px; /* Decrease spacing */
        }

        .masonry-overlay .description {
            font-size: 0.8rem; /* Decrease font size */
            margin-bottom: 2px; /* Decrease spacing */
        }

        .user-info {
            gap: 5px; /* Reduce spacing */
        }

        .username {
            font-size: 0.8rem; /* Decrease font size */
        }

        .upload-date {
            font-size: 0.7rem; /* Decrease font size */
        }
    }

    @media (max-width: 600px) {
        .item-actions {
            display: none; /* Hide both like and save buttons below 600px */
        }
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

    @media (max-width: 500px) {
        .masonry-overlay .description {
            margin-bottom: 1px; /* Even smaller gap for very small screens */
        }

    .masonry-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    }

    @media (max-width: 400px) {
        .grid-sizer,
        .masonry-item {
            width: 98%; /* 1 column */
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
document.addEventListener('DOMContentLoaded', function() {
    // --- Page Load, Infinite Scroll, and Actions ---
    let isLoading = false;
    let currentPage = <?php echo $page; ?>;
    const container = document.querySelector('.container');
    const lazyImages = document.querySelectorAll('img.lazy-image');
    const loaderEl = document.getElementById('loader');
    const loadMoreContainer = document.getElementById('load-more-container');
    const grid = document.getElementById('masonryGrid');
    let msnry;

    // Initialize Masonry after images are loaded
    imagesLoaded(grid, function () {
        msnry = new Masonry(grid, { // ... Masonry options
            itemSelector: '.masonry-item', columnWidth: '.grid-sizer', gutter: 20, percentPosition: true, transitionDuration: '0.4s'
        });
    });

    // --- Robust Lazy Loading with IntersectionObserver Fallback ---
    if ('loading' in HTMLImageElement.prototype) {
        // Native lazy loading is supported.
        // The browser will handle it. We just need to ensure images fade in.
        lazyImages.forEach(img => {
            // Use a temporary image to detect when the real one has loaded
            const tempImg = new Image();
            tempImg.src = img.src;
            tempImg.onload = () => {
                img.classList.add('loaded');
                if (msnry) {
                    imagesLoaded(img, () => msnry.layout());
                }
            };
        });
    } else {
        // Fallback to IntersectionObserver
        const lazyLoad = (target) => {
            const io = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        // The src is already the high-quality one, we just need to load it.
                        img.src = img.src; // Re-assigning src triggers load
                        img.onload = () => {
                            img.classList.add('loaded');
                            if (msnry) {
                                imagesLoaded(img, () => msnry.layout());
                            }
                        };
                        observer.unobserve(img);
                    }
                });
            });
            io.observe(target);
        };
        lazyImages.forEach(lazyLoad);
    }
    
    const handlePageLoad = () => {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0'; // Start fade out
            setTimeout(function() {
                loadingOverlay.style.display = 'none';
                if (container) container.style.opacity = '1';
            }, 500); // Match CSS transition
        } else if (container) {
            container.style.opacity = '1';
        }
    };

    window.addEventListener('load', handlePageLoad);

    // Fallback: hide overlay after 5 seconds in case 'load' event fails
    setTimeout(function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (container && (!container.style.opacity || container.style.opacity === '0')) {
            handlePageLoad();
        } else if (loadingOverlay && loadingOverlay.style.display !== 'none') {
             loadingOverlay.style.opacity = '0';
             setTimeout(() => { loadingOverlay.style.display = 'none'; }, 500);
        }
    }, 5000);

    const clearSearch = document.querySelector('.clear-search');
    if (clearSearch) {
        clearSearch.addEventListener('click', function () {
            window.location.href = '?';
        });
    }

    // --- Infinite Scroll ---
    const observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && !isLoading) {
                fetchMoreItems();
            }
        }, { rootMargin: "0px 0px 400px 0px" }
    );
    
    if (loaderEl) {
        observer.observe(loaderEl);
    }

    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            loadMoreContainer.style.display = 'none';
            fetchMoreItems();
        });
    }

    async function fetchMoreItems() {
        const params = new URLSearchParams(window.location.search);
        isLoading = true;
        currentPage++;

        params.set('page', currentPage);
        params.set('ajax', '1');

        let data;
        try {
            const response = await fetch(`index.php?${params.toString()}`);
            loaderEl.style.display = 'block';
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            if (data && data.success && data.items.length > 0) {
                const fragment = document.createDocumentFragment();
                const newItems = [];
                data.items.forEach(item => {
                    const newItem = createMasonryItem(item);
                    fragment.appendChild(newItem);
                    newItems.push(newItem);
                });
                grid.appendChild(fragment);

                // Use imagesLoaded for new items, then append to Masonry
                imagesLoaded(newItems, function() {
                    msnry.appended(newItems);
                });
                initLikeButtons();
                initSaveButtons();
            }

            if (!data.has_more) {
                observer.unobserve(loaderEl);
            }

        } catch (error) {
            console.error("Failed to fetch more items:", error);
            // Show error message and load more button
            const errorMessageEl = document.getElementById('load-error-message');
            if (errorMessageEl) {
                errorMessageEl.textContent = 'Failed to load content. Please try again.';
                errorMessageEl.style.display = 'block';
            }
            if (loadMoreContainer) {
                loadMoreContainer.style.display = 'block';
            }
            observer.unobserve(loaderEl); // Stop observing on error
        } finally {
            isLoading = false;
            loaderEl.style.display = 'none';
        }
    }

    function createMasonryItem(row) {
        const file_ext = (row.filename.split('.').pop() || '').toLowerCase();
        const is_video = ['mp4', 'mov', 'avi', 'wmv', 'webm'].includes(file_ext);
        const display_path = '<?php echo BASE_URL; ?>/' + row.filepath;

        let thumbnail_to_show = '';
        if (is_video && row.thumbnail_path) {
            let thumbnail_display_path = row.thumbnail_path.replace(/\\/g, '/');
            if (!thumbnail_display_path.startsWith('/')) {
                thumbnail_display_path = '/' + thumbnail_display_path;
            }
            thumbnail_to_show = thumbnail_display_path;
        }

        let initials = '';
        if (row.username) {
            const names = row.username.split(' ');
            initials = (names[0][0] || '').toUpperCase();
            if (names.length > 1) {
                initials += (names[names.length - 1][0] || '').toUpperCase();
            }
        }

        const itemDiv = document.createElement('div');
        itemDiv.className = 'masonry-item';
        itemDiv.dataset.uploadId = row.id;

        let mediaHtml;
        if (is_video) {
            mediaHtml = `
                <div class="video-thumbnail-container">
                    <div class="image-skeleton"></div>
                    ${thumbnail_to_show ? `
                        <img src="${thumbnail_to_show}?quality=70" alt="Thumbnail for ${row.title}" loading="lazy" decoding="async" onload="this.classList.add('loaded'); this.previousElementSibling.style.display='none';">
                    ` : `
                        <video width="100%" preload="metadata" muted onloadeddata="this.classList.add('loaded'); this.previousElementSibling.style.display='none';" playsinline>
                            <source src="${display_path}#t=${row.thumbnail_time}" type="video/${file_ext}">
                        </video>
                    `}
                    <div class="video-play-icon" aria-label="Play video"><i class="fas fa-play" aria-hidden="true"></i></div>
                </div>`;
        } else {
            mediaHtml = `
                <div class="image-aspect-ratio-container" style="padding-top: 100%;">
                    <div class="image-skeleton"></div>
                    <img src="${display_path}?quality=70" alt="${row.title}" loading="lazy" decoding="async" onload="
                        this.classList.add('loaded'); 
                        this.previousElementSibling.style.opacity = '0';
                        // Set aspect ratio on the container after image loads to help Masonry
                        const aspectRatio = this.naturalHeight / this.naturalWidth;
                        if (aspectRatio) {
                            this.parentElement.style.paddingTop = (aspectRatio * 100) + '%';
                        }
                        if (msnry) { msnry.layout(); }
                    ">
                </div>`;
        }

        itemDiv.innerHTML = `
            <a href="view.php?id=${row.id}" class="masonry-content" tabindex="0">
                ${mediaHtml}
                <div class="masonry-overlay">
                    <h3>${row.title}</h3>
                    <p class="description">${row.description.substring(0, 100)}</p>
                    <div class="masonry-footer">
                        <div class="user-info">
                            <div class="user-avatar" aria-label="User avatar">
                                ${row.profile_pic ? `<img src="<?php echo BASE_URL; ?>/${row.profile_pic}" alt="${row.username}'s profile picture" class="user-profile-pic" loading="lazy" decoding="async" onload="this.classList.add('loaded');">` : `<div class="user-avatar-initials" aria-hidden="true">${initials}</div>`}
                            </div>
                            <div class="user-meta">
                                <span class="username">${row.username}</span>
                                <span class="upload-date">${new Date(row.upload_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="like-btn ${row.user_liked ? 'liked' : ''}" data-upload-id="${row.id}" aria-label="Like"><i class="fas fa-heart" aria-hidden="true"></i><span class="like-count">${row.like_count}</span></button>
                            <button class="save-btn ${row.user_saved ? 'saved' : ''}" data-upload-id="${row.id}" aria-label="Save"><i class="fas fa-bookmark" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </a>`;
        return itemDiv;
    }
    
    // --- Like/Save Button Logic ---
    function initLikeButtons() {
        document.querySelectorAll('.like-btn').forEach(button => {
            if (button._likeHandler) return; // Already initialized

            button._likeHandler = async function (e) {
                e.preventDefault();
                e.stopPropagation();

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

                    if (likeCountEl && typeof data.like_count !== 'undefined') {
                        likeCountEl.textContent = String(data.like_count);
                    }

                } catch (error) {
                    console.error('Like Error:', error);
                    this.className = originalClass;
                    if (likeCountEl) {
                        likeCountEl.textContent = String(originalLikeCount);
                    }
                    const msg = error.message || '';
                    if (msg.includes('401') || msg.includes('Authentication')) {
                        showLoginModal();
                        return;
                    } else {
                        showAlert('Failed to update like. Please try again.', 'error');
                    }
                } finally {
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
            if (button._saveHandler) return; // Already initialized

            button._saveHandler = async function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (this._isProcessing) {
                    return;
                }

                this._isProcessing = true;

                const uploadId = this.dataset.uploadId;
                const wasSaved = this.classList.contains('saved');
                const originalClass = this.className;

                this.classList.toggle('saved');

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

                    if (data.saved) {
                        this.classList.add('saved');
                    } else {
                        this.classList.remove('saved');
                    }

                    showAlert(data.message, 'success');

                } catch (error) {
                    console.error('Save Error:', error);
                    this.className = originalClass;
                    const msg = error.message || '';
                    if (msg.includes('401') || msg.includes('Authentication')) {
                        showLoginModal();
                        return;
                    } else {
                        showAlert('Failed to save content. Please try again.', 'error');
                    }
                } finally {
                    this._isProcessing = false;
                    this.style.opacity = '';
                    this.style.pointerEvents = '';
                }
            };

            button.addEventListener('click', button._saveHandler);
        });
    }

    initLikeButtons();
    initSaveButtons();

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
        
        setTimeout(() => {
            alert.style.animation = 'fadeOut 0.3s';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 3000);
    }

    function showLoginModal() {
        const authModal = document.getElementById('authModal');
        if (authModal) {
            authModal.style.display = 'flex';
        }
    }

    document.querySelector('#authModal .close-button').addEventListener('click', function() {
        document.getElementById('authModal').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        const authModal = document.getElementById('authModal');
        if (event.target === authModal) {
            authModal.style.display = 'none';
        }
    });
});
</script>

<!-- Masonry & ImagesLoaded CDN -->
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

<?php require_once 'includes/footer.php'; ?>

<!-- Login/Register Modal -->
<div id="authModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="authModalTitle" style="display:none;">
    <div class="modal-content">
        <span class="close-button" role="button" aria-label="Close">&times;</span>
        <h2 id="authModalTitle">Join Our Community!</h2>
        <p>Like, save, and follow to connect with creators and discover more amazing content.</p>
        <div class="modal-actions">
            <a href="login.php" class="btn btn-primary">Log In</a>
            <a href="register.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>
</div>

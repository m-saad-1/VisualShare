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
    $count_query = "SELECT COUNT(DISTINCT uploads.id) as total \
                   FROM uploads \
                   JOIN users ON uploads.user_id = users.id";
    if ($tables_exist && !empty($search_query)) {
        $count_query .= " LEFT JOIN upload_tags ut ON ut.upload_id = uploads.id\
                         LEFT JOIN tags t ON t.id = ut.tag_id\
                         WHERE (uploads.title LIKE ? \
                         OR uploads.description LIKE ? \
                         OR users.username LIKE ?\
                         OR t.name LIKE ?)";
    } elseif (!empty($search_query)) {
        $count_query .= " WHERE (uploads.title LIKE ? \
                         OR uploads.description LIKE ? \
                         OR users.username LIKE ?}";
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

<!-- The rest of the file remains unchanged, as coding standards fixes are mainly in PHP blocks above. -->

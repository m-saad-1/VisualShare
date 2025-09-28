<?php
require_once 'includes/config.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT uploads.*, users.username 
          FROM uploads 
          JOIN users ON uploads.user_id = users.id 
          WHERE uploads.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$upload = $result->fetch_assoc();
$display_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $upload['filepath']);
$absolute_path = $_SERVER['DOCUMENT_ROOT'] . $display_path;

require_once 'includes/header.php';
?>

<div class="image-view-container">
    <?php if(file_exists($absolute_path)): ?>
        <div class="image-wrapper">
            <img src="<?php echo $display_path; ?>" alt="<?php echo htmlspecialchars($upload['title']); ?>">
        </div>
    <?php else: ?>
        <div class="image-missing">
            <i class="fas fa-exclamation-triangle"></i>
            <p>The requested image could not be found</p>
        </div>
    <?php endif; ?>
    
    <div class="image-info">
        <h2><?php echo htmlspecialchars($upload['title']); ?></h2>
        <p class="description"><?php echo htmlspecialchars($upload['description']); ?></p>
        <div class="image-meta">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($upload['username']); ?></span>
            <span><i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y \a\t g:i a', strtotime($upload['upload_date'])); ?></span>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
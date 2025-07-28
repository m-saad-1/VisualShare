<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch all uploads
$query = "SELECT uploads.*, users.username 
          FROM uploads 
          JOIN users ON uploads.user_id = users.id 
          ORDER BY upload_date DESC";
$result = $conn->query($query);
?>

<div class="container">
    <h1>Latest Visual Content</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="masonry-grid">
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                $display_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['filepath']);
                $absolute_path = $_SERVER['DOCUMENT_ROOT'] . $display_path;
                ?>
                <div class="masonry-item">
                    <a href="view.php?id=<?php echo $row['id']; ?>" class="masonry-content">
                        <?php if(file_exists($absolute_path)): ?>
                            <img src="<?php echo $display_path; ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>Image not found</p>
                            </div>
                        <?php endif; ?>
                        <div class="masonry-overlay">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?></p>
                            <div class="masonry-meta">
                                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['username']); ?></span>
                                <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($row['upload_date'])); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-content">No content available yet. Be the first to <a href="upload.php">upload</a>!</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
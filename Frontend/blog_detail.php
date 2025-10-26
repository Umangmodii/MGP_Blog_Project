<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electronica Blogs</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/blog_detail.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
<body>

<?php
include 'header.php';
require_once __DIR__ . '/../Backend/Database/connection.php';

$blog = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM blogs_management WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
}

$recent_posts = [];
$recent_q = $conn->query("SELECT id, title FROM blogs_management ORDER BY published_date DESC, id DESC LIMIT 5");
while ($row = $recent_q->fetch_assoc()) {
    $recent_posts[] = $row;
}

$popular_posts = [];
$popular_q = $conn->query("SELECT id, title, image, published_date, author, content FROM blogs_management ORDER BY published_date DESC, id DESC LIMIT 2");
while ($row = $popular_q->fetch_assoc()) {
    $popular_posts[] = $row;
}
?>

<section id="center" class="center_shop clearfix">
  <div class="container">
    <div class="row">
      <div class="center_shop_1 text-center clearfix">
        <div class="col-sm-12">
          <h1 class="mgt">Blog Details</h1>
        </div>
      </div>
      <div class="blog_1 clearfix">
        <div class="col-sm-9">
          <?php if ($blog): ?>
            <div class="blog_1l mgt clearfix" style="margin-bottom:40px; border-bottom: 1px #eee solid;">
              <img src="<?php echo htmlspecialchars($blog['image'] ? $blog['image'] : 'img/default_blog.jpg'); ?>" class="iw" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="max-width:100%;height:auto;">
              <h2 class="mt-3">
                <span>
                  <?php
                    if (!empty($blog['published_date'])) {
                      echo date('d M', strtotime($blog['published_date']));
                    }
                  ?>
                </span>
                <?php echo htmlspecialchars($blog['title']); ?>
              </h2>
              <h5>by <?php echo htmlspecialchars($blog['author'] ?? 'admin'); ?></h5>
              <article>
                <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
              </article>
            </div>
          <?php else: ?>
            <div class="alert alert-info mt-3">Blog not found.</div>
          <?php endif; ?>
        </div>
        <div class="col-sm-3">
          <div class="blog_1r clearfix">
            <h2 class="mgt">Search Here</h2>
            <form method="get" action="blog.php">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search" value="">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
            <br>
            <h2>Recent Posts</h2>
            <ul>
              <?php foreach ($recent_posts as $rp): ?>
                <li>
                  <a href="blog_detail.php?id=<?php echo (int)$rp['id']; ?>">
                    <i class="fa fa-chevron-right"></i> <?php echo htmlspecialchars($rp['title']); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <br>
          <div class="blog_1r1 clearfix">
            <h2>Popular Posts</h2>
            <?php foreach ($popular_posts as $pp): ?>
              <div class="blog_1r1i clearfix">
                <a href="blog_detail.php?id=<?php echo (int)$pp['id']; ?>">
                  <img src="<?php echo htmlspecialchars($pp['image'] ? $pp['image'] : 'img/default_blog.jpg'); ?>" class="iw" alt="<?php echo htmlspecialchars($pp['title']); ?>">
                </a>
                <h5>
                  <a href="blog_detail.php?id=<?php echo (int)$pp['id']; ?>">
                    <span class="span_1">
                      <?php
                        echo !empty($pp['published_date'])
                          ? date('d M', strtotime($pp['published_date']))
                          : '';
                      ?>
                    </span>
                    by <?php echo htmlspecialchars($pp['author'] ?? 'admin'); ?>
                    <span class="pull-right span_2"><i class="fa fa-comment-o"></i> 0</span>
                  </a>
                </h5>
                <h5 class="bold">
                  <a href="blog_detail.php?id=<?php echo (int)$pp['id']; ?>">
                    <?php echo htmlspecialchars($pp['title']); ?>
                  </a>
                </h5>
                <p><?php
                  $excerpt2 = isset($pp['content']) ? strip_tags($pp['content']) : '';
                  echo (mb_strlen($excerpt2) > 70) ? mb_substr($excerpt2, 0, 70) . "..." : $excerpt2;
                ?></p>
              </div>
              <br>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
  include 'footer.php';
?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electronica</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<link href="css/blog.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
	<script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
<body> 	
<?php
	include 'header.php';
?>

<section id="center" class="center_shop clearfix">
 <div class="container">
  <div class="row">
   <div class="center_shop_1 text-center clearfix">
    <div class="col-sm-12">
	   <h1 class="mgt">Blog Posts</h1>
	</div>
   </div>
   <div class="blog_1 clearfix">
   <div class="col-sm-9">
    <?php
        require_once __DIR__ . '/../Backend/Database/connection.php';

        $per_page = 5;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $per_page;

        $count_result = $conn->query("SELECT COUNT(*) as total FROM blogs_management");
        $total_blogs = $count_result ? intval($count_result->fetch_assoc()['total']) : 0;
        $total_pages = ceil($total_blogs / $per_page);

        $sql = "SELECT id, title, author, image, published_date FROM blogs_management ORDER BY published_date DESC LIMIT $per_page OFFSET $offset";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while($blog = $result->fetch_assoc()):
    ?>
        <div class="blog_1l mgt clearfix" style="margin-bottom: 35px;">
            <a href="blog_detail.php?id=<?php echo htmlspecialchars($blog['id']); ?>">
                <img src="<?php echo !empty($blog['image']) ? htmlspecialchars($blog['image']) : 'img/30.jpg'; ?>" class="iw" alt="blog-thumbnail-<?php echo htmlspecialchars($blog['id']); ?>">
            </a>
            <h2>
                <a href="blog_detail.php?id=<?php echo htmlspecialchars($blog['id']); ?>">
                    <span>
                        <?php echo date('d M', strtotime($blog['published_date'])); ?>
                    </span>
                    <?php echo htmlspecialchars($blog['title']); ?>
                </a>
            </h2>
            <h5 class="text-muted"><small>By <?php echo htmlspecialchars($blog['author']); ?></small></h5>
       
            <h5>
                <a class="button" href="blog_detail.php?id=<?php echo htmlspecialchars($blog['id']); ?>">Read More</a>
            </h5>
        </div>
    <?php
            endwhile;
        else:
    ?>
        <div class="alert alert-info">No blog posts found.</div>
    <?php endif; ?>

    <div class="center_product_1r4r clearfix">
      <div class="col-sm-12 space_all">
       <ul class="pagination mgt">
        <?php if ($page > 1): ?>
          <li><a href="?page=<?php echo $page-1; ?>">&laquo;</a></li>
        <?php else: ?>
          <li class="disabled"><span>&laquo;</span></li>
        <?php endif; ?>

        <?php
        $range = 2;
        $start = max(1, $page - $range);
        $end = min($total_pages, $page + $range);

        for ($i = $start; $i <= $end; $i++):
        ?>
            <li<?php if ($i === $page) echo ' class="active"'; ?>>
                <a href="?page=<?php echo $i; ?>">
                    <?php echo $i; ?>
                    <?php if ($i === $page): ?><span class="sr-only">(current)</span><?php endif; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li><a href="?page=<?php echo $page+1; ?>">&raquo;</a></li>
        <?php else: ?>
          <li class="disabled"><span>&raquo;</span></li>
        <?php endif; ?>
       </ul>
      </div>
     </div>
   </div>
   <div class="col-sm-3">
    <div class="blog_1r clearfix">
     <h2 class="mgt">Search Here</h2>
     <form class="input-group" method="get" action="">
        <input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-search"></i>
        </button>
        </span>
     </form>
     <br>
     <h2>Recent Posts</h2>
     <ul>
        <?php
        $recent_result = $conn->query("SELECT id, title FROM blogs_management ORDER BY published_date DESC LIMIT 5");
        if ($recent_result && $recent_result->num_rows > 0):
            while ($recent_post = $recent_result->fetch_assoc()):
        ?>
          <li>
            <a href="blog_detail.php?id=<?php echo htmlspecialchars($recent_post['id']); ?>">
                <i class="fa fa-chevron-right"></i>
                <?php echo htmlspecialchars($recent_post['title']); ?>
            </a>
          </li>
        <?php endwhile; else: ?>
          <li><span class="text-muted">No recent posts.</span></li>
        <?php endif;?>
     </ul>
    </div><br>
	
    <div class="blog_1r1 clearfix">
     <h2>Popular Posts</h2>
     <?php
        $popular_result = $conn->query(
            "SELECT id, title, image, published_date, author FROM blogs_management ORDER BY published_date DESC LIMIT 2"
        );
        if ($popular_result && $popular_result->num_rows > 0):
            while ($pop = $popular_result->fetch_assoc()):
     ?>
     <div class="blog_1r1i clearfix">
        <a href="blog_detail.php?id=<?php echo htmlspecialchars($pop['id']); ?>">
          <img src="<?php echo !empty($pop['image']) ? htmlspecialchars($pop['image']) : 'img/30.jpg'; ?>" class="iw" alt="">
        </a>
        <h5>
          <a href="blog_detail.php?id=<?php echo htmlspecialchars($pop['id']); ?>">
            <span class="span_1"><?php echo date('d M', strtotime($pop['published_date'])); ?> </span>
            by <?php echo htmlspecialchars($pop['author']); ?>
          </a>
        </h5>
        <h5 class="bold"><a href="blog_detail.php?id=<?php echo htmlspecialchars($pop['id']); ?>"><?php echo htmlspecialchars($pop['title']); ?></a></h5>
 
     </div><br>
     <?php
            endwhile;
        else:
     ?>
      <div class="text-muted" style="padding:10px 0 10px 10px">No popular posts.</div>
     <?php endif; ?>
    </div>
   </div>
  </div>
  </div>

 </div>
</section>

<?php
    // Make sure the footer is properly included only once and outside any earlier closing PHP tags.
    include_once 'footer.php';
?>

</body>
</html>

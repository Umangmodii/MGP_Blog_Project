<?php

require_once __DIR__ . '/Database/connection.php';

function requireLogin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_email'])) {
        header("Location: /../MGP_Blog_Project/Frontend/login.php");
        exit();
    }
}
requireLogin();
$user_email = $_SESSION['user_email'];

class BlogManager {
    private $conn;
    public $msg = '';
    public $msg_type = '';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        if (isset($_POST['add_blog'])) {
            $this->addBlog();
        } elseif (isset($_POST['delete_id'])) {
            $this->deleteBlog(intval($_POST['delete_id']));
        } elseif (isset($_POST['edit_id'])) {
            $this->editBlog(intval($_POST['edit_id']));
        }

        if ($this->msg_type == 'success') {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }

    private function validateImage($file, &$imagePath) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return false;
        $uploads_dir = __DIR__ . '/../Frontend/img/blogs';
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $tmp_name = $file['tmp_name'];
        $basename = basename($file['name']);
        $target_file = $uploads_dir . "/" . uniqid() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_types)) {
            $this->msg = "Invalid image file type.";
            $this->msg_type = "danger";
            return false;
        }
        if (move_uploaded_file($tmp_name, $target_file)) {
            $imagePath = 'img/blogs/' . basename($target_file);
            return true;
        } else {
            $this->msg = "Failed to upload image.";
            $this->msg_type = "danger";
            return false;
        }
    }

    public function addBlog() {
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $published_date = trim($_POST['published_date'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image = null;

        if ($title === '' || $author === '' || $published_date === '' || $content === '') {
            $this->msg = "All fields except image are required.";
            $this->msg_type = "danger";
            return;
        }

        if (
            (!empty($_FILES['image']['name']) && $this->validateImage($_FILES['image'], $image))
            || empty($_FILES['image']['name'])
        ) {
            $stmt = $this->conn->prepare(
                "INSERT INTO blogs_management (title, author, published_date, content, image) VALUES (?, ?, ?, ?, ?)"
            );
            if ($stmt) {
                $stmt->bind_param('sssss', $title, $author, $published_date, $content, $image);
                if ($stmt->execute()) {
                    $this->msg = "Blog successfully added!";
                    $this->msg_type = 'success';
                } else {
                    $this->msg = "Failed to add blog. (" . $this->conn->error . ")";
                    $this->msg_type = 'danger';
                }
                $stmt->close();
            } else {
                $this->msg = "Failed to prepare database statement.";
                $this->msg_type = 'danger';
            }
        }
    }

    public function deleteBlog($id) {
        $get_img_stmt = $this->conn->prepare("SELECT image FROM blogs_management WHERE id = ?");
        if ($get_img_stmt) {
            $get_img_stmt->bind_param('i', $id);
            $get_img_stmt->execute();
            $get_img_stmt->bind_result($del_img);
            if ($get_img_stmt->fetch() && !empty($del_img)) {
                $img_path = __DIR__ . '/../Frontend/' . $del_img;
                if (is_file($img_path)) {
                    @unlink($img_path);
                }
            }
            $get_img_stmt->close();
        }
        $del_stmt = $this->conn->prepare("DELETE FROM blogs_management WHERE id = ?");
        if ($del_stmt) {
            $del_stmt->bind_param('i', $id);
            if ($del_stmt->execute()) {
                $this->msg = "Blog deleted successfully!";
                $this->msg_type = 'success';
            } else {
                $this->msg = "Failed to delete blog.";
                $this->msg_type = 'danger';
            }
            $del_stmt->close();
        }
    }

    public function editBlog($edit_id) {
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $published_date = trim($_POST['published_date'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image = null;

        if ($title === '' || $author === '' || $published_date === '' || $content === '') {
            $this->msg = "All fields except image are required.";
            $this->msg_type = "danger";
            return;
        }

        $image_updated = false;
        if (!empty($_FILES['image']['name'])) {
            $image_updated = $this->validateImage($_FILES['image'], $image);
            if ($image_updated) {
                $get_img_stmt = $this->conn->prepare("SELECT image FROM blogs_management WHERE id = ?");
                if ($get_img_stmt) {
                    $get_img_stmt->bind_param('i', $edit_id);
                    $get_img_stmt->execute();
                    $get_img_stmt->bind_result($del_img);
                    if ($get_img_stmt->fetch() && !empty($del_img)) {
                        $img_path = __DIR__ . '/../Frontend/' . $del_img;
                        if (is_file($img_path)) {
                            @unlink($img_path);
                        }
                    }
                    $get_img_stmt->close();
                }
            }
        }

        if ($this->msg === '') {
            if ($image_updated) {
                $stmt = $this->conn->prepare(
                    "UPDATE blogs_management SET title=?, author=?, published_date=?, content=?, image=? WHERE id=?"
                );
                $stmt->bind_param('sssssi', $title, $author, $published_date, $content, $image, $edit_id);
            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE blogs_management SET title=?, author=?, published_date=?, content=? WHERE id=?"
                );
                $stmt->bind_param('ssssi', $title, $author, $published_date, $content, $edit_id);
            }
            if ($stmt->execute()) {
                $this->msg = "Blog updated successfully!";
                $this->msg_type = 'success';
            } else {
                $this->msg = "Failed to update blog.";
                $this->msg_type = 'danger';
            }
            $stmt->close();
        }
    }

    public function fetchBlogs() {
        $blogs = [];
        $result = $this->conn->query("SELECT * FROM blogs_management ORDER BY published_date DESC, id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $blogs[] = $row;
            }
            $result->free();
        }
        return $blogs;
    }

    public function getBlog($edit_id) {
        $stmt = $this->conn->prepare("SELECT * FROM blogs_management WHERE id = ?");
        $edit_row = null;
        if ($stmt) {
            $stmt->bind_param('i', $edit_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $edit_row = $result->fetch_assoc();
            $stmt->close();
        }
        return $edit_row;
    }
}

$blogManager = new BlogManager($conn);
$blogManager->handlePost();
$blogs = $blogManager->fetchBlogs();

$edit_row = null;
if (isset($_GET['edit_id'])) {
    $edit_row = $blogManager->getBlog(intval($_GET['edit_id']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Electronica</title>
    <link href="../Frontend/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Frontend/css/global.css" rel="stylesheet">
    <link href="../Frontend/css/index.css" rel="stylesheet">
    <link href="../Frontend/css/adminpanel.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Frontend/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
    <style>
        .blog-image-thumb {
            max-width: 90px; max-height: 60px;
            border-radius:4px; object-fit:cover;
        }
        .form-group input[type="file"] {
            padding: 6px 0;
        }
        .edit-modal label { font-weight:bold; }
        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.87em;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="padding-left:0; padding-right:0;">
    <div class="row no-gutters">
        <nav class="col-md-2 d-none d-md-block admin-sidebar">
            <div class="position-sticky" style="padding-top:44px; min-height:90vh;">
                <div style="font-size:1.23rem; text-align:center; margin-bottom:50px; letter-spacing:2px;">
                    <span style="font-weight:bold; color:#fff;">ADMIN PANEL</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-dashboard" style="margin-right:12px;"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact_us.php">
                            <i class="fa fa-pencil-square-o" style="margin-right:12px;"></i> Contact Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="blog_management.php">
                            <i class="fa fa-users" style="margin-right:12px;"></i> Blogs Management
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-10 ml-sm-auto px-0">
            <div class="admin-header">
                <div class="admin-logo">
                    <i class="fa fa-bolt" style="margin-right:10px; color: #3c59e7;"></i>Electronica <span style="font-size:1.07rem; color:#3c59e7; font-weight:normal;">Admin Panel</span>
                </div>
                <div>
                    <span class="admin-user-badge">
                        <i class="fa fa-user-circle-o" style="font-size:1.28rem; margin-right: 10px; color:#3c59e7;"></i>
                        <?php echo htmlspecialchars($user_email); ?>
                    </span>
                    <a href="logout.php">
                        <button class="admin-logout-btn"><i class="fa fa-sign-out"></i> Logout</button>
                    </a>
                </div>
            </div>
            <div class="admin-content">
                <div class="admin-panel-title"><i class="fa fa-users"></i> Blogs Management</div>
                <hr>
                <?php if ($blogManager->msg): ?>
                    <div id="msg-block" class="alert alert-<?php echo $blogManager->msg_type; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($blogManager->msg); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card mb-5" id="add-blog-section">
                    <div class="card-header" style="font-weight:bold;">
                       <h5> Add New Blog </h5>
                    </div> <br>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" autocomplete="off" id="add-blog-form">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Blog Title<span style="color:#e74c3c;">*</span></label>
                                    <input type="text" name="title" class="form-control" required maxlength="255">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Author<span style="color:#e74c3c;">*</span></label>
                                    <input type="text" name="author" class="form-control" required maxlength="100">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Published Date<span style="color:#e74c3c;">*</span></label>
                                    <input type="date" name="published_date" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Image (optional)</label>
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" class="form-control-file">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Content<span style="color:#e74c3c;">*</span></label>
                                    <textarea name="content" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                            <button type="submit" name="add_blog" class="btn btn-success"><i class="fa fa-plus"></i> Add Blog</button>
                            <br> <br>
                        </form>
                    </div>
                </div>

                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-hover table-sm bg-light">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Blog Title</th>
                                <th>Author</th>
                                <th>Published Date</th>
                                <th>Image</th>
                                <th>Content</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($blogs) > 0): ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blog['id']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['title']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['author']); ?></td>
                                    <td><?php echo htmlspecialchars($blog['published_date']); ?></td>
                                    <td>
                                        <?php if (!empty($blog['image'])): ?>
                                            <img src="../Frontend/<?php echo htmlspecialchars($blog['image']); ?>" class="blog-image-thumb" alt="Blog Img">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="max-width:240px; max-height:85px; overflow:auto;">
                                            <?php echo nl2br(htmlspecialchars(mb_strimwidth($blog['content'], 0, 100, "..."))); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            data-toggle="modal"
                                            data-target="#editModal<?php echo $blog['id']; ?>">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $blog['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog post?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        
                                        <div class="modal fade edit-modal" id="editModal<?php echo $blog['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $blog['id']; ?>" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                              <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="editModalLabel<?php echo $blog['id']; ?>">Edit Blog #<?php echo $blog['id']; ?></h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="edit_id" value="<?php echo $blog['id']; ?>">
                                                  <div class="form-row">
                                                      <div class="form-group col-md-6">
                                                          <label>Blog Title<span style="color:#e74c3c;">*</span></label>
                                                          <input type="text" name="title" class="form-control" required maxlength="255" value="<?php echo htmlspecialchars($blog['title']); ?>">
                                                      </div>
                                                      <div class="form-group col-md-6">
                                                          <label>Author<span style="color:#e74c3c;">*</span></label>
                                                          <input type="text" name="author" class="form-control" required maxlength="100" value="<?php echo htmlspecialchars($blog['author']); ?>">
                                                      </div>
                                                      <div class="form-group col-md-6">
                                                          <label>Published Date<span style="color:#e74c3c;">*</span></label>
                                                          <input type="date" name="published_date" class="form-control" required value="<?php echo htmlspecialchars($blog['published_date']); ?>">
                                                      </div>
                                                      <div class="form-group col-md-6">
                                                          <label>Image (leave blank to keep current)</label><br>
                                                          <?php if (!empty($blog['image'])): ?>
                                                              <img src="../Frontend/<?php echo htmlspecialchars($blog['image']); ?>" class="blog-image-thumb mb-2" alt="Current Img"><br>
                                                          <?php endif; ?>
                                                          <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" class="form-control-file">
                                                      </div>
                                                      <div class="form-group col-md-12">
                                                          <label>Content<span style="color:#e74c3c;">*</span></label>
                                                          <textarea name="content" class="form-control" rows="4" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
                                                      </div>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><div class="alert alert-info mb-0">No blogs found.</div></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="../Frontend/js/jquery-2.1.1.min.js"></script>
<script src="../Frontend/js/bootstrap.min.js"></script>
<script>
    $(function() {
        <?php if (isset($blogManager) && $blogManager->msg && ($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['add_blog'])): ?>
            $('html, body').animate({
                scrollTop: $('#add-blog-section').offset().top - 100
            }, 600, function() {
                $('#add-blog-form input[name=title]').focus();
            });
        <?php endif; ?>
        $('#msg-block.alert-success').delay(1750).slideUp(200);
    });
</script>

<style>
.admin-sidebar {
    min-height: 100vh;
    background: #222859;
    padding-left: 0;
    padding-right: 0;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    width: 240px;
    z-index: 100;
    height: 100%;
    box-shadow: 2px 0 8px rgba(0,0,0,0.04);
}
@media (max-width: 991.98px) {
    .admin-sidebar {
        position: relative;
        width: 100%;
        min-height: unset;
        height: auto;
        box-shadow: none;
    }
}
main.col-md-10 {
    margin-left: 240px !important;
    width: calc(100% - 240px);
    transition: margin-left 0.3s;
}
@media (max-width: 991.98px) {
    main.col-md-10 {
        margin-left: 0 !important;
        width: 100%;
    }
}
body, html {
    overflow-x: auto;
}
</style>

</body>
</html>

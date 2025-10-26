<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_email'])) {
    header("Location: /../MGP_Blog_Project/Frontend/login.php");
    exit();
}
$user_email = $_SESSION['user_email'];
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
                        <a class="nav-link active" href="#">
                            <i class="fa fa-dashboard" style="margin-right:12px;"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact_us.php">
                            <i class="fa fa-pencil-square-o" style="margin-right:12px;"></i> Contact Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog_management.php">
                            <i class="fa fa-users" style="margin-right:12px;"></i> Blog Management
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Main Content Area -->
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
                <div class="admin-panel-title"><i class="fa fa-dashboard"></i> Dashboard</div>
                <hr>
                <div class="row">
                    <?php
                        require_once __DIR__ . '/Database/connection.php';
                        $msg_count = 0;
                        $sql = "SELECT COUNT(*) as total FROM contacts";
                        if ($result = $conn->query(query: $sql)) {
                            if ($row = $result->fetch_assoc()) {
                                $msg_count = $row['total'];
                            }
                            $result->free();
                        }
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; box-shadow: 0 4px 16px #e2e7fa;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #3c59e7;"><i class="fa fa-pencil"></i> Contact Us Messages</h5>
                                <h2 style="font-weight:bold;"><?php echo htmlspecialchars($msg_count); ?></h2>
                                <p class="card-text" style="color: #666;">Total Contact Us Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; box-shadow: 0 4px 16px #e2e7fa;">
                            <div class="card-body">
                            <?php
                                require_once __DIR__ . '/Database/connection.php';
                                $msg_counts = 0;
                                $sql = "SELECT COUNT(*) as total FROM blogs_management";
                                if ($result = $conn->query(query: $sql)) {
                                    if ($row = $result->fetch_assoc()) {
                                        $msg_counts = $row['total'];
                                    }
                                    $result->free();
                                }
                            ?>
                                <h5 class="card-title" style="color: #3c59e7;"><i class="fa fa-users"></i> Blogs</h5>
                                <h2 style="font-weight:bold;"><?php echo htmlspecialchars($msg_counts); ?></h2></h2>
                                <p class="card-text" style="color: #666;">Total Blogs Uploaded</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>

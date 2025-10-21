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
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block admin-sidebar">
            <div class="position-sticky" style="padding-top:44px; min-height:90vh;">
                <div style="font-size:1.23rem; text-align:center; margin-bottom:50px; letter-spacing:2px;">
                    <span style="font-weight:bold; color:#fff;">ADMIN</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fa fa-dashboard" style="margin-right:12px;"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-pencil-square-o" style="margin-right:12px;"></i> Manage Posts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-users" style="margin-right:12px;"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-cogs" style="margin-right:12px;"></i> Settings
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
                <div class="row">
                    <!-- Quick Stats Cards -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; box-shadow: 0 4px 16px #e2e7fa;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #3c59e7;"><i class="fa fa-pencil"></i> Posts</h5>
                                <h2 style="font-weight:bold;">12</h2>
                                <p class="card-text" style="color: #666;">Total posts in the system</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; box-shadow: 0 4px 16px #e2e7fa;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #3c59e7;"><i class="fa fa-users"></i> Users</h5>
                                <h2 style="font-weight:bold;">4</h2>
                                <p class="card-text" style="color: #666;">Admins and content editors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border: none; box-shadow: 0 4px 16px #e2e7fa;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #3c59e7;"><i class="fa fa-comments"></i> Comments</h5>
                                <h2 style="font-weight:bold;">153</h2>
                                <p class="card-text" style="color: #666;">New comments this month</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Quick Stats -->
                <div style="margin-top:44px;">
                    <h4 style="color:#222859; font-weight:600;"><i class="fa fa-bolt"></i> Quick Actions</h4>
                    <div class="row">
                        <div class="col-md-3 col-6" style="padding: 18px 10px;">
                            <a href="#" class="btn btn-outline-primary btn-block" style="width:100%; font-weight:600;">
                                <i class="fa fa-plus"></i> New Post
                            </a>
                        </div>
                        <div class="col-md-3 col-6" style="padding: 18px 10px;">
                            <a href="#" class="btn btn-outline-success btn-block" style="width:100%; font-weight:600;">
                                <i class="fa fa-user-plus"></i> Add User
                            </a>
                        </div>
                        <div class="col-md-3 col-6" style="padding: 18px 10px;">
                            <a href="#" class="btn btn-outline-warning btn-block" style="width:100%; font-weight:600;">
                                <i class="fa fa-cogs"></i> Settings
                            </a>
                        </div>
                        <div class="col-md-3 col-6" style="padding: 18px 10px;">
                            <a href="#" class="btn btn-outline-info btn-block" style="width:100%; font-weight:600;">
                                <i class="fa fa-eye"></i> View Site
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Maybe place more admin widgets here -->
            </div>
        </main>
    </div>
</div>
</body>
</html>

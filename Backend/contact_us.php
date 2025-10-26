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
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-dashboard" style="margin-right:12px;"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact_us.php">
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
                <div class="admin-panel-title"><i class="fa fa-pencil"></i> Customer Contact Details Messages</div>
                <hr>
                <?php
                    require_once __DIR__ . '/Database/connection.php';

                    $records_per_page = 5;
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($page < 1) $page = 1;
                    $offset = ($page - 1) * $records_per_page;

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
                        $delete_id = intval($_POST['delete_id']);
                        $delete_sql = "DELETE FROM contacts WHERE id = ?";
                        $stmt = $conn->prepare($delete_sql);
                        if ($stmt) {
                            $stmt->bind_param('i', $delete_id);
                            $stmt->execute();
                            $stmt->close();
                        }
                        header("Location: ".$_SERVER['PHP_SELF']."?page=".$page);
                        exit();
                    }

                    $count_sql = "SELECT COUNT(*) AS total FROM contacts";
                    $count_result = $conn->query($count_sql);
                    $total_records = 0;
                    if ($count_result && $row = $count_result->fetch_assoc()) {
                        $total_records = (int)$row['total'];
                    }
                    $count_result->free();

                    $total_pages = ceil($total_records / $records_per_page);

                    $sql = "SELECT * FROM contacts ORDER BY created_at ASC LIMIT $records_per_page OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-bordered table-hover">';
                        echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Name</th>';
                        echo '<th>Phone No</th>';
                        echo '<th>Email</th>';
                        echo '<th>Message</th>';
                        echo '<th>Date</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</thead><tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . nl2br(htmlspecialchars($row['comment'])) . '</td>';
                            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                            // Note: The input name and POST key is 'delete_id' (not 'id'), and this matches with our POST handler above.
                            echo '<td>
                                    <form method="POST" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?page=' . $page . '" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="' . htmlspecialchars($row['id']) . '">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this message?\')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                  </td>';
                            echo '</tr>';
                        }

                        echo '</tbody></table></div>';
                        $result->free();

                        if ($total_pages > 1) {
                            echo '<nav><ul class="pagination justify-content-center">';

                            if ($page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page='.($page - 1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                            } else {
                                echo '<li class="page-item disabled"><span class="page-link"><span aria-hidden="true">&laquo;</span></span></li>';
                            }

                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $page) {
                                    echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
                                }
                            }

                            if ($page < $total_pages) {
                                echo '<li class="page-item"><a class="page-link" href="?page='.($page + 1).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                            } else {
                                echo '<li class="page-item disabled"><span class="page-link"><span aria-hidden="true">&raquo;</span></span></li>';
                            }
                            echo '</ul></nav>';
                        }

                    } else {
                        echo '<div class="alert alert-info" role="alert">No contact messages found.</div>';
                    }
                ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>

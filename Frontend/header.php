<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_logged_in = isset($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electronica - Electrical Services</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  
  <body>

    <section id="top">
      <div class="container">
        <div class="row">
          <div class="top_1 clearfix">
            <div class="col-sm-4">
              <div class="top_1l clearfix">
                <p class="mgt col"><i class="fa fa-map-marker col_1"></i> 2583 Signal Hill Road Manassas, VA, 10230</p>
                <p class="col"><i class="fa fa-clock-o col_1"></i> Mon-Fri 09:00 AM - 06:00 PM</p>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="top_1m text-center clearfix">
                <h1 class="mgt"><a href="index.php">Electronica <span>Lorem Ipsum Dolor</span></a></h1>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="top_1r text-center clearfix">
                <h3 class="mgt"><a href="#"><i class="fa fa-phone col_1"></i> 1 (001) 234-56-78 <span>Call us now. Resistance is futile!</span></a></h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="main clearfix">
      <section id="header" class="clearfix">
        <div class="container">
          <div class="row">
            <div class="haeder clearfix">
              <nav class="navbar">
                <!-- <div class="navbar-header">
                  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="index.php">Electronica</a>
                </div> -->

                <div class="collapse navbar-collapse custom-navbar-collapse" style="box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important;">
                  <ul class="nav navbar-nav custom-navbar-nav">
                    <li><a class="font_tag active_tab custom-nav-link" href="index.php">Home</a></li>
                    <li><a class="font_tag custom-nav-link" href="shop.php">Product</a></li>
                    <li><a class="font_tag custom-nav-link" href="detail.php">Detail</a></li>
                    <li><a class="font_tag custom-nav-link" href="blog.php">Blog</a></li>
                    <li><a class="font_tag custom-nav-link" href="service.php">Services</a></li>
                    <li><a class="font_tag custom-nav-link" href="contact.php">Contact Us</a></li>
                    <?php if (!$user_logged_in): ?>
                      <li><a class="font_tag border_none custom-nav-link" href="login.php">Login</a></li>
                    <?php else: ?>
                      <li><a class="font_tag border_none custom-nav-link" href="/../MGP_Blog_Project/Backend/logout.php">Logout</a></li>
                    <?php endif; ?>
                  </ul>
                </div><!-- /.custom-navbar-collapse -->
              </nav>
            </div>
          </div>
        </div>
      </section>
    </div>


    </body>
    </html>
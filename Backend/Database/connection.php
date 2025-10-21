<?php
$host = "localhost";    
$user = "root";       
$pass = "";              
$dbname = "mgp_blog_project";   

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

else{
   // echo "Database connected successfully " . $dbname;
}

?>

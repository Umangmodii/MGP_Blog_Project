
<?php
require '../Backend/PHPMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../Backend/Database/connection.php';

$contact_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($name === '' || $email === '' || $comment === '') {
        $contact_message = "<div style='color:red;'>Please fill in all required fields.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, phone, email, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $comment);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = 'umangmodi003@gmail.com'; // FROM 
                $mail->Password   = 'uopekabpdrbuovwn'; // Token SMTP 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Recipients
                $mail->setFrom($mail->Username, 'Electronics Contact Form');
                $mail->addAddress($email, $name);
                $mail->addReplyTo($mail->Username, 'Electronica Contact Form');

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Thank you for contacting us!";
                $mail->Body    = "
                    <h2>Thank you for reaching out, {$name}!</h2>
                    <p>We have received your message. Here are the details you submitted:</p>
                    <p><b>Name:</b> {$name}</p>
                    <p><b>Phone:</b> " . ($phone ?: 'N/A') . "</p>
                    <p><b>Email:</b> {$email}</p>
                    <p><b>Comment:</b><br>" . nl2br(htmlspecialchars($comment)) . "</p>
                    <hr>
                    <small>Submission Time: " . date('Y-m-d H:i:s') . "</small>
                ";
                $mail->AltBody = "Name: $name\nPhone: " . ($phone ?: 'N/A') . "\nEmail: $email\nComment: $comment";

                $mail->send();

                $contact_message = "<div style='color:green;'>Your contact message was sent successfully. Confirmation email sent and saved to database.</div>";
            } catch (Exception $e) {
                $contact_message = "<div style='color:orange;'>Data saved, but email sending failed. Error: {$mail->ErrorInfo}</div>";
            }
        } else {
            $contact_message = "<div style='color:red;'>Database Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electronica</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<link href="css/contact.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
	<script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  
<body>

<?php
	include 'header.php';
?>
	
<section id="center" class="clearfix center_contact">
 <div class="container">
  <div class="row">
     <div class="contact_1 clearfix">
		 <div class="col-sm-12">
		  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114964.53925916665!2d-80.29949920266738!3d25.782390733064336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b0a20ec8c111%3A0xff96f271ddad4f65!2sMiami%2C+FL%2C+USA!5e0!3m2!1sen!2sin!4v1530774403788" width="100%" height="450px" frameborder="0" style="border:0" allowfullscreen=""></iframe>
		 </div>
	 </div>
	 <div class="contact_2 clearfix">
		<div class="col-sm-9">
		 <div class="contact_2l clearfix">
		  <h6>Home / Contact</h6>

		  <?php
		      if (!empty($contact_message)) {
		          echo $contact_message;
		      }
		  ?>

		  <h3 class="col_1">CONTACT</h3>
		  <p>Comodous et accusamus et iusto odios un dignissimos ducimus qui blan ditiis prasixer esentium voluptatum un deleniti atqueste sites excep turiitate non providentsimils. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, consequunturser magni halothemes - premium shopify templates & themes sequi nesciunt lorem ipsum dolor sit amet isse potenti. Vesquam ante aliquet lacusemper loremous.</p>
		  
		  <form method="post" action="contact.php" id="contactForm">
		    <div class="form-group">
		      <label for="name" class="bold">Your Name</label>
		      <input class="form-control" type="text" name="name" id="name">
		    </div>
		    <div class="form-group">
		      <label for="phone" class="bold">Your Phone</label>
		      <input class="form-control" type="text" name="phone" id="phone">
		    </div>
		    <div class="form-group">
		      <label for="email" class="bold">Your Email <span class="col_2">*</span></label>
		      <input class="form-control" type="email" name="email" id="email" required>
		    </div>
		    <div class="form-group">
		      <label for="comment" class="bold">Your Comment <span class="col_2">*</span></label>
		      <textarea class="form-control form_1" name="comment" id="comment" required></textarea>
		    </div>
			<button type="submit" class="button" id="submit-contact" style="margin-top:20px;">SUBMIT CONTACT</button>
		  </form>

		 </div>
		</div>

		<div class="col-sm-3">
		 <div class="contact_2r clearfix">
		  <h4 class="col_1">CONTACT INFO</h4>
		  <p>We'd love to hear from you - please use the form to send us your message or ideas. Or simply pop in for a cup of fresh tea and a cookie:</p>
		  <p>1234 Ave Dermentum, Onsectetur Adipiscing
Tortor Sagittis, CA 123456,
United States</p>
           <p>Email: info@gmail.com<br>
Toll-free: (1234) 567 89XY</p>
           <hr>
		   <p>Opening Hours:<br>
Monday to Saturday: 9am - 10pm<br>
Sundays: 10am - 6pm</p>
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


<section id="footer">
 <div class="container">
  <div class="row">
   <div class="footer_2 clearfix">
    <div class="col-sm-3">
	 <div class="footer_2i clearfix">
	    <a class="navbar-brand" href="index.php"> Electronica </a>
	    <p class="col">Choosing the right hospital and physician are important factors to consider that significantly influence a patient’s treatment. The preferred choice for many patients is choosing private care.</p>
	 </div>
	</div>
	<div class="col-sm-3">
	 <div class="footer_2i1 clearfix">
	  <h4 class="mgt col">Departments</h4>
	  <hr>
	  <ul>
	   <li><a class="col" href="#">Neurology</a></li>
	   <li><a class="col" href="#">Traumotology</a></li>
	   <li><a class="col" href="#">Gynaecology</a></li>
	   <li><a class="col" href="#">Nephrology</a></li>
	   <li><a class="col" href="#">Cardiology</a></li>
	   <li><a class="col" href="#">Pulmonary</a></li>
	  </ul>
	 </div>
	</div>
	<div class="col-sm-3">
	 <div class="footer_2i1 clearfix">
	  <h4 class="mgt col">Head Office</h4>
	  <hr>
	  <p class="col">1096 N Highland St, Arlington
VA 130xz, USA<br>
info@gmail.com<br>
123 4567 89 01</p>
     <p class="col">Mon-Thu: 9:30 – 21:00<br>
Fri: 6:00 – 21:00<br>
Sat: 10:00 – 15:00</p>
	 </div>
	</div>
	<div class="col-sm-3">
	 <div class="footer_2i1 clearfix">
	  <h4 class="mgt col">Quick Links</h4>
	  <hr>
	  <ul>
	   <li><a class="col" href="#">Gallery</a></li>
	   <li><a class="col" href="#">FAQs</a></li>
	   <li><a class="col" href="#">Contacts</a></li>
	  </ul>
	 </div>
	</div>
   </div>
   <div class="footer_3 clearfix">
	 <div class="col-sm-7">
	  <div class="footer_3l clearfix">
       <p class="mgt col">© 2013 Your Website Name. All Rights Reserved | Design by <a class="col" href="http://www.templateonweb.com">TemplateOnWeb</a></p>
	  </div>
	 </div>
	 <div class="col-sm-5">
	  <div class="footer_3r clearfix">
       <ul class="mgt">
	    <li><a href="#">Home</a></li>
		<li><a href="#">What we do</a></li>
		<li><a href="#">About</a></li>
		<li><a href="#">FAQ</a></li>
		<li><a href="#">Team</a></li>
		<li><a href="#">News</a></li>
		<li><a href="#">Contacts</a></li>
	   </ul>
	  </div>
	 </div>
	</div>
  </div>
 </div>
</section>

<script>
var myIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("mySlides");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  setTimeout(carousel, 2500);    
}
</script>
</body>
 
</html>

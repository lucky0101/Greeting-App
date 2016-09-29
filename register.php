<?php
//for stop someone to try register after login
// i know its weird  but many half mind do that

ob_start();

// This file is www.rocksdesign.in curriculum material
session_start();

if (isset($_SESSION["useremail"])) {
    header('location: user_dashboard.php'); 
    exit();
}
?>
<?php
// register script start here

$errorMsg = "";
$name = "";
$email = "";
$password = "";

if(isset($_POST['name'])){
	 
	 // Connect to database
     include_once "scripts/connect_to_mysql.php";
	 
	 //filter data
	 
	 $name = mysqli_real_escape_string($conn, $_POST['name']);
	 $email = mysqli_real_escape_string($conn, $_POST['email']);
	 $password = mysqli_real_escape_string($conn, $_POST['password']);
	 
	 $name = preg_replace('#[^a-z ]#i', '', $name);
	 $email = preg_replace('#[^a-z0-9@._-]#i', '', $email);
	 $joinday_ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	 
	 $name = stripslashes($name);
     $email = stripslashes($email);
	 $password = stripslashes($password);
	 $joinday_ip = stripslashes($joinday_ip);
	 
	 $name = strip_tags($name);
     $email = strip_tags($email);
	 $password = strip_tags($password);
     $joinday_ip = strip_tags($joinday_ip);
	 
	 $name = htmlspecialchars($name);
     $email = htmlspecialchars($email);
	 $password = htmlspecialchars($password);
     $joinday_ip = htmlspecialchars($joinday_ip);
	 
	 $name = trim($name);
     $email = trim($email);
	 $password = trim($password);
     $joinday_ip = trim($joinday_ip);
	 
	 //valid email regex check for email format
	 
	 $regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
	 
	 // check duplication of an email address
	 $emailCHecker = $email;
	 
	 $sql_email_check = mysqli_query($conn, "SELECT myemail FROM greetuser WHERE myemail='$emailCHecker'");
	 $email_check = mysqli_num_rows($sql_email_check);
	 
	 // Error handling for missing data
     if ((!$name) || (!$email) || (!$password)){
		 
		 if(!$name){ 
         $errorMsg = '<div class="red-mark"><i class="fa fa-user"></i> * Please Enter Your Full Name </div>';
		 
		 }else if(!$email){ 
         $errorMsg = '<div class="red-mark"><i class="fa fa-envelope"></i> * Please Enter Your Vaild Email </div>';
		 
		 }else if(!$password){ 
         $errorMsg = '<div class="red-mark"><i class="fa fa-unlock-alt"></i> * Please Enter A Password For Your Profile </div>';
         
		 }else{
		 $errorMsg = '';
		 
		 } //input checking end here
	 
	 }else if ($email_check > 0){ 
              $errorMsg .= '<div class="orange-mark"><i class="fa fa-envelope"></i>  <u>ERROR :</u><br />Your Email address is already in use inside our database. Please use another. </div>'; 
	   
     }else if (!preg_match($regex,$email)) { 
	         $errorMsg .= '<div class="orange-mark"><i class="fa fa-envelope"></i> Its Email is not valid Please Enter a valid Email Address</div>';
	 
	 }else if (strlen($password) < 4 || strlen($password) > 20) {
	          $errorMsg .= '<div class="orange-mark"><i class="fa fa-unlock-alt"></i> Password has at least 4 - 20 characters long </div>';
     
	 }else { // Error handling is ended, process the data and add member to database
	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	 $seq_hash = md5('789'.$email);
	 $db_password = md5('123'.$seq_hash.''.$password.'');
	 
	 // Add user info into the database table
	 
     $sql = mysqli_query($conn, "INSERT INTO greetuser (fullname, myemail, mypass, joinday_ip, lastlogday_ip, joinday_date, lastlogday_date)
	 
     VALUES('$name', '$email', '$db_password', '$joinday_ip', '$joinday_ip',  now(), now())");
	 
	 // extract last insert id 
	 $id = mysqli_insert_id($conn);
	 
	 // Create directory(folder) to hold each user's files(pics, MP3s, etc.)		
     mkdir("user_data/$id", 0755);
	 
	 
	  // check user info from database for sequrity
	 
	  $sql = mysqli_query($conn, "SELECT * FROM greetuser WHERE id='$id' AND myemail='$email' AND mypass='$db_password' LIMIT 1"); 
	
	 $login_check = mysqli_num_rows($sql);
    
	 if($login_check > 0){ 
		
		while($row = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
	 
			//create session from posted data for user
			
			$id = $row["id"];
			$_SESSION['id'] = $id;
			
			$useremail = $row["myemail"];
			$_SESSION['useremail'] = $email;
					
			$userpass = $row["mypass"];
			$_SESSION['userpass'] = $userpass;
		}
	 
	 header("location: user_dashboard.php"); 
     exit();
	 
	 }
	 }
	 

}else { // if the form is not posted with variables, place default empty variables
	  
	  $errorMsg = "";
	  $name = "";
	  $email = "";
	  $password = "";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
	<title>Greeting App - Register</title>
	
    <!-- Bootstrap  
	================================================== -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	
	<!-- Custom Stylesheet 
	================================================== -->
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<!-- Font Awesome 
	================================================== -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	
	<!-- Fonts  
	================================================== -->
	
	<!--Amatic SC -->
	<link href='https://fonts.googleapis.com/css?family=Amatic+SC:400,700' rel='stylesheet' type='text/css'>
	
	
	<!--open sans -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' />
		
	<!-- Roboto -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css' />
	
	<!-- Caveat -->
	<link href='https://fonts.googleapis.com/css?family=Caveat:400,700' rel='stylesheet type='text/css' />
		
	
	<!-- Bootstrap cnd for internet explorer  
	================================================== -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
	<!-- Page Wrapper
	++++++++++++++++++++++++++++++++++++++++++++ -->
		
		<div class="wrapper">
		
			<!-- Page header
			========================================== -->
				
			<div class="page-header">
				
			<div class="container-fluid">
			<div class="row padding-5">
				
			<div class="col-md-12" align="center">
				
			<h1 class="amatic-sc margin-0 margin-top-20 font-size-60">Greeting App</h1>
			<p class="roboto font-size-16 margin-top-20"><i class="fa fa-pencil"></i> Make An Account</p>
				
			</div><!-- /column end -->
				
				
			</div><!-- /row end -->
			</div><!-- /container end -->
				
			</div><!-- end page-header->
				
			<!-- /Page header
			========================================== -->
			
			<!-- Page content
			========================================== -->
			
			<div class="container-fluid">
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div>
			<div class="col-md-4">
			
			<div class="error-display"><?php print "$errorMsg"; ?></div>
			
			<form id="frm_request"  action="register.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="Name">Your Name</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-user"></span></div>
			<input type="text" class="form-control" name="name" id="name" placeholder="Jane Doe" value="<?php echo "$name"; ?>"/>
			</div>
			</div>
			
			<div class="form-group">
			<label for="email">Email address</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-envelope"></span></div>
			<input type="email" class="form-control" name="email" id="email" placeholder="john@gmail.com" value="<?php echo "$email"; ?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="password">Password</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-unlock-alt"></span></div>
			<input type="password" class="form-control" name="password" id="password" placeholder="Choose A Password" value="<?php echo "$password"; ?>" />
			</div>
			</div>
			
			<div align="center">
			<button type="submit" class="btn btn-success" id="FormSubmit"><i class="fa fa-check"> </i> Register</button>
			
			<button type="reset" class="btn btn-info margin-left-5" id="FormSubmit"><i class="fa fa-repeat"> </i> Reset</button>
			</div>
			
			</form>
			
			
			</div><!-- /column end -->
			<div class="col-md-4"></div>
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12" align="center">
			
			<p> <a href="login.php" class="open-sans"> <i class="fa fa-registered"></i> I Allready have an Account</a></p>
			<br />
			<p> <a href="index.php" class="open-sans"> <i class="fa fa-arrow-circle-left"></i> Back</a></p>
			</div>
			
			</div><!-- /row end -->
			
			
			</div><!-- /container end -->
			
			<!-- /Page content
			========================================== -->
		
		
		</div>
	
	
	<!-- /Page Wrapper
	++++++++++++++++++++++++++++++++++++++++++++ -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
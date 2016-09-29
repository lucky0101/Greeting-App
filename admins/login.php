<?php
//for stop someone to try login after login
// i know its weird  but many half mind do that

ob_start();

// This file is www.rocksdesign.in curriculum material
session_start();

if (isset($_SESSION["useremail"])) {
    header('location: index.php'); 
    exit();
}
?>
<?php 
// login script start here

$errorMsg = "";
$email = "";
$password = "";

if(isset($_POST['email'])){
	
	//connect to database
	
	require_once("../scripts/connect_to_mysql.php");
	
	//filter data
	
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	$email = preg_replace('#[^a-z0-9@._-]#i', '', $email);
	$logday_ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$email = stripslashes($email);
	$password = stripslashes($password);
	$logday_ip = stripslashes($logday_ip);
	
    $email = strip_tags($email);
	$password = strip_tags($password);
    $logday_ip = strip_tags($logday_ip);
	 
    $email = htmlspecialchars($email);
	$password = htmlspecialchars($password);
    $logday_ip = htmlspecialchars($logday_ip);
	 
    $email = trim($email);
	$password = trim($password);
    $logday_ip = trim($logday_ip);
	
	//valid email regex check for email format
	
	$regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
	
	// Error handling for missing data
    if ((!$email) || (!$password)){ 
  
	 if(!$email){ 
       $errorMsg = ' <div class="red-mark"><i class="fa fa-envelope"></i> * Please Enter Your Vaild Email </div>';
     
	 }else if(!$password){ 
       $errorMsg = ' <div class="red-mark"><i class="fa fa-unlock-alt"></i> * Please Enter Your Chosen Password For Your Profile </div>';
     
	 }else{
	   $errorMsg = '';
	 } // input checking end here
	
	}else if (!preg_match($regex,$email)) { 
	    
		$errorMsg .= '<div class="orange-mark"><i class="fa fa-envelope"></i> Its Email is not valid Please Enter a valid Email Address </div>';
	 
	 } else {// Error handling is ended, process the data and give login to member
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 
		 $seq_hash = md5('789'.$email);
		 $db_password = md5('123'.$seq_hash.''.$password.'');
		 
		 
		 $sql = mysqli_query($conn, "SELECT * FROM admins WHERE emailid='$email' AND db_password='$db_password' LIMIT 1"); 
		 $login_check = mysqli_num_rows($sql);
         if($login_check > 0){ 
    			while($row = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
					
					$id = $row["id"];   
					$_SESSION['id'] = $id;
					
					$useremail = $row["emailid"];
					$_SESSION['useremail'] = $useremail;
					
					$userpass = $row["db_password"];
					$_SESSION['userpass'] = $userpass;

					mysqli_query($conn, "UPDATE admins SET lastlogday_date=now() WHERE id='$id' LIMIT 1");
					mysqli_query($conn, "UPDATE admins SET lastlogday_ip='$logday_ip' WHERE id='$id' LIMIT 1");
        
    			} // close while
	
    			 
    			header("location: index.php"); 
    			exit();
	
		}else{
			$errorMsg = ' <div class="orange-mark"><i class="fa fa-remove"></i> Incorrect login data, please try again </div> ';
		}
		 
	 }
	 
}else { // if the form is not posted with variables, place default empty variables
	  
	  $errorMsg = "";
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
    
	<title>Greeting App - Login</title>
	
    <!-- Bootstrap  
	================================================== -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	
	<!-- Custom Stylesheet 
	================================================== -->
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
	
	<!-- Font Awesome 
	================================================== -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	
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
			<p class="roboto font-size-16 margin-top-20"><i class="fa fa-sign-in"></i> Enter In Your Account</p>
				
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
			
			<form id="frm_request"  action="login.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="email">Email address</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-envelope"></span></div>
			<input type="email" class="form-control" name="email" id="email" placeholder="john@gmail.com" value="<?php echo"$email"; ?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="password">Password</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-unlock-alt"></span></div>
			<input type="password" class="form-control" name="password" id="password" placeholder="Choose A Password" value="<?php echo"$password";?>" />
			</div>
			</div>
			
			<div align="center">
			<button type="submit" class="btn btn-success" id="FormSubmit"><i class="fa fa-check"> </i> Enter</button>
			
			<button type="submit" class="btn btn-info margin-left-5" id="FormSubmit"><i class="fa fa-repeat"> </i> Reset</button>
			</div>
			
			</form>
			
			
			</div><!-- /column end -->
			<div class="col-md-4"></div>
			
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
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
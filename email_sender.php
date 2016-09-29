<?php
//check user login
require_once("login_check.php");

//grab it session id for use
$uid = (int)$_SESSION['id'];

//if session id is not set
if($uid == ""){
	header("loction:login.php");
}

//sql query to extract user info

$userinfo = mysqli_query($conn,"SELECT id,fullname,myemail FROM greetuser WHERE id='$uid' LIMIT 1") or die(mysqli_error($conn)); // query the person

$personCount = mysqli_num_rows($userinfo);

if($personCount >0){
	
	while($row= mysqli_fetch_array($userinfo,MYSQLI_ASSOC)){
		
		$name = $row['fullname'];
		$sender_email = $row['myemail'];
		
		//breck name to get first name and last name
		$name_explode = explode(" ", $name);
		$firstname = $name_explode[0];
		$lastname = $name_explode[1];
		
	}
	
}

// extract data and start sending email

if(isset($_POST['final_email_list'])){
	
	$email_list_id = (int)$_POST['final_email_list'];
	
	$greeting_id = (int)$_SESSION['greet_id'];
	
	//sql query for extract user's email list email id's
	
	// confirm that email list exit for this user

	$Email_listinfo = mysqli_query($conn,"SELECT id FROM email_listname WHERE id='$email_list_id' AND user_id='$uid' LIMIT 1") or die(mysqli_error($conn));

	$email_list_Count = mysqli_num_rows($Email_listinfo);
	
	// in conditon if emai list does not contaion first name and lastname it should be blank
	
	$person_firstname = '';
	$person_lastname = '';
	
	if($email_list_Count > 0){
		
		while($row = mysqli_fetch_array($Email_listinfo,MYSQLI_ASSOC)){
			
			$list_id = $row['id'];
			
		}
		
		// now extract email id's and start sending mails
		
		$Email_listed_id = mysqli_query($conn,"SELECT id,firstname,lastname,emailid FROM email_list WHERE list_name_id='$email_list_id' ORDER BY id ASC") or die(mysqli_error($conn));
		
		$email_id_Count = mysqli_num_rows($Email_listed_id);
		
		if($email_id_Count > 0){
			
			while($row = mysqli_fetch_array($Email_listed_id,MYSQLI_ASSOC)){
				
				$receiver_id = $row['id'];
				$person_firstname = $row['firstname'];
				$person_lastname = $row['lastname'];
				$emailid = $row['emailid'];
				
				//configure geeting for each listed email id
				
				// get name of greeting and dipaly greeting image

				$Greet_image = mysqli_query($conn, "SELECT greeting_name,greeting_file FROM greeting_list WHERE id='$greeting_id' LIMIT 1");

				$CheckImage = mysqli_num_rows($Greet_image);

				if($CheckImage > 0){
	
					while($row = mysqli_fetch_array($Greet_image,MYSQLI_ASSOC)){
		
						$greeting_name = $row['greeting_name'];
						$greeting_file = $row['greeting_file'];
		
					}
	
					//get image dircotory for location
	
					$dir_name = str_replace(" ","_",$greeting_name);
					
					// get file name for location
					
					$kaboom = explode("/", $greeting_file);
					$file_name = end($kaboom);
					
					// file location
					
					if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
						
						$server_url = "https://$_SERVER[HTTP_HOST]";
					
					} else{
						
						$server_url = "http://$_SERVER[HTTP_HOST]";
					
					}
					
					// change it for server configure
	
					$final_greeing_file = 
					''.$server_url.'/user_data/'.$uid.'/'.$dir_name.'/images/'.$file_name.'';
					
					
					$html_email_body ='
	
						<!DOCTYPE html>
						<html>
						<head>
						<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
						</head>
						<body bgcolor="#ffffff" text="#000000">
							
							<h2 style="font-weight:400;"> Dear, '.$person_firstname.' '.$person_lastname.' </h2>
							
							<br /> <br />
							
							
							<div align="center" style="text-align:center;">
							
							<img src="'.$final_greeing_file.'" widht="100%" style="widht:100%;" alt="please enable image to see its greeting :)" />
							
							</div>
							
							<br /> <hr /> <br />
							
							<div> 
							
							<h3 style="font-weight:400;"> Regards, </h3>
							<h3 style="font-weight:400;"> '.$firstname.' '.$lastname.' </h3>
							
							</div>
							
						</body>
						</html>
						
					';
					
					$person_name = "$person_firstname $person_lastname";
	
					$html_email_body = trim($html_email_body);
					
					///////////////////////////////	 
					// sender script start here
					///////////////////////////////

					require_once('scripts/class.phpmailer.php');
					//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

					$mail = new PHPMailer();
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->Host       = "smtp.gmail.com"; // SMTP server
					//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
															   // 1 = errors and messages
															   // 2 = messages only
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
					$mail->Host       = "ssl://smtp.gmail.com"; // sets the SMTP server
					$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
					//$mail->SMTPSecure = 'ssl';               // use this if you want secure app
					
					$mail->Username   = "addyouremail@gmail.com"; // SMTP account username
					$mail->Password   = "addyourpassword";        // SMTP account password
					
					//$mail->Timeout = 3600;  
					

					$mail->SetFrom($sender_email, $name);
					$mail->AddReplyTo($sender_email, $name);
					$mail->Subject    = $greeting_name;
					$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
					$mail->MsgHTML($html_email_body);

					$address = $emailid;
					$mail->AddAddress($address, $person_name);

					$mail -> send();
					
					$sender_ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
					
					if(!$mail->Send()) {
						
					  $mail_error= "Mailer Error: " . $mail->ErrorInfo;
					  
					  $error_report = mysqli_query($conn,"INSERT INTO email_reporting (receiver_id, sender_id, email_list_id, geeting_id, status, report, ip, last_update) 
					  VALUES('$receiver_id', '$uid', '$email_list_id', '$greeting_id','ERROR', '$mail_error', '$sender_ip', now())") or die("error".mysqli_error($conn));
					  
					} else {
						
					   $sucess_report = mysqli_query($conn,"INSERT INTO email_reporting (receiver_id, sender_id, email_list_id, geeting_id, status, report, ip, last_update) 
					  VALUES('$receiver_id', '$uid', '$email_list_id', '$greeting_id','OK', 'SENT', '$sender_ip', now())")or die("success".mysqli_error($conn));
					}
					

					///////////////////////////////	 
					// sender script end here
					///////////////////////////////	 
					
	
				}else{
	
					header("location:user_dashboard.php");
	
				}
				
			}// end while
			
			header("location:user_dashboard.php"); // send them to dashboard after sending mail
			
		}else{
		
			header("location:user_dashboard.php");
		
		}
		
		
	}else{
		
		header("location:user_dashboard.php");
		
	}
	
	
}else{
	
	header("location:user_dashboard.php");
	
}



//sql query for count user's Greeting list

$Greeting_listinfo = mysqli_query($conn,"SELECT id FROM greeting_list WHERE user_id='$uid' ORDER BY ID ASC") or die(mysqli_error($conn)); // query the person

$greeting_list_Count = mysqli_num_rows($Greeting_listinfo);



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
	<title>Greeting App - <?php echo $name; ?></title>
	
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
				
			<div class="col-md-6 content-left">
				
			<h1 class="amatic-sc margin-0 margin-top-20 font-size-50">Greeting App</h1>
				
			</div><!-- /column end -->
				
			<div class="col-md-6 content-right">
			
			<a href="logout.php" class="btn btn-danger open-sans margin-0 margin-top-30 margin-left-5"> <i class="fa fa-sign-out"></i> Log Out</a>
				
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
			
			<div class="col-md-12" align="center">
			
			<h1 class="front-heading margin-0 margin-top-20">Hi, <?php echo "$firstname"; ?></h1>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
				
			<div class="col-md-12" align="center">
			
			<h1><i class="fa fa-send" style="color:#999;"></i></h1>
			<h3 class="open-sans" style="color:#999;"> WE ARE SENDING YOUR EMAILS,<br /> PLEASE WAIT FOR SOME MOVEMENT</h3>
			
			</div><!-- /column end -->
			
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

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

$userinfo = mysqli_query($conn,"SELECT id,fullname FROM admins WHERE id='$uid' LIMIT 1") or die(mysqli_error($conn)); // query the person

$personCount = mysqli_num_rows($userinfo);

if($personCount >0){
	
	while($row = mysqli_fetch_array($userinfo,MYSQLI_ASSOC)){
		
		$name = $row['fullname'];
		
		//breck name to get first name and last name
		$name_explode = explode(" ", $name);
		$firstname = $name_explode[0];
		$lastname = $name_explode[1];
		
	}
	
}

$user_listings = '';

//display font list program

$user_info = mysqli_query($conn,"SELECT * FROM greetuser ORDER BY id DESC") or die(mysqli_error($conn));

$checkList = mysqli_num_rows($user_info);

if($checkList > 0){
	
	while($row = mysqli_fetch_array($user_info,MYSQLI_ASSOC)){
		
		$users_id = $row['id'];
		$fullname = $row['fullname'];
		$myemail = $row['myemail'];
		$lastlogday_date = strftime( "%d %b, %Y", strtotime($row['lastlogday_date']));
		$lastlogday_time = strftime( "%I:%M:%S %p", strtotime($row['lastlogday_date']));
		
		
		// creating dynamic display for list
		
		$user_listings .= '
		
			<div class="col-md-4" align="center">
			
			<div class="listbox">
			
			<h3>'.$fullname.'</h3>
			
			<br />
			
			<span class="open-sans font-size-16 margin-bottom-0 display-block">'.$myemail.'</span>
			
			<br />
			
			<span class="open-sans font-size-16 margin-bottom-0 display-block">'.$lastlogday_date.' AT '.$lastlogday_time.'</span>
			
			<br />
			
			<a href="User_profile.php?user_id='.$users_id.'"><i class="fa fa-pencil-square-o"> </i> Visit Profile</a>
			
			<br />
			
			</div>
			
			</div><!-- /column end -->
					
		';
		
	}
	
} else{
	
	$user_listings .= '
		
		<div class="col-md-3"></div>
		
		<div class="col-md-6" align="center">
		
		<div class="error-display">
		
		<div class="orange-mark">
		
		<i class="fa fa-envelope"></i> No User Found in database at this time  
		
		</div>
		
		</div>
		
		</div>
		
		<div class="col-md-3"></div>
	
	';	
	
}



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
			
			<p class="open-sans"> Manage Users</p>
			
			<p class="open-sans"> 
			<a href="index.php"> <i class="fa fa-home"></i> HOME </a>
			</p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<?php echo "$user_listings"; ?>
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12" align="center">
			
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
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
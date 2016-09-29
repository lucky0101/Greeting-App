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
	
	while($row= mysqli_fetch_array($userinfo,MYSQLI_ASSOC)){
		
		$name = $row['fullname'];
		
		//breck name to get first name and last name
		$name_explode = explode(" ", $name);
		$firstname = $name_explode[0];
		$lastname = $name_explode[1];
		
	}
	
}

if(isset($_GET['list_id'])){
	
	$list_id = (int)$_GET['list_id'];
	
	// incase somebuddy try to play with link
	
	if($list_id =="" || $list_id == 0){
		
		header("location:index.php");
		
	}
	
	
	// Email List Info
	
	$Email_listinfo = mysqli_query($conn,"SELECT * FROM email_listname WHERE id='$list_id' ORDER BY ID DESC") or die(mysqli_error($conn)); 

	$Email_list_Count = mysqli_num_rows($Email_listinfo);
	
	$All_Email_list = '';
	
	if($Email_list_Count > 0){
		
		while($row = mysqli_fetch_array($Email_listinfo,MYSQLI_ASSOC)){
			
			$email_list_id = $row['id'];
			$email_list_name = $row['list_name'];
			$email_list_user_id = $row['user_id'];
			$post_date = strftime( "%d %b, %Y", strtotime($row['last_update']));
			$post_time = strftime( "%I:%M:%S %p", strtotime($row['last_update']));
			
			// get email list listed email
			
			$Email_listed = mysqli_query($conn,"SELECT * FROM email_list WHERE list_name_id='$email_list_id' ORDER BY id DESC") or die(mysqli_error($conn));

			$Count_listed_id = mysqli_num_rows($Email_listed);
			
			if($Count_listed_id > 0){
			
				while($row = mysqli_fetch_array($Email_listed,MYSQLI_ASSOC)){
				
					$listed_firstname = $row['firstname'];
					$listed_lastname = $row['lastname'];
					$listed_emailid = $row['emailid'];
				
					$All_Email_list .='
				
					<p class="open-sans">  '. $listed_firstname .' '. $listed_lastname .' </p>
					<p class="open-sans">  '. $listed_emailid .' </p>
					
					<hr />
				
					';
				
				}
				
			}else{
		
				$All_Email_list ='<p class="open-sans"> This Email list does not have any email id </p>';
		
			}
			
		}
		
	}else{
		
		$All_Email_list ='<p class="open-sans"> This Email list does not have any email id </p>';
		
	}
	
}else{
	
	header("location:index.php");
	
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
			
			<p class="open-sans"> View Email List </p>
			
			<p class="open-sans"> 
			
			<a href="index.php"> <i class="fa fa-home"></i> HOME </a> /
			
			<a href="User_profile.php?user_id=<?php echo $email_list_user_id;?>"> <i class="fa fa-arrow-circle-left"></i> BACK </a>
			
			</p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12">
			
			<h2 class="open-sans margin-0 margin-top-20" style="font-weight:300; text-transform: capitalize; ">
			
			<?php echo "$email_list_name";?> 
			
			<span class="font-size-16 margin-left-5"> 
			
			( <?php echo "$Count_listed_id";?> Email Id Listed )
			
			<br />
			
			 Create On <?php echo $post_date;?> @ <?php echo $post_time;?>
			</span>
			
			</h2>
			
			<hr />
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12">
			
			<?php echo "$All_Email_list";?>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			
			
			<div class="row padding-5">
			
			<div class="col-md-12" align="center">
			
			<p class="open-sans"> 
			<a href="index.php"> <i class="fa fa-home"></i> HOME </a> /
			<a href="User_profile.php?user_id=<?php echo $email_list_user_id;?>"> <i class="fa fa-arrow-circle-left"></i> BACK </a>
			</p>
			
			</div>
			
	
			
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
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

$userinfo = mysqli_query($conn,"SELECT id,fullname FROM greetuser WHERE id='$uid' LIMIT 1") or die(mysqli_error($conn)); // query the person

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

//sql query for count user's Greeting list

$Greeting_listinfo = mysqli_query($conn,"SELECT * FROM greeting_list WHERE user_id='$uid' ORDER BY ID ASC") or die(mysqli_error($conn)); // query the person

$greeting_list_Count = mysqli_num_rows($Greeting_listinfo);

$full_greeting_list ='';

$i = 1;

if($greeting_list_Count > 0){
	
	while($row = mysqli_fetch_array($Greeting_listinfo,MYSQLI_ASSOC)){
		
		$greet_id = $row['id'];
		$greeting_name = $row['greeting_name'];
		$greeting_file = $row['greeting_file'];
		$post_date = strftime( "%d %b, %Y", strtotime($row['last_update']));
		$post_time = strftime( "%I:%M:%S %p", strtotime($row['last_update']));
		
		// get real greeting file by refrence
		
		$kaboom = explode("/", $greeting_file);
		$greeting_file = end($kaboom);

		//prepare dircotory name
		
		$diroctory_name = str_replace(" ","_","$greeting_name");
		
		$greeting_file_location = 'user_data/'.$uid.'/'.$diroctory_name.'/images/'.$greeting_file.'';
		
		$full_greeting_list .='
		
			<div class="col-md-4">

			<div class="geeting-box">
			
			<div data-toggle="modal" data-target="#myModal'.$i.'" style="cursor:pointer;"> 
			
			<img src="'.$greeting_file_location.'" width="100%" class="img-responsive" />
			
			<p class="name"> '.$greeting_name.' </p>
			
			<p class="name"> '.$post_date.'  @ '.$post_time.'</p>
			
			</div>
			
			<!-- Modal -->
			<div id="myModal'.$i.'" class="modal fade" role="dialog">
			
			<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			
				<div class="modal-header">
				
				<button type="button" class="close open-sans" data-dismiss="modal">&times;</button>
				
				<h4 class="modal-title open-sans">'.$greeting_name.'</h4>
				
				</div>
				
				<div class="modal-body">
				
				<img src="'.$greeting_file_location.'" class="img-responsive" />
				
				</div>
				
				<div class="modal-footer">
				
				<button type="button" class="btn btn-info open-sans" data-dismiss="modal">Close
				</button>
				
				</div>
				
			</div>

			</div>
			
			</div>
			
			<!-- Modal end-->
			
			
			<form id="frm_request"  action="magage_greeting_list.php" method="post" enctype="multipart/form-data">
			
			<input type="hidden" name="proceed" id="proceed"  value="'.$greet_id.'" />
			
			<button type="submit" class="btn btn-proceed" id="FormSubmit"><i class="fa fa-send"> </i> Send Again </button>
			
			</form>
			
			</div>
			
			</div><!-- /column end -->
		
		';
		
		$i++; // for genrating dynamic model ids
	}
	
}

// on the proceed button

if(isset($_POST['proceed'])){
	
	$proceed_id = (int)$_POST['proceed'];
	
	//Create session for later use
	$_SESSION['greet_id'] = $proceed_id;
	
	header("location: email_list_configure.php" );
	
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
			<p class="open-sans"> Manage Greeting List</p>
			
			<p class="open-sans"> <a href="user_dashboard.php"> <i class="fa fa-home"></i> HOME </a></p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<?php echo "$full_greeting_list";?>
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12" align="center">
			
			<p> <a href="user_dashboard.php" class="open-sans"> <i class="fa fa-arrow-circle-left"></i> Back</a></p>
			
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
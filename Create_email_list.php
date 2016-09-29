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

//create new email list

$listname ="";
$filename ="";
$errorMsg = "";

if(isset($_POST['listname'])){
	
	$listname = preg_replace('#[^A-Za-z0-9 ]#i', '',$_POST['listname']);
	$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$listname = trim($listname);
    $listname = stripslashes($listname);
    $listname = strip_tags($listname);
    $listname = htmlspecialchars($listname);
	$listname = mysqli_real_escape_string($conn, $listname);
   
    $ip = trim($ip);
    $ip = stripslashes($ip);
    $ip = strip_tags($ip);
    $ip = htmlspecialchars($ip);
	$ip = mysqli_real_escape_string($conn, $ip);
	
	if($listname == ""){
     
	 $errorMsg = ' <div class="red-mark"><i class="fa fa-envelope"></i> * Please Enter A Email List Name </div>';

	}else if($_FILES['filename']['tmp_name'] == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-file-excel-o"></i> * Please Select A CSV File </div>';

	}else{
		
		$sql = mysqli_query($conn ,"INSERT INTO email_listname (list_name, user_id, ip, last_update) 
        VALUES('$listname', '$uid','$ip', now())") or die (mysql_error());
		
		$list_id = (int)mysqli_insert_id($conn);
		
		$file = $_FILES['filename']['tmp_name'];
		$handle = fopen($file,"r");
		
		//counter to ignore first line on csv file (headings)
		 $i=0;
		
		while(($file_open = fgetcsv($handle,1000,",")) !== false){
			
			if($i>0) {
				
				$first_name = $file_open[0];
				$last_name = $file_open[1];
				$email_id = $file_open[2];
				
				
				$first_name = mysqli_real_escape_string($conn, $first_name);
				$last_name = mysqli_real_escape_string($conn, $last_name);
				$email_id = mysqli_real_escape_string($conn, $email_id);
				
				
				$first_name = trim($first_name);
				$last_name = trim($last_name);
				$email_id = trim($email_id);
				
				
				$first_name = stripslashes($first_name);
				$last_name = stripslashes($last_name);
				$email_id = stripslashes($email_id);
				
				
				$first_name = strip_tags($first_name);
				$last_name = strip_tags($last_name);
				$email_id = strip_tags($email_id);	
				 
				
				$first_name = htmlspecialchars($first_name);
				$last_name = htmlspecialchars($last_name);
				$email_id = htmlspecialchars($email_id);

				$sql = mysqli_query($conn , "INSERT INTO email_list (firstname, lastname, emailid, list_name_id, ip, last_update) 
				
				VALUES('$first_name', '$last_name','$email_id', '$list_id', '$ip', now())") or die (mysql_error());
				
			}
			
			$i++; // Increment with loop so we can ignore number 0 data
			
		}
		
		header("location: user_dashboard.php" );//return to dashboad
	}
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
			
			<p class="open-sans"> Create New Email List </p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div><!-- /column end -->
			
			<div class="col-md-4">
			
			<div class="error-display"><?php print "$errorMsg"; ?></div>
			
			<form id="frm_request"  action="Create_email_list.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="listname">Email List Name</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-envelope"></span></div>
			<input type="field" class="form-control" name="listname" id="listname" placeholder="Eg. Love" value="<?php echo"$listname"; ?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="filename">Choose CSV file</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-file-excel-o"></span></div>
			<input type="file" class="form-control" name="filename" id="filename" value="" />
			</div>
			</div>
			
			<div align="center">
			<button type="submit" class="btn btn-success" id="FormSubmit"><i class="fa fa-check"> </i> Submit</button>
			
			<button type="submit" class="btn btn-info margin-left-5" id="FormSubmit"><i class="fa fa-repeat"> </i> Reset</button>
			</div>
			
			</form>
			
			</div><!-- /column end -->
			
			<div class="col-md-4"></div><!-- /column end -->
			
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
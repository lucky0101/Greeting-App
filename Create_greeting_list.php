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

$greetingname ="";
$filename ="";
$errorMsg = "";

if(isset($_POST['greeting'])){
	
	$greetingname = preg_replace('#[^A-Za-z0-9 ]#i', '',$_POST['greeting']);
	$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$greetingname = trim($greetingname);
    $greetingname = stripslashes($greetingname);
    $greetingname = strip_tags($greetingname);
    $greetingname = htmlspecialchars($greetingname);
	$greetingname = mysqli_real_escape_string($conn, $greetingname);
   
    $ip = trim($ip);
    $ip = stripslashes($ip);
    $ip = strip_tags($ip);
    $ip = htmlspecialchars($ip);
	$ip = mysqli_real_escape_string($conn, $ip);
	
	
	if($greetingname == ""){
     
	 $errorMsg = ' <div class="red-mark"><i class="fa fa-object-group"></i> * Please Enter A Greeting Name </div>';

	}else if($_FILES['filename']['tmp_name'] == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-image"></i> * Please Select A Image File (jpeg, png or gif) </div>';

	}else{
		
		// validate and uload file
		
		// temprary loation of file
		
		$file = $_FILES['filename']['tmp_name'];
		
		// catch the file name
		
		$filename = $_FILES['filename']['name'];
		
		// catch the error code for rare file not upload error
		
		$fileErrorMsg = $_FILES['filename'] ["error"];
		
		$kaboom = explode(".", $filename);// Split file name into an array using the dot
		
		$fileExt = end($kaboom); // Now target the last array element to get the file extension
		
		// create a new name for handling file
		
		$newname = str_replace(" ","_","$greetingname.$fileExt");
		
		
		if($fileSize > 102400) { // if file size is larger than 1 Megabytes
		
			$errorMsg = '<div class="red-mark"><i class="fa fa-image"></i> ERROR: Your profile pic is  larger than <b>1MB</b> in size. <br />';
			
			unlink($file); // Remove the uploaded file from the PHP temp folder
		
		} else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $filename) ) {
			
			// This condition is only if you wish to allow uploading of specific file types 
			
			$errorMsg = '<div class="red-mark"><i class="fa fa-image"></i> ERROR: Your Image file is not jpeg, png or gif </div>';
			
			unlink($file); // Remove the uploaded file from the PHP temp folder
			
		} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
		
			$errorMsg = '<div class="red-mark"><i class="fa fa-image"></i> ERROR: An error occurred while processing Image File . Try again. </div>';
			
		} else {
			
			// create a new directory for upload greeting material
			
			 $directory_name = str_replace(" ","_","$greetingname");
			 
			 mkdir("user_data/$uid/$directory_name", 0755);
			
			
			// upload the file
			
			$moveResult = move_uploaded_file($file, "user_data/$uid/$directory_name/$newname");
		
		
			// collect filename for insert in table
			
			$filename = "user_data/$uid/$directory_name/$newname";
			
			// insert data in table
			
			$sql = mysqli_query($conn, "INSERT INTO greeting_list (greeting_name, greeting_file, user_id, ip, last_update)
		 
			VALUES('$greetingname', '$filename', '$uid','$ip', now())");
			
			 // extract last insert id 
			 $greet_id = mysqli_insert_id($conn);
			 
			 //Create session for later use
			 $_SESSION['greet_id'] = $greet_id;
			
			
			header("location: Greeting_maker.php" );//return to dashboad
		
		}
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
			
			<p class="open-sans"> Create Greeting </p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div><!-- /column end -->
			
			<div class="col-md-4">
			
			<div class="error-display"><?php print "$errorMsg"; ?></div>
			
			<form id="frm_request"  action="Create_greeting_list.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="greeting">Greeting Name</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-object-group"></span></div>
			<input type="field" class="form-control" name="greeting" id="greeting" placeholder="Eg. Love Greeting" value="<?php echo"$greetingname";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="filename">Choose Image file</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-image"></span></div>
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
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

$fontname ="";
$filename ="";
$errorMsg = "";

if(isset($_POST['fontname'])){
	
	$fontname = preg_replace('#[^A-Za-z0-9 ]#i', '',$_POST['fontname']);
	$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$fontname = trim($fontname);
    $fontname = stripslashes($fontname);
    $fontname = strip_tags($fontname);
    $fontname = htmlspecialchars($fontname);
	$fontname = mysqli_real_escape_string($conn, $fontname);
   
    $ip = trim($ip);
    $ip = stripslashes($ip);
    $ip = strip_tags($ip);
    $ip = htmlspecialchars($ip);
	$ip = mysqli_real_escape_string($conn, $ip);
	
	// check the font name to stop dupliation font
	
	 $sql_font_check = mysqli_query($conn, "SELECT font_name FROM font_list WHERE font_name='$fontname' LIMIT 1");
	 $font_check = mysqli_num_rows($sql_font_check);
	
	if($fontname == ""){
     
	 $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter A Font Name </div>';

	}else if($_FILES['filename']['tmp_name'] == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-file-excel-o"></i> * Please Select A Font File ( TTF format webfont ) </div>';

	}else if($font_check > 0){
		
	 $errorMsg = ' <div class="green-mark"><i class="fa fa-font"></i> * This font is already exit in our database  </div>';
		
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
		
		$newname = str_replace(" ","_","$fontname.$fileExt");
		
		
		if (!preg_match("/.(ttf)$/i", $filename) ) {
			
			// This condition is only if you wish to allow uploading of specific file types 
			
			$errorMsg = '<div class="red-mark"><i class="fa fa-file-excel-o"></i> ERROR: Your Font file is not .ttf </div>';
			
			unlink($file); // Remove the uploaded file from the PHP temp folder
			
		} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
		
			$errorMsg = '<div class="red-mark"><i class="fa fa-file-excel-o"></i> ERROR: An error occurred while processing Font File . Try again. </div>';
			
		} else {
			
			// upload the file
			
			$moveResult = move_uploaded_file($file, "../web_fonts/$newname");
		
		
			// collect filename for insert in table
			
			$filename = "web_fonts/$newname";
			
			// insert data in table
			
			$sql = mysqli_query($conn, "INSERT INTO font_list (font_name, font_file, ip, last_update)
		 
			VALUES('$fontname', '$filename', '$ip', now())");
			
			
			header("location: index.php" );//return to dashboad
		
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
			
			<p class="open-sans"> Add New Font </p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div><!-- /column end -->
			
			<div class="col-md-4">
			
			<div class="error-display"><?php print "$errorMsg"; ?></div>
			
			<form id="frm_request"  action="add_font.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="fontname">Font Name</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-font"></span></div>
			<input type="field" class="form-control" name="fontname" id="fontname" placeholder="Eg. Arial" value="<?php echo"$fontname"; ?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="filename">Choose Font file</label>
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
			
			<br />
			
			<div class="error-display">
			
			<div class="green-mark">
			
			<p class="open-sans" align="justify" > 
			
			Note : Font file has to be <b>.ttf (True Type Font)</b> extension, also it has to be <b> webfont version </b> regular version will not work. 
			
			</p> 
			
			</div>
			
			</div>
			
			</div><!-- /column end -->
			
			<div class="col-md-4"></div><!-- /column end -->
			
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
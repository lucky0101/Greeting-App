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

// Get the greeting id 

$greet_id = (int)$_SESSION['greet_id'];

// insilize form data

$greeting_heading = '';
$heading_position_x = '';
$heading_position_y = '';
$message = '';
$message_position_x = '';
$message_position_y = '';
$greeting_image = '';
$heading_font = '';
$heading_font_size='';
$message_font = '';
$message_font_size = '';
$heading_width ='';
$message_width ='';

// get name of greeting and dipaly greeting image

$Greet_image = mysqli_query($conn, "SELECT greeting_name,greeting_file FROM greeting_list WHERE id='$greet_id' LIMIT 1");

$CheckImage = mysqli_num_rows($Greet_image);

if($CheckImage > 0){
	
	while($row = mysqli_fetch_array($Greet_image,MYSQLI_ASSOC)){
		
		$greeting_name = $row['greeting_name'];
		$greeting_file = $row['greeting_file'];
		
	}
	
	$greeting_image = '<div> <img src="'.$greeting_file.'" class="img-responsive" /> </div>';
	
}else{
	
	header("location:user_dashboard.php");
	
}

// extract saved font for greeting

$Greet_font = mysqli_query($conn, "SELECT font_name,font_file FROM font_list ORDER BY id ASC");

$Checkfont = mysqli_num_rows($Greet_font);

$font_dropdown_message ='';

$font_dropdown_heading ='';

$fontlist='';

if($Checkfont > 0){
	
	while($row = mysqli_fetch_array($Greet_font,MYSQLI_ASSOC)){
		
		$fontname = $row['font_name'];
		$fontfile = $row['font_file'];
		
		$fontlist .= '
						<option value="'.$fontfile.'">'.$fontname.'</option>
					  
					  ';
		
	}
	
	$font_dropdown_message = '<select class="form-control" id="message_font" name="message_font">'.$fontlist.'</select>';
	
	$font_dropdown_heading = '<select class="form-control" id="heading_font" name="heading_font">'.$fontlist.'</select>';
	
} else{
	
	$font_dropdown_message = '<select class="form-control">
	
							<option value="webfont/Open_Sans.ttf"> Open Sans </option>
	
							</select>';
							
	$font_dropdown_heading = '<select class="form-control">
	
							<option value="webfont/Open_Sans.ttf"> Open Sans </option>
	
							</select>';
	
}


//catch form data and create greeting

$errorMsg = '';

if(isset($_POST['message'])){
	
	// capture input with some special condition to stop hacker
	
	//$greeting_heading = preg_replace('#[^A-Za-z0-9.! ]#i', '',$_POST['greeting_heading']);
	
	$greeting_heading = $_POST['greeting_heading'];
	
	$heading_position_x = preg_replace('#[^0-9.]#i', '',$_POST['heading_position_x']);
	$heading_position_y = preg_replace('#[^0-9.]#i', '',$_POST['heading_position_y']);
	$heading_font = preg_replace('#[^A-Za-z./_ ]#i', '',$_POST['heading_font']);
	$heading_font_size = preg_replace('#[^0-9.]#i', '',$_POST['heading_font_size']);
	$heading_width = preg_replace('#[^0-9.]#i', '',$_POST['heading_width']);
	
	//$message = preg_replace('#[^A-Za-z0-9.!-@$& ]#i', '',$_POST['message']);
	
	
	$message = $_POST['message'];
	$message_position_x = preg_replace('#[^0-9.]#i', '',$_POST['message_position_x']);
	$message_position_y = preg_replace('#[^0-9.]#i', '',$_POST['message_position_y']);
	$message_font = preg_replace('#[^A-Za-z./_ ]#i', '',$_POST['message_font']);
	$message_font_size = preg_replace('#[^0-9.]#i', '',$_POST['message_font_size']);
	$message_width = preg_replace('#[^0-9.]#i', '',$_POST['message_width']);
	
	// filter data
	
	$greeting_heading = trim($greeting_heading);
    $greeting_heading = stripslashes($greeting_heading);
    $greeting_heading = strip_tags($greeting_heading);
    $greeting_heading = htmlspecialchars($greeting_heading);
	//$greeting_heading = mysqli_real_escape_string($conn, $greeting_heading);
	
	$heading_position_x = trim($heading_position_x);
    $heading_position_x = stripslashes($heading_position_x);
    $heading_position_x = strip_tags($heading_position_x);
    $heading_position_x = htmlspecialchars($heading_position_x);
	$heading_position_x = mysqli_real_escape_string($conn, $heading_position_x);
	
	$heading_position_y = trim($heading_position_y);
    $heading_position_y = stripslashes($heading_position_y);
    $heading_position_y = strip_tags($heading_position_y);
    $heading_position_y = htmlspecialchars($heading_position_y);
	$heading_position_y = mysqli_real_escape_string($conn, $heading_position_y);
	
	$heading_font = trim($heading_font);
    $heading_font = stripslashes($heading_font);
    $heading_font = strip_tags($heading_font);
    $heading_font = htmlspecialchars($heading_font);
	$heading_font = mysqli_real_escape_string($conn, $heading_font);
	
	$heading_font_size = trim($heading_font_size);
    $heading_font_size = stripslashes($heading_font_size);
    $heading_font_size = strip_tags($heading_font_size);
    $heading_font_size = htmlspecialchars($heading_font_size);
	$heading_font_size = mysqli_real_escape_string($conn, $heading_font_size);
	
	$heading_width = trim($heading_width);
    $heading_width = stripslashes($heading_width);
    $heading_width = strip_tags($heading_width);
    $heading_width = htmlspecialchars($heading_width);
	$heading_width = mysqli_real_escape_string($conn, $heading_width);
	
	$message = trim($message);
    $message = stripslashes($message);
    $message = strip_tags($message);
    $message = htmlspecialchars($message);
	//$message = mysqli_real_escape_string($conn, $message);
	
	$message_position_x = trim($message_position_x);
    $message_position_x = stripslashes($message_position_x);
    $message_position_x = strip_tags($message_position_x);
    $message_position_x = htmlspecialchars($message_position_x);
	$message_position_x = mysqli_real_escape_string($conn, $message_position_x);
	
	$message_position_y = trim($message_position_y);
    $message_position_y = stripslashes($message_position_y);
    $message_position_y = strip_tags($message_position_y);
    $message_position_y = htmlspecialchars($message_position_y);
	$message_position_y = mysqli_real_escape_string($conn, $message_position_y);
	
	$message_font = trim($message_font);
    $message_font = stripslashes($message_font);
    $message_font = strip_tags($message_font);
    $message_font = htmlspecialchars($message_font);
	$message_font = mysqli_real_escape_string($conn, $message_font);
	
	$message_font_size = trim($message_font_size);
    $message_font_size = stripslashes($message_font_size);
    $message_font_size = strip_tags($message_font_size);
    $message_font_size = htmlspecialchars($message_font_size);
	$message_font_size = mysqli_real_escape_string($conn, $message_font_size);
	
	$message_width = trim($message_width);
    $message_width = stripslashes($message_width);
    $message_width = strip_tags($message_width);
    $message_width = htmlspecialchars($message_width);
	$message_width = mysqli_real_escape_string($conn, $message_width);
	
	// missing data conditon
	
	if($message == ""){
     
	 $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter A Message </div>';

	}else if($message_position_x == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter Message X Position </div>';

	}else if($message_position_y == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter Message Y Position </div>';

	}else if($message_font_size == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter Message Font Size </div>';

	}else if($message_width == ""){

     $errorMsg = ' <div class="red-mark"><i class="fa fa-font"></i> * Please Enter Message Character Width </div>';

	}else{
		
		// get the message contain dirocoty name
		
		$directory_name = str_replace(" ","_","$greeting_name");
		
		// check if dirctory is exit than skip the code
		
		$path = "user_data/$uid/$directory_name/images";
		
		if (!file_exists($path)) { // making a diroctoy if its not exit
		
		// making a image directory for contain new greeting image
		
		mkdir("user_data/$uid/$directory_name/images",0755);
		
		}
		
		// add message in message widht
		
		$message = wordwrap($message, $message_width, "\n");
		
		// get image file extaintin for renaming it and also for some parameter
		
		$kaboom = explode(".", $greeting_file);
		$fileExt = end($kaboom);
		
		
		// Create Image From Existing File
		
		if($fileExt == "jpeg" || $fileExt == "jpg"){
			
			$canvas = imagecreatefromjpeg($greeting_file);
			
		} else if($fileExt == "png"){
			
			$canvas = imagecreatefrompng($greeting_file);
			
		}else if ($fileExt == "gif"){
			
			$canvas = imagecreatefromgif($greeting_file);
			
		}
		
		// Allocate A Color For The Text we are only going to use black for now

		$color = imagecolorallocate($canvas, 0, 0, 0);
		
		// for heading if user add heading than
		
		if( $greeting_heading !== ""){
		
		imagettftext($canvas, $heading_font_size, 0, $heading_position_x, $heading_position_y, $color, $heading_font, $greeting_heading);
		
		}
		// for message
		
		imagettftext($canvas, $message_font_size, 0, $message_position_x, $message_position_y, $color, $message_font, $message);
		
		// new name for output image
		
		$newname = str_replace(" ","_","$greeting_name.$fileExt");
		
		// save output image
		
		
		if($fileExt == "jpeg" || $fileExt == "jpg"){
			
			Imagejpeg($canvas, 'user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'', 100);
			
			$greeting_image ='<img src="user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'" class="img-responsive" />';
			
		} else if($fileExt == "png"){
			
			Imagepng($canvas, 'user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'');
			
			$greeting_image ='<img src="user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'" class="img-responsive" />';
			
		}else if ($fileExt == "gif"){
			
			Imagegif($canvas, 'user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'', 100);
			
			$greeting_image ='<img src="user_data/'.$uid.'/'.$directory_name.'/images/'.$newname.'" class="img-responsive" />';
			
		}

		// Clear Memory
		
		imagedestroy($canvas);
		
	}
	
}

// creating a function for create html page for it

if(isset($_POST['proceed'])){
	
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
	
	// create html email body
	
	$html_email_body ='
	
		<!DOCTYPE html>
		<html>
		<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
		</head>
		<body bgcolor="#ffffff" text="#000000">
			
			<div align="center" style="text-align:center;">
			
			<img src="'.$final_greeing_file.'" widht="100%" style="widht:100%;" alt="please enable image to see its greeting :)" />
			
			</div>
			
		</body>
		</html>

	';
	
	$html_email_body = trim($html_email_body);
	
	// initialize file name and location
	
	// configure it also for user server
	
	$html_file_locate = 'user_data/'.$uid.'/'.$dir_name.'/index.html'; 
	
	// create and open file
	
	$html_file = fopen($html_file_locate,"w");
	
	// put data  in file
	
	fputs($html_file,$html_email_body);
	
	// close html file
	
	fclose($html_file);
	
	header("location:email_list_configure.php");
	
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
			
			<p class="open-sans"> Create Greeting Step 2 </p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div><!-- /column end -->
			
			<div class="col-md-4">
			
			<div class="error-display"><?php print "$errorMsg"; ?></div>
			
			<p class="roboto-bold font-size-16">Greeting Image</p>
			
			<div data-toggle="modal" data-target="#myModal" style="cursor:pointer;"> 
			
			<?php echo "$greeting_image";?>
			
			<br />
			
			<p class="open-sans font-size-14"> Click on image to see it's large size</p>
			
			</div>
			
			<!-- Modal -->
			<div id="myModal" class="modal fade" role="dialog">
			
			<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			
				<div class="modal-header">
				
				<button type="button" class="close open-sans" data-dismiss="modal">&times;</button>
				
				<h4 class="modal-title open-sans"><?php echo $greeting_name;?></h4>
				
				</div>
				
				<div class="modal-body">
				
				<?php echo "$greeting_image";?>
				
				</div>
				
				<div class="modal-footer">
				
				<button type="button" class="btn btn-info open-sans" data-dismiss="modal">Close
				</button>
				
				</div>
				
			</div>

			</div>
			
			</div>
			
			<!-- Modal end-->
			
			<br /> <br /> <br />
			
			<form id="frm_request"  action="Greeting_maker.php" method="post" enctype="multipart/form-data">
			
			<div class="form-group">
			<label for="greeting_heading">Greeting Heading (Optional)</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-font"></span></div>
			<input type="field" class="form-control" name="greeting_heading" id="greeting_heading" placeholder="Eg. Happy New Year" value="<?php echo"$greeting_heading";?>" />
			</div>
			</div>
			
			<div class="error-display"> 
			<div class="green-mark"> 
			<p class="open-sans font-size-14"> Add heading parameter if you give a heading otherwise leave them blank </p> 
			</div> 
			</div>
			
			<div class="form-group">
			<label for="heading_position_x">Heading Position (X)</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> X </b> </div>
			<input type="field" class="form-control" name="heading_position_x" id="heading_position_x" placeholder="Eg. 80" value="<?php echo"$heading_position_x";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="heading_position_y">Heading Position (Y)</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> Y </b> </div>
			<input type="field" class="form-control" name="heading_position_y" id="heading_position_y" placeholder="Eg. 120" value="<?php echo"$heading_position_y";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="heading_font"> Select Heading Font</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-font"></span></div>
			<?php echo "$font_dropdown_heading";?>
			</div>
			</div>
			
			<div class="form-group">
			<label for="heading_font_size">Heading Font Size</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> S </b> </div>
			<input type="field" class="form-control" name="heading_font_size" id="heading_font_size" placeholder="Eg. 24" value="<?php echo"$heading_font_size";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="heading_width">Heading Width</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> W </b> </div>
			<input type="field" class="form-control" name="heading_width" id="heading_width" placeholder="Eg. 200" value="<?php echo"$heading_width";?>" />
			</div>
			</div>
			
			
			<br /> <hr /> <br />
			
			<div class="form-group">
			<label for="Message">Greetin Message</label>
			<textarea class="form-control" name="message" id="message" rows="5" placeholder="Type Your Message Here"><?php echo "$message";?></textarea>
			</div>
			
			
			<div class="form-group">
			<label for="message_position_x">Message Position (X)</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> X </b></div>
			<input type="field" class="form-control" name="message_position_x" id="message_position_x" placeholder="Eg. 80" value="<?php echo"$message_position_x";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="message_position_y">Message Position (Y)</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> Y </b> </div>
			<input type="field" class="form-control" name="message_position_y" id="message_position_y" placeholder="Eg. 120" value="<?php echo"$message_position_y";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="message_font"> Select Message Font</label>
			<div class="input-group">
			<div class="input-group-addon"><span class="fa fa-font"></span></div>
			<?php echo "$font_dropdown_message";?>
			</div>
			</div>
			
			<div class="form-group">
			<label for="message_font_size">Message Font Size</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> S </b> </div>
			<input type="field" class="form-control" name="message_font_size" id="message_font_size" placeholder="Eg. 16" value="<?php echo"$message_font_size";?>" />
			</div>
			</div>
			
			<div class="form-group">
			<label for="message_width">Message Width</label>
			<div class="input-group">
			<div class="input-group-addon"> <b> W </b> </div>
			<input type="field" class="form-control" name="message_width" id="message_width" placeholder="Eg. 300" value="<?php echo"$message_width";?>" />
			</div>
			</div>
			
			
			<div align="center">
			<button type="submit" class="btn btn-success" id="FormSubmit"><i class="fa fa-check"> </i> Submit</button>
			
			<button type="reset" class="btn btn-info margin-left-5" id="FormReset"><i class="fa fa-repeat"> </i> Reset</button>
			
			</div>
			
			</form>
			
			
			</div><!-- /column end -->
			
			<div class="col-md-4"></div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-4"></div><!-- /column end -->
			
			<div class="col-md-4" align="center">
			
			<form id="frm_request"  action="Greeting_maker.php" method="post" enctype="multipart/form-data">
			
			<input type="hidden" name="proceed" id="proceed"  value="Proceed" />
			
			<button type="submit" class="btn btn-proceed" id="FormSubmit"><i class="fa fa-send"> </i> PROCEED </button>
			
			</form>
			
			</div><!-- /column end -->
			
			<div class="col-md-4"></div><!-- /column end -->
			
			</div>
			
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
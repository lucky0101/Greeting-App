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
	
	while($row = mysqli_fetch_array($userinfo,MYSQLI_ASSOC)){
		
		$name = $row['fullname'];
		
		//breck name to get first name and last name
		$name_explode = explode(" ", $name);
		$firstname = $name_explode[0];
		$lastname = $name_explode[1];
		
	}
	
}

//display email list program

// get the list id for display

if(isset($_GET['listid'])){
	
	$listing_id = preg_replace('#[^0-9]#i', '', (int)$_GET['listid']);
	
	if($listing_id =='' || $listing_id == 0){
		
		header("location:Manage_email_list.php");
		
	}
	
}

// confirm this list belong to this user

$listinfo = mysqli_query($conn,"SELECT id,list_name FROM email_listname WHERE id='$listing_id' AND user_id='$uid' ORDER BY ID DESC") or die(mysqli_error($conn));

$checkList = mysqli_num_rows($listinfo);

if($checkList > 0){
	
	while($row = mysqli_fetch_array($listinfo,MYSQLI_ASSOC)){
		
		$listed_id = $row['id'];
		$listed_name = $row['list_name'];
		
	}
	
} else{
	
	header("location:Manage_email_list.php");
	
}

// pagination start here

$list_display = mysqli_query($conn, " SELECT * FROM email_list WHERE list_name_id='$listed_id' ORDER BY id DESC");

$number_rows = mysqli_num_rows($list_display); // Get total of Num rows from the database query

if (isset($_GET['pn'])) { // Get pn from URL vars if it is present

    $page_number = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers
	
} else { // If the pn URL variable is not present force it to be value of page number 1
    
	$page_number = 1;
	
}

//This is where we set how many database items to show on each page

$itemsPerPage = 6;

// Get the value of the last page in the pagination result set

$lastPage = ceil($number_rows / $itemsPerPage);

// Be sure URL variable $page_number(page number) is no lower than page 1 and no higher than $lastpage

if ($page_number < 1) { // If it is less than 1

    $page_number = 1; // force if to be 1
	
} else if ($page_number > $lastPage) { // if it is greater than $lastpage

    $page_number = $lastPage; // force it to be $lastpage's value
	
}

// This creates the numbers to click in between the next and back buttons

$centerPages = "";
$sub1 = $page_number - 1;
$sub2 = $page_number - 2;
$add1 = $page_number + 1;
$add2 = $page_number + 2;

if ($page_number == 1) {
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $page_number . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '&listid='.$listed_id.'">' . $add1 . '</a> &nbsp;';
} else if ($page_number == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '&listid='.$listed_id.'">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $page_number . '</span> &nbsp;';
} else if ($page_number > 2 && $page_number < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub2 . '&listid='.$listed_id.'">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '&listid='.$listed_id.'">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $page_number . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '&listid='.$listed_id.'">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add2 . '&listid='.$listed_id.'">' . $add2 . '</a> &nbsp;';
} else if ($page_number > 1 && $page_number < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '&listid='.$listed_id.'">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $page_number . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '&listid='.$listed_id.'">' . $add1 . '</a> &nbsp;';
}

// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query

$limit = 'LIMIT ' .($page_number - 1) * $itemsPerPage .',' .$itemsPerPage;

// Now we are going to run the same query as above but this time add $limit onto the end of the SQL syntax

// $listinfo2 is what we will use to fuel our while loop statement below
$list_display2 = mysqli_query($conn, "SELECT * FROM email_list WHERE list_name_id='$listed_id' ORDER BY ID DESC $limit");

// END Pagination Logic 

// Pagination Display Setup

$paginationDisplay = ""; // Initialize the pagination output variable

// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display

if ($lastPage != "1"){
	
    // This shows the user what page they are on, and the total number of pages
	
    $paginationDisplay .= '<p> Page <strong>' . $page_number . '</strong> of ' . $lastPage. '</p> &nbsp;  &nbsp;  &nbsp; <br />  ';
	
    // If we are not on page 1 we can place the Back button
	
    if ($page_number != 1) {
        $previous = $page_number - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '&listid='.$listed_id.'"> Back</a> ';
    }
	
    // Lay in the clickable numbers display here between the Back and Next links
	
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    
	// If we are not on the very last page we can place the Next button
    
	if ($page_number != $lastPage) {
        $nextPage = $page_number + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $nextPage . '&listid='.$listed_id.'"> Next</a> ';
    }
	
	// if no list found
	
	if($page_number == ""){
		
		$paginationDisplay ="";
		
	}
	
}

// Build the Output Section Here

$email_list_Count = mysqli_num_rows($list_display);

$email_listings = '';

if($email_list_Count > 0){
	
	while($row = mysqli_fetch_array($list_display2,MYSQLI_ASSOC)){
		
		$list_id = $row["id"];
		$list_firstname = $row["firstname"];
		$List_lastname = $row["lastname"];
		$emailid = $row["emailid"];
		$up_date = strftime( "%d %b, %Y", strtotime($row['last_update']));
		$up_time = strftime( "%I:%M:%S %p", strtotime($row['last_update']));
		
		// in case of if somebuddy name is missing
		
		$Listed_name = "$list_firstname $List_lastname";
		
		if($list_firstname ==""){
			
			$Listed_name = "NO NAME";
			
		}
		
		// creating dynamic display for list
		
		$email_listings .= '
		
			<div class="col-md-4" align="center">
			
			<div class="listbox">
			
			<h3>'.$Listed_name.'</h3>
			
			<span class="open-sans font-size-16 margin-top-30 margin-bottom-25 display-block">'.$emailid.'</span>
			
			<span class="open-sans font-size-16 margin-bottom-25 display-block">'.$up_date.' AT '.$up_time.'</span>
			
			
			</div>
			
			</div><!-- /column end -->
					
		';
	
	}

}else{
	
	$email_listings .= '
		
		<div class="col-md-3"></div>
		
		<div class="col-md-6" align="center">
		
		<div class="error-display">
		
		<div class="orange-mark">
		
		<i class="fa fa-envelope"></i> No Email List Found Please Create <a href="Create_email_list.php"><i class="fa fa-plus"></i> New Email List</a> 
		
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
			
			<p class="open-sans"> View <b><?php echo"$listed_name"; ?></b> Email Lists </p>
			
			<p class="open-sans"> 
			<a href="user_dashboard.php"> <i class="fa fa-home"></i> HOME </a> / 
			<a href="Manage_email_list.php"> <i class="fa fa-envelope"></i> Manage </a> /
			<a href="Create_email_list.php"> <i class="fa fa-plus"></i> NEW LIST </a> 
			</p>
			
			</div><!-- /column end -->
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<?php echo "$email_listings"; ?>
			
			</div><!-- /row end -->
			
			<div class="row padding-5">
			
			<div class="col-md-12" align="center">
			
			<div id="pagination">
			
			<?php echo "$paginationDisplay"; ?>
			
			</div>
			
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
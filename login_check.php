<?php
// we are going to use it as check script is user is login or not if not it will send it to back to login page or if user is logged in he can do the work

// when both username and password session is start than user can do work else he will redirect to login page.

session_start();
if(!$_SESSION["useremail"] && !$_SESSION["userpass"]){
header("location:login.php");
}else{
// here we are going to make sure register session is match to database saved info if not it will send them back to login page.

$user = $_SESSION["useremail"];
$pass = $_SESSION["userpass"];

// call connection script
require_once('scripts/connect_to_mysql.php');

$sql = mysqli_query($conn,"SELECT id FROM greetuser WHERE myemail='$user' AND mypass='$pass' LIMIT 1") or die(mysqli_error($conn)); // query the person

// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------

$existCount = mysqli_num_rows($sql); // count the row nums

if ($existCount == 0) { // evaluate the count
	 
	 header("location:index.php");
	 
}
}

?>
<?php

require "connect_to_mysql.php";  

// User Table

$sqlCommand = "CREATE TABLE IF NOT EXISTS greetuser (
		 		 id int(11) NOT NULL AUTO_INCREMENT,
				 fullname varchar(255) NOT NULL,
				 myemail varchar(255) NOT NULL,
				 mypass varchar(255) NOT NULL,
				 joinday_ip varchar(100) NOT NULL,
				 lastlogday_ip varchar(100) DEFAULT NULL,
				 joinday_date datetime NOT NULL,
				 lastlogday_date datetime NOT NULL,
				 PRIMARY KEY (id),
				 UNIQUE KEY (myemail)
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b> User </b> table has been created successfully! <br />"; 
} else { 
    echo "CRITICAL ERROR: your <b> User </b> table has not been created. <br />";
}


$sqlCommand = "CREATE TABLE IF NOT EXISTS email_listname (
		 		 id int(11) NOT NULL auto_increment,
				 list_name varchar(255) NULL,
				 user_id int(11) NOT NULL,
				 ip varchar(100) NOT NULL,
				 last_update datetime NOT NULL,
		 		 PRIMARY KEY (id)
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b> Email List Name </b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: Your <b> Email List Name </b> table has not been created.<br />";
}

$sqlCommand = "CREATE TABLE IF NOT EXISTS email_list(
		 		 id int(11) NOT NULL auto_increment,
				 firstname varchar(255) NULL,
				 lastname varchar(255) NULL,
				 emailid varchar(255) NULL,
				 list_name_id int(11) NOT NULL,
				 ip varchar(100) NOT NULL,
				 last_update datetime NOT NULL,
		 		 PRIMARY KEY (id)
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b>Email List</b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: <b>Email List</b> table has not been created.<br />";
}

$sqlCommand = "CREATE TABLE IF NOT EXISTS email_reporting(
		 		 id int(11) NOT NULL auto_increment,
				 receiver_id int(11) NOT NULL,
				 sender_id int(11) NOT NULL,
				 email_list_id int(11) NOT NULL,
				 geeting_id int(11) NOT NULL,
				 status varchar(255) NULL,
				 report varchar(500) NULL,
				 ip varchar(100) NOT NULL,
				 last_update datetime NOT NULL,
		 		 PRIMARY KEY (id)
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b>Sent Report List</b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: <b>Sent Report List</b> table has not been created.<br />";
}


$sqlCommand = "CREATE TABLE IF NOT EXISTS greeting_list(
		 		 id int(11) NOT NULL auto_increment,
				 greeting_name varchar(255) NOT NULL,
				 greeting_file varchar(255) NOT NULL,
				 user_id int(11) NOT NULL,
				 ip varchar(100) NOT NULL,
				 last_update datetime NOT NULL,
		 		 PRIMARY KEY (id)
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b>Greeting List</b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: <b>Greeting List</b> table has not been created.<br />";
}

$sqlCommand = "CREATE TABLE IF NOT EXISTS font_list(
		 		 id int(11) NOT NULL auto_increment,
				 font_name varchar(255) NOT NULL,
				 font_file varchar(255) NOT NULL,
				 ip varchar(100) NOT NULL,
				 last_update datetime NOT NULL,
		 		 PRIMARY KEY (id),
				 UNIQUE KEY (font_name)
				 
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){ 
    echo "Your <b>Greeting List</b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: <b>Greeting List</b> table has not been created.<br />";
}

$sqlCommand = "CREATE TABLE IF NOT EXISTS admins(
		 		 id int(11) NOT NULL auto_increment,
				 fullname varchar(255) NOT NULL,
				 emailid varchar(255) NOT NULL,
				 db_password varchar(255) NOT NULL,
				 ip varchar(100) NOT NULL,
				 last_login datetime NOT NULL,
		 		 PRIMARY KEY (id),
				 UNIQUE KEY (emailid)
				 
		 		 ) ";
if (mysqli_query($conn, $sqlCommand)){
    echo "Your <b>Admin</b> table has been created successfully!<br />"; 
} else { 
    echo "CRITICAL ERROR: <b>Admin</b> table has not been created.<br />";
}

mysqli_close($conn);
exit();

?>
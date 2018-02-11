<?php
$dbuser = 'techsupp';
$dbpass = 'MYSQLPASSWORD';
$dbname = 'techsupp';
$dbhost = 'localhost';

$dbconn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if($dbconn->connect_errno) {
	echo "Failed to connect to MySQL: (" 
		. $dbconn->connect_errno 
		. ") "
		. $dbcon->connect_error
		;
	die;
}
?>

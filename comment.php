<?php

include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_POST['tick'];
$comment = $_POST['comment'];
$time = time();
$tech_id = $_COOKIE['tech_id'];

include('inc/mysql.php');

$comment = $dbconn->escape_string($comment);

$comsql = ''
	. "INSERT INTO comments"
	. " (tick_id, comment, tech_id, timestamp)"
	. " VALUES"
	. " ($tick_id, '$comment', $tech_id, $time)"
	;

$comres = $dbconn->query($comsql);

header("Location: ticket.php?id=$tick_id");
?>

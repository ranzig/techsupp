<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_GET['tick'];
$cat_id = $_GET['cat'];

$remsql = ''
	. "INSERT INTO tick_cat"
	. " (tick_id, cat_id)"
	. " VALUES"
	. " ($tick_id, $cat_id)"
	;

include('inc/mysql.php');

$remret = $dbconn->query($remsql);

header("Location: ticket.php?id=$tick_id");
?>

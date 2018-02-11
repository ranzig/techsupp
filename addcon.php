<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_GET['tick'];
$con_id = $_GET['con'];

$remsql = ''
	. "INSERT INTO tick_con"
	. " (tick_id, con_id)"
	. " VALUES"
	. " ($tick_id, $con_id)"
	;

include('inc/mysql.php');

$remret = $dbconn->query($remsql);

header("Location: ticket.php?id=$tick_id");
?>

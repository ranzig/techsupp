<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_GET['tick'];
$tech_id = $_GET['tech'];

$remsql = ''
	. "INSERT INTO assignments"
	. " (tick_id, tech_id)"
	. " VALUES"
	. " ($tick_id, $tech_id)"
	;

include('inc/mysql.php');

$remret = $dbconn->query($remsql);

header("Location: ticket.php?id=$tick_id");
?>

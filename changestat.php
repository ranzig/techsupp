<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header("Location: login.php");
	die;
}

$tick_id = $_POST['tick'];
$status = $_POST['stat'];

$stat_sql = ''
	. "UPDATE tickets"
	. " SET status = '$status'"
	. " WHERE tick_id = $tick_id"
	;

include('inc/mysql.php');

$stat_res = $dbconn->query($stat_sql);

header("Location: ticket.php?id=$tick_id");
?>

<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_GET['tick'];
$con_id = $_GET['con'];

$remsql = ''
	. "DELETE FROM tick_con"
	. " WHERE tick_id = $tick_id"
	. " AND con_id = $con_id"
	;

include('inc/mysql.php');

$remret = $dbconn->query($remsql);

header("Location: ticket.php?id=$tick_id");
?>

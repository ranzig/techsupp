<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

$tick_id = $_GET['tick'];
$cat_id = $_GET['cat'];

$remsql = ''
	. "DELETE FROM tick_cat"
	. " WHERE tick_id = $tick_id"
	. " AND cat_id = $cat_id"
	;

include('inc/mysql.php');

$remret = $dbconn->query($remsql);

header("Location: ticket.php?id=$tick_id");
?>

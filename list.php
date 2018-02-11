<?php

include_once('inc/functions.php');

/*
if(!check_perm()) {
	header('Location: login.php');
	die;
}
 */

if(isset($_GET['tech'])) {
	$curr_tech = $_GET['tech'];
} else if(isset($_COOKIE['tech_id'])) {
	$curr_tech = $_COOKIE['tech_id'];
} else {
	$curr_tech = 0;
}

$techseltxt = "<select name='tech'>\n<option value='0'";

if($curr_tech == 0) {
	$techseltxt .= " selected='true'";
}

$techseltxt .= ">All Technicians</option>";

$techsql = ''
	. 'SELECT *'
	. ' FROM technicians'
	. ' ORDER BY fname, lname'
	;

include('inc/mysql.php');

$techres = $dbconn->query($techsql);

for($i = 0; $i < $techres->num_rows; $i++) {
	$techres->data_seek($i);
	$techrow = $techres->fetch_assoc();

	$id = $techrow['tech_id'];
	$fname = $techrow['fname'];
	$lname = $techrow['lname'];
	$email = $techrow['email'];

	$techseltxt .= "\n<option value='$id'";

	if($id == $curr_tech) {
		$techseltxt .= " selected='true'";
	}

	$techseltxt .= ">$lname, $fname ($email)</option>";
}

$techseltxt .= "\n</select>";

$tick_sql = ''
	. 'SELECT *'
	. ' FROM tickets, assignments, technicians'
	. ' WHERE tickets.tick_id = assignments.tick_id'
	. ' AND assignments.tech_id = technicians.tech_id'
	;

if($curr_tech != 0) {
	$tick_sql .= " AND assignments.tech_id = $curr_tech";
}

if($_GET['status'] == 'all') {
	/* tickets with no defined status */
} else if($_GET['status'] == 'closed') {
	$tick_sql .= " AND tickets.status LIKE 'Closed'";
} else {
	$tick_sql .= " AND tickets.status LIKE 'Open'";
}

$tick_sql .= ''
	. ' GROUP BY assignments.tech_id'
	. ' ORDER BY tickets.tick_id'
	;

$statseltxt = "<select name='status'>"
	. "<option value='all'"
	;

if($_GET['status'] == 'all') {
	$statseltxt .= " selected='true'";
}

$statseltxt .= ">All tickets</option>"
	. "<option value='open'"
	;

if($_GET['status'] == '' || $_GET['status'] == 'open') {
	$statseltxt .= " selected='true'";
}

$statseltxt .= ">Open tickets</option>"
	. "<option value='closed'"
	;

if($_GET['status'] == 'closed') {
	$statseltxt .= " selected='true'";
}

$statseltxt .= ">Closed tickets</option>"
	. "</select>"
	;

$ticktxt = ''
	. '<table border=1>'
	. "\n<tr>"
	. "<td><strong>Ticket#</strong></td>"
	. "<td><strong>Status</strong></td>"
	. "<td><strong>Created</strong></td>"
	. "<td><strong>Description</strong></td>"
	. "</tr>"
	;

$tick_res = $dbconn->query($tick_sql);

$last_tech = 0;

for($i = 0; $i < $tick_res->num_rows; $i++) {
	$tick_res->data_seek($i);
	$row = $tick_res->fetch_assoc();

	$tech_id = $row['tech_id'];
	$fname = $row['fname'];
	$lname = $row['lname'];
	$email = $row['email'];
	$tick_id = $row['tick_id'];
	$desc = $row['description'];
	$detail = $row['detail'];
	$timestamp = $row['timestamp'];
	$status = $row['status'];

	if($tech_id != $last_tech) {
		$last_tech = $tech_id;
		$ticktxt .= ''
			. "\n<tr><td colspan='4'>"
			. "<em><a href='mailto:$email'>$lname, $fname</a></em>"
			. "</td></tr>"
			;
	}

	$ticktxt .= ''
		. "\n<tr><td align='center'>"
		. "<a href='ticket.php?id=$tick_id'>$tick_id</a>"
		. "</td><td>"
		. $status
		. "</td><td>"
		. date("M d, Y, g:i a", $timestamp)
		. "</td><td>"
		. $desc
		. "</td></tr>"
		;
}

$ticktxt .= "\n</table>";

/* grab the ticket stuff and put it in a table */

print_head('LIST!');

print<<<CONTENT
<h1>LIST!</h1>

<form>

<p>
TECHNICIAN: 
$techseltxt
</p>

<p>
STATUS:
$statseltxt
</p>

<p>
<input type='submit' value='FILTER!'/>
</p>

</form>
$ticktxt

CONTENT;

print_foot();

?>

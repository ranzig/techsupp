<?php
include_once('inc/functions.php');

if(!check_perm()) {
	header('Location: login.php');
	die;
}

if(isset($_GET['id'])) {
	$tick_id = $_GET['id'];
} else {
	header('Location: list.php');
	die;
}

$tick_sql = ''
	. 'SELECT *'
	. ' FROM tickets'
	. " WHERE tick_id = $tick_id"
	;

include('inc/mysql.php');

$tick_res = $dbconn->query($tick_sql);
$tick_res->data_seek(0);
$row = $tick_res->fetch_assoc();

$tick_desc = $row['description'];
$tick_detail = nl2br($row['detail']);
$tick_date = date('M d, Y, g:i a', $row['timestamp']);
$tick_status = $row['status'];

$stattxt = ''
	. "<form action='changestat.php' method='post'>"
	. "<input type='hidden' name='tick' value='$tick_id'/>"
	. "<select name='stat'>"
	. "<option value='closed'"
	;

if($tick_status == 'closed') {
	$stattxt .= " selected='true'";
}

$stattxt .= ''
	. ">Closed</option>"
	. "<option value='open'"
	;

if($tick_status == 'open') {
	$stattxt .= " selected='true'";
}

$stattxt .= ''
	. ">Open</option>"
	. "<input type='submit' value='CHANGE!'/>"
	. "</form>"
	;

$assign_sql = ''
	. "SELECT *"
	. " FROM assignments, technicians"
	. " WHERE assignments.tech_id = technicians.tech_id"
	. " AND assignments.tick_id = $tick_id"
	. " ORDER BY technicians.lname, technicians.fname"
	;

$assign_res = $dbconn->query($assign_sql);

for($i = 0; $i < $assign_res->num_rows; $i++) {
	$assign_res->data_seek($i);
	$row = $assign_res->fetch_assoc();

	$lname = $row['lname'];
	$fname = $row['fname'];
	$email = $row['email'];
	$id = $row['tech_id'];

	$tech = ''
		. "<a href='mailto:$email'>$lname, $fname</a>"
		. " <em><a href='remtech.php?tick=$tick_id&tech=$id'>remove</a></em>"
		;

	$techtxt .= "<br/>$tech";
}

$addtechtxt = ''
	. "\n<form action='addtech.php'>"
	. "\n<input type='hidden' name='tick' value='$tick_id'/>"
	. "\n<select name='tech'>"
	;

$tech_sql = ''
	. "SELECT *"
	. " FROM technicians"
	. " ORDER BY lname, fname"
	;

$techreq = $dbconn->query($tech_sql);

for($i = 0; $i < $techreq->num_rows; $i++) {
	$techreq->data_seek($i);
	$row = $techreq->fetch_assoc();

	$fname = $row['fname'];
	$lname = $row['lname'];
	$id = $row['tech_id'];

	$addtechtxt .= "\n<option value='$id'>$lname, $fname</option>";
}

$addtechtxt .= ''
	. "\n</select>"
	. "\n<input type='submit' value='ADD!'/>"
	. "\n</form>"
	;

$contact_sql = ''
	. "SELECT *"
	. " FROM tick_con, contacts"
	. " WHERE tick_con.con_id = contacts.con_id"
	. " AND tick_con.tick_id = $tick_id"
	;

$contact_res = $dbconn->query($contact_sql);

$addcontxt = ''
	. "\n<form action='addcon.php'>"
	. "\n<input type='hidden' name='tick' value='$tick_id'/>"
	. "\n<select name='con'>"
	;

$conadd_sql = ''
	. "SELECT *"
	. " FROM contacts"
	. " ORDER BY lname, fname"
	;

$conaddreq = $dbconn->query($conadd_sql);

for($i = 0; $i < $conaddreq->num_rows; $i++) {
	$conaddreq->data_seek($i);
	$row = $conaddreq->fetch_assoc();

	$fname = $row['fname'];
	$lname = $row['lname'];
	$id = $row['con_id'];

	$addcontxt .= "\n<option value='$id'>$lname, $fname</option>";
}

$addcontxt .= ''
	. "\n</select>"
	. "\n<input type='submit' value='ADD!'/>"
	. "\n</form>"
	;

for($i = 0; $i < $contact_res->num_rows; $i++) {
	$contact_res->data_seek($i);
	$row = $contact_res->fetch_assoc();

	$fname = $row['fname'];
	$lname = $row['lname'];
	$email = $row['email'];
	$id = $row['con_id'];

	$contact = ''
		. "<a href='mailto:$email'>$lname, $fname</a>"
		. " <em><a href='remcon.php?tick=$tick_id&con=$id'>remove</a></em>"
		;

	$contxt .= "<br/>$contact";
}

$cat_sql = ''
	. "SELECT *"
	. " FROM tick_cat, categories"
	. " WHERE tick_cat.cat_id = categories.cat_id"
	. " AND tick_cat.tick_id = $tick_id"
	;

$cat_res = $dbconn->query($cat_sql);

for($i = 0; $i < $cat_res->num_rows; $i++) {
	$cat_res->data_seek($i);
	$row = $cat_res->fetch_assoc();

	$cat = $row['name'];
	$id = $row['cat_id'];

	$cattxt .= ''
		. "<br/>$cat"
		. " <em><a href='remcat.php?tick=$tick_id&cat=$id'>remove</a></em>"
		;
}

$addcattxt = ''
	. "<form action='addcat.php'>"
	. "\n<input type='hidden' name='tick' value='$tick_id'/>"
	. "\n<select name='cat'>"
	;

$addcat_sql = ''
	. "SELECT *"
	. " FROM categories"
	. " ORDER BY name"
	;

$addcat_res = $dbconn->query($addcat_sql);

for($i = 0; $i < $addcat_res->num_rows; $i++) {
	$addcat_res->data_seek($i);
	$row = $addcat_res->fetch_assoc();

	$id = $row['cat_id'];
	$name = $row['name'];

	$addcattxt .= "\n<option value='$id'>$name</option>";
}

$addcattxt .= ''
	. "\n</select>"
	. "\n<input type='submit' value='ADD!'/>"
	. "\n</form>"
	;

$comtxt = '';

$comsql = ''
	. "SELECT *"
	. " FROM comments,technicians"
	. " WHERE comments.tech_id = technicians.tech_id"
	. " AND comments.tick_id = $tick_id"
	. " ORDER BY timestamp DESC"
	;

$comres = $dbconn->query($comsql);

for($i = 0; $i < $comres->num_rows; $i++) {
	$comres->data_seek($i);
	$row = $comres->fetch_assoc();

	$time = $row['timestamp'];
	$fname = $row['fname'];
	$lname = $row['lname'];
	$email = $row['email'];
	$comment = nl2br($row['comment']);

	$date = date("M d, Y, g:i a", $time);

	$comtxt .= ''
		. "<div>"
		. "\n<p>"
		. "\n<em>"
		. "On $date, <a href='mailto:$email'>$lname, $fname</a> wrote:"
		. "</em>"
		. "\n<br/><br/>\n$comment"
		. "\n</p>"
		. "\n</div>"
		;
}

/* now we print it all out */

print_head("TICKET #$tick_id: $tick_desc");

print<<<CONTENT
<h1>TICKET #$tick_id: $tick_desc</h1>

<div style='overflow:hidden;'>
<div style='width:25%; float: left;'>
<p>
<strong>STATUS!:</strong>
$stattxt
</p>

<p>
<strong>TECHNICIANS!:</strong>
$techtxt
$addtechtxt
</p>

<p>
<strong>CATEGORIES!:</strong>
$cattxt
$addcattxt
</p>

<p>
<strong>CONTACTS!:</strong>
$contxt
$addcontxt
</p>
</div>

<div style='margin-left:25%;'>
<p>
<strong>DETAIL!:</strong>
<br/>
$tick_detail
</p>
</div>
</div>

<div>
<p>
<strong>COMMENTS!</strong>
</p>
<div>
<form action='comment.php' method='post'>
<input type='hidden' name='tick' value='$tick_id'/>
<textarea name='comment' cols='75'></textarea>
<input type='submit' value='COMMENT!'/>
</form>
</div>
$comtxt
</div>

CONTENT;

print_foot();
?>

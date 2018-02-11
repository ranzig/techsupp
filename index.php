<?php
if($_POST['check'] == 1) {
	process_form();
} else {
	print_form();
}

function process_form() {
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$desc = $_POST['description'];
	$detail = $_POST['detail'];
	$time = time();
	$email = $_POST['email'];

	if(
		(!isset($fname))
		|| (!isset($lname))
		|| (!isset($email))
		|| (!isset($desc))
		|| (!isset($detail))
	) {
		$errors = array("ALL FIELDS REQUIRED!");
		print_form($errors);
		return;
	}

	include('inc/mysql.php');

	$csearsql = ''
		. "SELECT con_id"
		. " FROM contacts"
		. " WHERE email LIKE '$email'"
		;

	$con_id = 0;
	$csearres = $dbconn->query($csearcsql);
	if($csearres->num_rows < 1) {
		$newcsql = ''
			. "SELECT AUTO_INCREMENT"
			. " FROM INFORMATION_SCHEMA.TABLES"
			. " WHERE TABLE_SCHEMA = 'techsupp'"
			. " AND TABLE_NAME = 'contacts'"
			;
		$newcres = $dbconn->query($newcsql);
		$newcres->data_seek(0);
		$row = $newcres->fetch_array();
		$con_id = $row[0];

		$addcsql = ''
			. "INSERT INTO contacts"
			. " (fname, lname, email)"
			. " VALUES"
			. " ('$fname', '$lname', '$email')"
			;
		$addcres = $dbconn->query($addcsql);
	} else {
		$csearres->data_seek(0);
		$row = $csearres->fetch_array();
		$con_id = $row[0];
	}

	$incsql = ''
		. "SELECT AUTO_INCREMENT"
		. " FROM INFORMATION_SCHEMA.TABLES"
		. " WHERE TABLE_SCHEMA = 'techsupp'"
		. " AND TABLE_NAME = 'tickets'"
		;

	$incres = $dbconn->query($incsql);
	$incres->data_seek(0);
	$row = $incres->fetch_array();
	$tick_id = $row[0];

	$desc = $dbconn->escape_string($desc);
	$detail = $dbconn->escape_string($detail);

	$newsql = ''
		. "INSERT INTO tickets"
		. " (description, detail, timestamp, status)"
		. " VALUES"
		. " ('$desc', '$detail', $time, 'open')"
		;

	$newres = $dbconn->query($newsql);

	$addconsql = ''
		. "INSERT INTO tick_con"
		. " (tick_id, con_id)"
		. " VALUES"
		. " ($tick_id, $con_id)"
		;

	$addtechsql = ''
		. "INSERT INTO assignments"
		. " (tick_id, tech_id)"
		. " VALUES"
		. " ($tick_id, 1)"
		;

	$acres = $dbconn->query($addconsql);
	$atres = $dbconn->query($addtechsql);

	print<<<THANKS
<html>
<head>
<title>THANKS, PUCKERFACE!</title>
</head>
<body>
<h1>THANKS!</h1>
<p>WE'LL GET TO YOU WHEN WE GET TO YOU!  DON'T HOLD YOUR BREATH, THOUGH!<p>
<p>HAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHAHA!</p>
</body>
</html>
THANKS;
}

function print_form($errors = array()) {
	if(count($errors) > 0) {
		$errortxt = ''
			. "<p>ERRORS!\n<ul>\n<li>"
			. implode("</li>\n<li>", $errors)
			. "\n</ul>\n</p>";
	}

	print<<<FORM
<html>
<head>
<title>HELP!</title>
</head>
<body>
<h1>HELP!</h1>
$errortxt

<form method='post'>
<input type='hidden' name='check' value='1'/>

<p>
FIRST NAME!: <input name='fname'/>
</p>

<p>
LAST NAME!: <input name='lname'/>
</p>

<p>
EMAIL!: <input name='email'/>
</p>

<p>
DESCRIBE!: <input name='description'/>
</p>

<p>
DETAIL!:
<br/>
<textarea name='detail' rows='20' cols='75'>
</textarea>
</p>

<p>
<input type='submit' value='SUBMIT!'/>
</p>

</form>

</body>
</html>
FORM;
}
?>

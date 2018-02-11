<?php
if($_POST['check'] == 1) {
	process_form();
} else {
	print_form();
}

function process_form() {
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$sql = ''
		. 'SELECT *'
		. ' FROM technicians'
		. " WHERE username LIKE '$user'"
		. " AND password LIKE MD5('$pass')"
		;

	include('inc/mysql.php');

	$res = $dbconn->query($sql);
	$res->data_seek(0);
	$row = $res->fetch_assoc();

	$id = $row['tech_id'];

	if($id == '') {
		print_form(array('BAD LOGIN!'));
		return;
	}

	setcookie('tech_id', $id);

	print<<<HTML
<html>
<head>
<title>REDIRECTING!</title>
<meta http-equiv='refresh' content='0; url=list.php'>
</head>
<body>
<h1>REDIRECTING!</h1>
<p>
<a href='list.php'>CLICK HERE!</a>.
</p>
</body>
</html>
HTML;
}

function print_form($errors) {
	$errortxt = '';

	if(count($errors) > 0) {
		$errortxt = "<p>\nERROR!\n<ul>\n";
		foreach($errors as $error) {
			$errortxt .= "<li>$error</li>\n";
		}
		$errortxt .= "</ul>\n</p>\n";
	}

	print<<<END
<html>
	<head>
		<title>LOGIN!</title>
	</head>
	<body>
		<h1>LOGIN!</h1>
		$errortxt

		<form method='post'>
			<input type='hidden' name='check' value='1'/>
			<p>
				USERNAME!: <input name='user'/>
			</p>
			<p>
				PASSWORD!: <input name='pass' type='password'/>
			</p>
			<p>
				<input type='submit' value='LOGIN!'/>
			</p>
		</form>
	</body>
</html>

END;
}
?>

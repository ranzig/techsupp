<?php
function print_head($title = 'TITLE!') {
	if(check_perm()) {
		$extxt = ''
			. "<div style='float:right;'>"
			. "\n<p>"
			. "\n<a href='logout.php'>LOG OUT!</a>"
			. '<br/>'
			. "\n<a href='list.php'>MY LIST!</a>"
			. "\n</p>"
			. "\n</div>"
			;
	} else {
		$extxt = '';
	}
	print<<<HEAD
<html>
<head>
<title>$title</title>
</head>
<body>
$extxt

HEAD;
}

function print_foot() {
	print<<<FOOT
</body>
</html>
FOOT;
}

function check_perm() {
	if(isset($_COOKIE['tech_id'])) {
		return true;
	} else {
		return false;
	}
}
?>

<?php
	require('./sesFunc.php');
	
	define('ID', '');
	define('PASS', '');
	
	seStart();
	
	if(isset($_POST['id']) && isset($_POST['pass'])){
		$input = $_POST['pass'];
		$userInput = hash('ripemd160', $input);
		if($_POST['id'] == ID && $userInput == PASS){
			$_SESSION['logged_in'] = true;
			session_regenerate_id(true);
			header('Location: ./articleManage.php');
		}
	}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>Title</title>
</head>

<body onload='document.forms[0].id.focus();'>

<br>
<h2>ログイン</h2>
<br>
<form action='./login.php' method='POST'>
ID：<br>
<input type='text' name='id' size='20' maxlength='20'><br>
Password：<br>
<input type='password' name='pass' size='20' maxlength='20'><br>
<input type='submit' value='ログイン'>

</body>
</html>

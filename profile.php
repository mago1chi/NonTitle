<?php
	require('./sesFunc.php');
	seStart();
	
	if(!isset($_SESSION['logged_in'])){
		header('Location: ./login.php');
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>Title</title>
</head>

<body>

<br>

<?php
	function br2nl($string){
		return str_replace('<br />', "", $string);
	}
	
	$link = new pdo('sqlite:./DB/chilabo.db');
	$sql = "select * from sqlite_master where type='table' and name='profile'";
	$result = $link->query($sql);
	if($result->fetch(PDO::FETCH_ASSOC)){
		$sql = "select name, introduction from profile";
		$result = $link->query($sql);
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		$intro = br2nl($rows['introduction']);
		echo "<form method=\"post\" action=\"profile_manage.php\">";
		echo "名前：<br>";
		echo "<input type=\"text\" name=\"name\" size=\"30\" value='{$rows['name']}'><br>";
		echo "<br>";
		echo "プロフィール：<br>";
		echo "<textarea name=\"profile\" rows=\"20\" cols=\"50\">$intro</textarea><br>";
		echo "<input type=\"submit\" value=\"更新\">";
		echo "</form>";
	} else {
		echo "<form method=\"post\" action=\"profile_manage.php\">";
		echo "名前：<br>";
		echo "<input type=\"text\" name=\"name\" size=\"30\"><br>";
		echo "<br>";
		echo "プロフィール：<br>";
		echo "<textarea name=\"profile\" rows=\"20\" cols=\"50\"></textarea><br>";
		echo "<input type=\"submit\" value=\"更新\">";
		echo "</form>";
	}
	$link = null;
?>

</body>
</html>
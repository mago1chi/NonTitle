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
<title>�L���Ǘ�</title>
</head>

<body>

<br>

<h2>�L���Ǘ�</h2>
<a href='./index.php' target='_blank'>�g�b�v�y�[�W</a>�@�@�@<a href='./logout.php'>���O�A�E�g</a>
<br>
<br>
<input type='button' name='edit' value='�V�K�L���̒ǉ�' 
	onClick="location.href='./article.php'"><br>
<br>
<input type='button' name='profile' value='�v���t�B�[���Ǘ�'
	onClick="location.href='./profile.php'"><br>

<h4>�L���ꗗ</h4>

<?php	
	/* DB�֐ڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* �L���̗L�����m�F */
	$sql = "select count(*) from article";
	$result = $link->query($sql);
	$num = $result->fetchColumn();
	if($num == 0)
		die('���J����Ă���L��������܂���D');
	/* �L�������݂���ꍇ */
	$sql = "select id,title,timeStamp from article order by id desc";
	$result = $link->query($sql);
	echo "<form method='POST' action='articleDelete.php'>\n\r";
	echo "<input type='submit' name='delete' value='�L���̏���'><br>\n\r";
	echo "<table border=1 cellpadding=5 cellspacing=0>\n\r";
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		echo "<tr>\n\r";
			echo "<td><input type=\"checkbox\" name=\"check[]\" value=\"{$rows['id']}\">
				</td>\n\r";
			echo "<td>{$rows['timeStamp']}</td>\n\r";
			echo "<td>{$rows['title']}</td>\n\r";
			echo "<td><input type='button' name='edit' value='�ҏW' 
				onClick=\"location.href='./articleEdit.php?id={$rows['id']}'\"'></td>\n\r";
		echo "</tr>\n\r";
	}
	echo "</table>\n\r";
	echo "<input type='submit' name='delete' value='�L���̏���'>\n\r";
	echo "</form>\n\r";
	$link = null;
?>

</body>
</html>

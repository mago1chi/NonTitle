<html>
<head><title>�ȈՌf����</title></head>
<body>
<form action = "board.php" method = "post">
���O<br>
<input type = "text" name = "name" size = "30" value="����������"><br>
���e<br>
<textarea name = "comment" cols = "80" rows = "20"></textarea><br>
<input type = "submit" value = "��������"><br>
</form>
<br>
<?php
	$link = new pdo('sqlite:./DB/board.db');
	$sql = "select name, comment, time from comments order by id desc";
	$result = $link->query($sql);
	$sql = "select count(*) from comments";
	$count = $link->query($sql);
	$num = $count->fetchColumn();
	$i = $num;
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		echo "$i ���O�F{$rows['name']}<br>";
		echo "���t�F{$rows['time']}<br>";
		echo "{$rows['comment']}<br>";
		echo "<br>";
		$i--;
	}
?>
</body>
</html>
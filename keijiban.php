<html>
<head><title>簡易掲示板</title></head>
<body>
<form action = "board.php" method = "post">
名前<br>
<input type = "text" name = "name" size = "30" value="名無しさん"><br>
内容<br>
<textarea name = "comment" cols = "80" rows = "20"></textarea><br>
<input type = "submit" value = "書き込み"><br>
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
		echo "$i 名前：{$rows['name']}<br>";
		echo "日付：{$rows['time']}<br>";
		echo "{$rows['comment']}<br>";
		echo "<br>";
		$i--;
	}
?>
</body>
</html>
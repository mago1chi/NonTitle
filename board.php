<html>
<head><title></title></head>
<body>
<?php
	if(!isset($_POST['name']) || !isset($_POST['comment']))
		die('不正なパラメータ');
		
	$name = htmlspecialchars($_POST["name"]);
	$comment = nl2br(htmlspecialchars($_POST['comment']));
	date_default_timezone_set('Asia/Tokyo');
	$weekday = array("日", "月", "火", "水", "木", "金", "土");
	$time = getdate();
	$number = date("w");
	$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
		$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
	$timeStamp = sprintf("%d-%02d-%02d（{$weekday[$number]}） %02d:%02d:%02d",
		$time['year'], $time['mon'], $time['mday'],
		$time['hours'], $time['minutes'], $time['seconds']);
	try{
		/* DBへ接続 */
		$link = new pdo('sqlite:./DB/board.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* テーブルcommentsの確認 */
		$sql = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			/* テーブルが無い場合作成する */
			$sql = "create table comments (id int, name text, comment text, time text)";
			$link->exec($sql);
		}
		/* 新しいデータをテーブルへ挿入 */
		$sql = "insert into comments (id, name, comment, time)
			values ($keyNum, '$name', '$comment', '$timeStamp')";
		$link->exec($sql);
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	print("書き込みに成功しました.<br>");
?>
戻るときは<a href = "./keiji.php">こちら</a>から
</body>
</html>
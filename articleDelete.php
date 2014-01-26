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
	/* DBのバックアップを作成 */
	copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
	if($_POST['check'] == ""){
		echo "削除する記事にチェックし、選択してください．";
		exit();
	}
	$check = $_POST['check'];
	/* DBへ接続 */
	try{
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* チェックされた記事の削除を繰り返す */
		for($i = 0; $i < count($check); $i++){
			$id = $check[$i];
			/* 記事の削除 */
			$sql = "delete from article where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "記事番号：$id<br>\r\n";
				echo "の削除に失敗\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "delete from time where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "記事番号：$id<br>\r\n";
				echo "の削除に失敗\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "delete from tag where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "記事番号：$id<br>\r\n";
				echo "の削除に失敗\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "select count(*) from img where id={$check[$i]}";
			$count = $link->query($sql);
			$num = $count->fetchColumn();
			if($num > 0){
				$sql = "delete from img where id={$check[$i]}";
				if(!$link->exec($sql)){
					echo "<font color='red'>\r\n";
					echo "記事番号：$id<br>\r\n";
					echo "の削除に失敗\r\n";
					echo "</font>";
					copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
					die();
				}
			}
			echo "記事番号：$id<br>\r\n";
			echo "を削除．<br>\r\n";
			echo "<br>\r\n";
		}
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	echo "指定された記事の削除を完了．<br>\r\n";
?>

戻るときは<a href = './articleManage.php'>こちら</a>から．

</body>
</html>

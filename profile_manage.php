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
	/* フォームからの入力を取得 */
	@$name = $_POST["name"];
	@$profile = nl2br($_POST["profile"]);
	try {
		/* DBへ接続 */
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!$link)
			print('DBへの接続に失敗．');
		/* テーブルprofileの確認 */
		$query = "select * from sqlite_master where type='table' and name='profile'";
		$result = $link->query($query);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$query = "create table profile (id int, name text, introduction text)";
			$result = $link->exec($query);
			/* テーブルprofileにプロフィールを挿入 */
			$query = "insert into profile (id, name, introduction) values (1, '$name', '$profile')";
			$flag = $link->exec($query);
			if(!$flag)
				die('プロフィール更新に失敗．');
			echo "プロフィール更新に成功！<br>";
		} else {			
			/* テーブルprofileにプロフィールを挿入 */
			$query = "update profile set name='$name', introduction='$profile' where id=1";
			$flag = $link->exec($query);
			if(!$flag)
				die('プロフィール更新に失敗．');
			echo "プロフィール更新に成功！<br>";
		}
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
?>
戻るときは<a href = "./articleManage.php">こちら</a>から
</body>
</html>
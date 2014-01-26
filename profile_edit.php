<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>Title</title>
</head>

<body>

<br>

<?php
	/* フォームからの入力を取得 */
	$name = htmlspecialchars($_POST["name"]);
	$profile = nl2br(htmlspecialchars($_POST["profile"]));
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* テーブルprofileにプロフィールを挿入 */
	$query = "update profile set name='$name', introduction='$profile' where id=1";
	$flag = $link->exec($query);
	if(!$flag)
		die('プロフィール更新に失敗．');
	echo "プロフィール更新に成功！<br>";
?>
戻るときは<a href = "./articleManage.php">こちら</a>から
</body>
</html>
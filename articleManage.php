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
<title>記事管理</title>
</head>

<body>

<br>

<h2>記事管理</h2>
<a href='./index.php' target='_blank'>トップページ</a>　　　<a href='./logout.php'>ログアウト</a>
<br>
<br>
<input type='button' name='edit' value='新規記事の追加' 
	onClick="location.href='./article.php'"><br>
<br>
<input type='button' name='profile' value='プロフィール管理'
	onClick="location.href='./profile.php'"><br>

<h4>記事一覧</h4>

<?php	
	/* DBへ接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* 記事の有無を確認 */
	$sql = "select count(*) from article";
	$result = $link->query($sql);
	$num = $result->fetchColumn();
	if($num == 0)
		die('公開されている記事がありません．');
	/* 記事が存在する場合 */
	$sql = "select id,title,timeStamp from article order by id desc";
	$result = $link->query($sql);
	echo "<form method='POST' action='articleDelete.php'>\n\r";
	echo "<input type='submit' name='delete' value='記事の消去'><br>\n\r";
	echo "<table border=1 cellpadding=5 cellspacing=0>\n\r";
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		echo "<tr>\n\r";
			echo "<td><input type=\"checkbox\" name=\"check[]\" value=\"{$rows['id']}\">
				</td>\n\r";
			echo "<td>{$rows['timeStamp']}</td>\n\r";
			echo "<td>{$rows['title']}</td>\n\r";
			echo "<td><input type='button' name='edit' value='編集' 
				onClick=\"location.href='./articleEdit.php?id={$rows['id']}'\"'></td>\n\r";
		echo "</tr>\n\r";
	}
	echo "</table>\n\r";
	echo "<input type='submit' name='delete' value='記事の消去'>\n\r";
	echo "</form>\n\r";
	$link = null;
?>

</body>
</html>

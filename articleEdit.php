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
	/* URLから値を取得 */
	$id = $_GET['id'];
	/* DBへ接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* テーブルからタイトル、内容、タグを取得 */
	$sql = "select a.title,a.content,b.name from article as a, tag as b 
		where a.id=b.id and a.id = $id";
	if(!$result = $link->query($sql))
		die('データの取得に失敗．');
	$rows = $result->fetch(PDO::FETCH_ASSOC);
	$content = $rows['content'];
	echo "<form method = 'POST' action = './tableEdit.php?id=$id'
		enctype='multipart/form-data'>\r\n";
	echo "タイトル：<br>\r\n";
	echo "<input type='text' name='title' size='50' value='{$rows['title']}'><br>\r\n";
	echo "<br>\r\n";
	echo "<br>\r\n";
	echo "記事内容：<br>\r\n";
	echo "<textarea name='article' rows='30' cols='100'>$content</textarea><br>\r\n";
	echo "※本文の改行に&ltbr /&gtを使う必要あり．（RSSフィードのエラー回避をするため）";
	echo "<br>\r\n";
	echo "<br>\r\n";
	echo "タグ：<br>\r\n";
	echo "<input type='text' name='tag' size='20' value='{$rows['name']}'><br>\r\n";
	echo "<br>\r\n";
	echo "画像のアップロード：<br>\r\n";
	echo "※ファイルサイズは500kb以下まで可．拡張子が「jpg、png」の画像のみOK．<br>
		またファイル名は英数字のみ．<br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<br>\r\n";
	echo "<input type='submit' value='編集完了'>\r\n";
	echo "</form>";
	$link = null;
?>

</body>
</html>

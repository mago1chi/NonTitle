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

<form method="post" action="diary_manage.php"  enctype='multipart/form-data'>
タイトル：<br>
<input type="text" name="title" size="50"><br>
<br>
<br>
記事内容：<br>
<textarea name="article" rows="30" cols="100"></textarea><br>
※本文の改行に&ltbr /&gtを使う必要あり．（RSSフィードのエラー回避をするため）<br>
<br>
<br>
タグ：<br>
<input type="text" name="tag" size="20"><br>
<br>
画像のアップロード：<br>
※ファイルサイズは1MB以下まで可．拡張子が「jpg、png」の画像のみOK．<br>
またファイル名は英数字のみ．<br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<br>
<input type="submit" value="投稿">
</form>
</body>
</html>
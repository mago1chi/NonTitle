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
	/* 変更された値を取得 */
	@$title = $_POST["title"];
	@$article = $_POST["article"];
	$tag = htmlspecialchars($_POST["tag"]);
	/* 変更された記事のIDを取得 */
	$id = $_GET['id'];
	/* 投稿時の規則判定 */
	if(!strcmp($title, "")){
		print("タイトルを記入してください.");
		exit();
	}
	if(!strcmp($article, "")){
		print("内容を書いてください.");
		exit();
	}
	/* 画像ファイルのサイズ、拡張子を判定 */
	$upFile = $_FILES['imgUpload'];
	for($i = 0; $i < 10; $i++){
		if($upFile['name'][$i] != "" && ($upFile['size'][$i] > 1000000 || !require('extDetect.php'))){
			die('ファイルサイズが1MB以上か、もしくはファイル名、拡張子に違反があります．');
		}
	}
	/* DBのバックアップ作成 */
	copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
	/* DBへ接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	$sql = "update article set title='$title', content='$article' where id=$id";
	if(!$link->exec($sql)){
		copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
		die('記事の更新に失敗');
	}
	$sql = "update tag set name='$tag' where id=$id";
	if(!$link->exec($sql)){
		copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
		die('記事の更新に失敗');
	}
	/* 画像ファイル用テーブルの確認 */
	$sql = "select * from sqlite_master where type='table' and name='img'";
	$result = $link->query($sql);
	if(!$result->fetch(PDO::FETCH_ASSOC)){
		$sql = 'create table img (id int, path text)';
		$result = $link->exec($sql);
		if(!$result)
			die('imgテーブルの作成に失敗');
	}
	/* 画像がアップロードされているかを確認 */
	$imgFlag = false;
	for($i = 0; $i < 10; $i++){
		if($upFile['name'][$i] != ""){
			$imgFlag = true;
			break;
		}
	}
	/* 画像がアップロードされている場合 */
	if($imgFlag){
		for($i = 0; $i < 10; $i++){
			if($upFile['name'][$i] != ""){
				$upPath = "./img/{$upFile['name'][$i]}";
				$fileName[] = $upFile['name'][$i];
				move_uploaded_file($upFile['tmp_name'][$i], $upPath);
			}
		}
		/* 画像ファイルのパスをテーブルへ登録 */
		for($i = 0; $i < count($fileName); $i++){
			$sql = "insert into img (id, path) values ($id, '$fileName[$i]')";
			if(!$link->exec($sql)){
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die('画像のアップロードに失敗．');
			}
		}
	}
	$link = null;
?>

記事の更新が完了．<br>
戻るときは<a href = './articleManage.php'>こちら</a>から．

</body>
</html>

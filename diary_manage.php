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
	date_default_timezone_set('Asia/Tokyo');
	$time = getdate();
	$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
		$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
	$timeStamp = sprintf("%d-%02d-%02d %02d:%02d",
		$time['year'], $time['mon'], $time['mday'],
		$time['hours'], $time['minutes']);
	/* 記事の処理 */
	@$title = $_POST["title"];
	@$article = $_POST["article"];
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
	
	try{
		/* DB接続 */
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!$link)
			die('DBへの接続に失敗．');
		
		/* バックアップファイルの作成 */
		copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
		
		/* 記事管理テーブルの確認 */
		$sql = "select * from sqlite_master where type='table' and name='article'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = "create table article (id int, year int, month int, day int, title text, content text, timeStamp text)";
			$result = $link->exec($sql);
		}
		/* タイムスタンプ管理テーブルの確認 */
		$sql = "select * from sqlite_master where type='table' and name='time'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = "create table time (id int, hour int, minute int, second int)";
			$result = $link->exec($sql);
		}
		
		/* テーブルarticleへ新規記事の挿入 */
		$sql = "insert into article (id, year, month, day, title, content, timeStamp) 
			values ($keyNum, $time[year], $time[mon], $time[mday], '$title', '$article', '$timeStamp')";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('新規記事の挿入に失敗．');
		}
		/* テーブルtimeへタイムスタンプを挿入 */
		$sql = "insert into time (id, hour, minute, second) 
			values ($keyNum, $time[hours], $time[minutes], $time[seconds])";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('新規記事のタイムスタンプエラー．');
		}
		
		/* タグの処理 */
		$tag = htmlspecialchars($_POST["tag"]);
		/* タグ用テーブルの確認 */
		$sql = "select * from sqlite_master where type='table' and name='tag'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = 'create table tag (name text, id int)';
			$result = $link->exec($sql);
			if(!$result)
				die('tagテーブルの作成に失敗．');
		}
		/* タグデータの挿入 */
		$sql = "insert into tag (name, id) values ('$tag', $keyNum)";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('タグデータ更新に失敗．');
		}
		
		/* commnetテーブルが無ければ作成 */
		$query = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($query);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$query = "create table comments (id int, time int, name text, comment text, timeStamp text)";
			$link->exec($query);
		}
		
		/* 画像ファイル用テーブルの確認 */
		$sql = "select * from sqlite_master where type='table' and name='img'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = 'create table img (id int, path text)';
			$result = $link->exec($sql);
		}
		/* 画像がアップロードされているかを確認 */
		$imgFlag = false;
		for($i = 0; $i < 10; $i++){
			if($upFile['name'][$i] != ""){
				$imgFlag = true;
				break;
			}
		}
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
				$sql = "insert into img (id, path) values ($keyNum, '$fileName[$i]')";
				if(!$link->exec($sql)){
					copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
					die('画像のアップロードに失敗．');
				}
			}
		}
		
		/* RSSフィードの生成 */
		require('./writeRSS.php');
		
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	print("記事の新規追加が完了しました.<br>");
?>

戻るときは<a href = "./articleManage.php">こちら</a>から
</body>
</html>
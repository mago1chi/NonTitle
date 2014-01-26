<?php
	/* 画像認証のためにセッションを利用 */
	session_start();
	
	/* 画像認証を利用するためのソースコード(securimage.php)を読み込み */
	include_once './securimage/securimage.php';
	$securimage = new Securimage();
	
	/* 新規コメントをテーブルへ追加 */
	/* コメント内容の有無を確認 */
	if(isset($_POST['comment'])){
	
		/* 画像認証を実行 */
		if ($securimage->check($_POST['captcha_code']) == false) {
		  echo "認証コードが正しくありません．<br /><br />";
		  echo "<a href='javascript:history.go(-1)'>戻る</a>";
		  exit();
		} else {
			/* 画像認証が通ればコメントをDBへ登録 */
			$id = $_GET['id'];
			$name = htmlspecialchars($_POST['name']);
			$comment = $_POST['comment'];
			/* 書き込み時刻を取得 */
			date_default_timezone_set('Asia/Tokyo');
			$time = getdate();
			$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
				$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
			$timeStamp = sprintf("%d-%02d-%02d %02d:%02d",
				$time['year'], $time['mon'], $time['mday'],
				$time['hours'], $time['minutes']);
			/* DBに接続 */
			$link = new pdo('sqlite:./DB/chilabo.db');
			/* 新規コメントを挿入 */
			$query = "insert into comments (id, time, name, comment, timeStamp)
				values ($id, $keyNum, '$name', '$comment', '$timeStamp')";
			$link->exec($query);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="./layout.css" type="text/css">
<title>Non Title</title>
<script type="text/javascript" src="scripts/shCore.js"></script>
<script type="text/javascript" src="scripts/shBrushJScript.js"></script>
<link type="text/css" rel="stylesheet" href="styles/shCoreDefault.css"/>
<script type="text/javascript">SyntaxHighlighter.all();</script>
<script type="text/javascript" src="scripts/shBrushCpp.js"></script>
</head>
<body>

<br>

<header id = "chilabo">
<a href='./index.php'>
<img src='./chilaboTop2.png'>
</a>
</header>


<?php
	$id = $_GET['id'];
	echo "<section id = \"articles\">\r\n";
	
	/* DBに接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link){
		die('DBへの接続に失敗．');
	}
	
	/* 指定された記事の表示 */
	$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
	where a.id=b.id and a.id=$id";
	/* クエリからデータ取得 */
	$result = $link->query($query);
	$flag = true;
	if(!$rows = $result->fetch(PDO::FETCH_ASSOC)){
		echo '<br><center>存在しない記事です．<br><br>';
		/* HOMEへのリンク表示 */
		echo "<a href='./index.php'>HOME</a></center>";
		$flag = false;
	}
	else {
		/* 記事の表示 */
		/* 記事表示のテンプレを呼び出し */
		require('./writeArticle.php');
		/* 前の記事へのリンク表示 */
		echo "<center>";
		$query = "select id,title from article where id<$id order by id desc";
		$result = $link->query($query);
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		$title = mb_strimwidth($rows['title'], 0, 13, '…', 'sjis');
		if($rows){
			echo "<a href='./each.php?id={$rows['id']}' title='{$rows['title']}'>&lt&lt$title</a>　|";
		}
		/* HOMEへのリンク表示 */
		echo "　<a href='./index.php'>HOME</a>　";
		/* 次の記事へのリンク表示 */
		$query= "select id,title from article where id>$id order by id";
		$result = $link->query($query);
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		$title = mb_strimwidth($rows['title'], 0, 13, '…', 'sjis');
		if($rows){
			echo "|　<a href='./each.php?id={$rows['id']}' title='{$rows['title']}'>$title>></a>";
		}
	}
	echo "</center>";
	
	if($flag){
		/* コメント機能 */
		echo "<div id='comments'>";
		echo "<a name='comment'></a>";
		echo "<p id='comments'>コメント</p>\r\n";
		/* commentsテーブルの有無を確認 */
		$query = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($query);
		if($result->fetch(PDO::FETCH_ASSOC)){
			/* 既にテーブルがあれば内容を表示 */
			$query = "select name, comment, timeStamp from comments where id=$id order by time";
			$result = $link->query($query);
			while($rows = $result->fetch(PDO::FETCH_ASSOC)){
				$comment = nl2br(htmlspecialchars($rows['comment']));
				echo "<ul>\r\n";
				echo "<li id='name'>{$rows['name']}</li>\r\n";
				echo "<li id='comment'>$comment</li>\r\n";
				echo "<li id='timeStamp'>{$rows['timeStamp']}</li>\r\n";
				echo "</ul>\r\n";
			}
		}
		echo "<form method='post' action='./each.php?id=$id'>\r\n";
		echo "名前：<br>";
		echo "<input type='text' name='name' size='30' value='名無しさん'><br>\r\n";
		echo "コメント：<br>";
		echo "<textarea name='comment' rows='20' cols='70'></textarea><br>\r\n";
		echo "<br>";
		echo "画像認証：<br>";
		echo '<img id="captcha" src="./securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" />';
		echo '<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById(\'captcha\').src = \'./securimage/securimage_show.php?sid=\' + Math.random(); this.blur(); return false">
		<img src="./securimage/images/refresh.png" alt="画像の再読み込み" onclick="this.blur()" align="bottom" border="0" width=20 hetigh=20></a><br>';
		echo '<input type="text" name="captcha_code" size="10" maxlength="6" /><br>';
		echo "<br>";
		echo "<input type='submit' value='投稿'><br>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
	}
	echo "</section>\r\n";
	
	echo "<nav id = 'rightColumn'>\r\n";
	/* プロフィールの表示 */
	echo "<div id = \"profile\">\r\n";
	$query = "select name, introduction from profile";
	$result = $link->query($query);
	if(!$result){
		echo 'プロフィールがありません．';
		echo '</div>';
	}
	else {
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		/* タグごとの記事数を取得 */
		require('./writeProf.php');
	}
	
	/* 最新コメント一覧の表示 */
	echo "<div id = 'comlist'>\r\n";
	$query = "select id, name from comments order by time desc limit 10";
	$result = $link->query($query);
	echo "<p id = 'com'>最近のコメント一覧</p>\r\n";
	echo "<ul id = 'coms'>\r\n";
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		/* コメント一覧表示テンプレ呼び出し */
		require('./writeCom.php');
	}
	echo "</ul>\r\n";
	echo "</div>\r\n";
	
	/* 年月別リンクの表示 */
	echo "<div id = \"ymcategory\">\r\n";
	$query = "select distinct year, month from article order by id desc";
	$result = $link->query($query);
	if(!$result)
		die('年月別リンク作成に失敗．');
	echo "<p id = \"ym\">年月別過去の記事一覧</p>\r\n";
	echo "<ul id = \"yms\">\r\n";
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		require('./writeYM.php');
	}
	echo "</ul>\r\n";
	echo "</div>\r\n";
	
	/* タグ別リンクの表示 */
	echo "<div id = \"tagcategory\">\r\n";
	$query = "select distinct name from tag order by name";
	$result = $link->query($query);
	if(!$result)
		die('タグ別リンク作成に失敗．');
	echo "<p id = \"tag\">タグ別過去の記事一覧</p>\r\n";
	echo "<ul id = \"tags\">\r\n";
	while($rows = $result->fetch(PDO::FETCH_ASSOC)){
		require('./writeTag.php');
	}
	echo "</ul>\r\n";
	echo "</div>\r\n";
?>

<p>リンク</p>
<ul>
<li><a href='http://www.hiroshima-u.ac.jp/index-j.html/'>広島大学</a></li>
<li><a href='http://www.se.hiroshima-u.ac.jp/'>分散システム学</a></li>
</ul>
</nav>

<footer>
連絡は<a href='mailto:shinjiro@se.hiroshima-u.ac.jp'>こちら</a>まで．<br>
Copyright (C) 2013 Shinjiro All Rights Reserved.
</footer>

</body>
</html>

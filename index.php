<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="./layout.css" type="text/css">
<link href="http://www.se.hiroshima-u.ac.jp/~shinjiro/chilabo.xml" rel="alternate" type="application/rss+xml" title="Non Title"/>
<title>Non Title</title>
<script type="text/javascript" src="scripts/shCore.js"></script>
<script type="text/javascript" src="scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="scripts/shBrushCpp.js"></script>
<link type="text/css" rel="stylesheet" href="styles/shCoreDefault.css"/>
<script type="text/javascript">SyntaxHighlighter.all();</script>
</head>
<body>

<br>

<header id = "chilabo">
<a href='./index.php'>
<img src='./chilaboTop2.png'>
</a>
</header>


<?php
	echo "<section id = \"articles\">\r\n";
	echo "<p id = \"category\">最近の記事</p>\r\n";
	/* DBに接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link){
		die('DBへの接続に失敗．');
	}
	/* 記事の有無を確認 */
	$query = "select * from sqlite_master where type = 'table' and name = 'article'";
	$result = $link->query($query);
	if(!$result->fetch(PDO::FETCH_ASSOC)){
		echo "<li id = \"article\">\r\n";
		echo "記事がありません.\r\n";
		echo "</li>\r\n";
		echo "</section>\r\n";
	} else {
		/* 最新記事3件の表示 */
		$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
		where a.id=b.id order by a.id desc limit 4";
		/* クエリからデータ取得 */
		$result = $link->query($query);
		if(!$result)
			die('クエリが失敗しました．');
		/* 記事の表示 */
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			/* 記事表示のテンプレを呼び出し */
			require('./indexArticle.php');
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
	}
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

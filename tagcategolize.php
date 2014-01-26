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
	echo "<section id = 'articles'>\r\n";
	
	/* 指定年月記事の一覧を作成するためのテンプレート */
	/* 使用するときは所定の文字を置換 */
	
	/* URLから値取得 */
	if(!isset($_GET['tag']))
		die("タグ別ページの表示に必要なパラメータがありません．");
	$tag = $_GET['tag'];
	if(!isset($_GET['num']))
		die("タグ別ページの表示に必要なパラメータがありません．");
	$num = $_GET['num'];
	if(!isset($_GET['tnum']))
		die("タグ別ページの表示に必要なパラメータがありません．");
	$tnum = $_GET['tnum'];
	/* DBへ接続 */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link)
		die('DBへの接続に失敗');
	/* テーブルへのクエリ作成 */
	if($tnum < 5){
		$query = "select a.id,a.year,a.month,a.title,a.content,a.timeStamp,b.name 
			from article as a, tag as b where a.id = b.id and 
			b.name = '$tag' order by a.id DESC";
		/* クエリからデータ取得 */
		$result = $link->query($query);
		if(!$result)
			die('クエリが失敗しました．');
		/* 記事の表示 */
		echo "<p id = \"category\">タグ「".$tag."」の記事一覧</p>";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			require('./indexArticle.php');
		}
	} else {
		/* テーブルへのクエリ作成 */
		$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
		where a.id=b.id and b.name='$tag' order by a.id desc limit $num*5, 5";
		/* クエリからデータ取得 */
		$result = $link->query($query);
		if(!$result)
			die('クエリが失敗しました．');
		/* 記事の表示 */
		echo "<p id = \"category\">タグ「".$tag."」の記事一覧</p>";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			/* 記事表示のテンプレを呼び出し */
			require('./indexArticle.php');
		}
		/* ページ分割処理 */
		$pageNum = ceil($tnum / 5);
		echo "<center>";
		echo "<b>";
		if($num > 0)
			echo "<a href = './tagcategolize.php?tag=$tag&num=",$num-1,"&tnum=$tnum'>&lt&ltprev</a>　";
		for($i = 0; $i < $pageNum; $i++){
			if($i == $pageNum-1){
				if($i == $num)
					echo $i+1;
				else
					echo "<a href = './tagcategolize.php?tag=$tag&num=$i&tnum=$tnum'>",$i+1,"</a>";
			}
			else{
				if($i == $num)
					echo $i+1,"　";
				else
					echo "<a href = './tagcategolize.php?tag=$tag&num=$i&tnum=$tnum'>",$i+1,"</a>　";
			}
		}
		if($num < $pageNum-1)
			echo "<a href = './tagcategolize.php?tag=$tag&num=",$num+1,"&tnum=$tnum'>　next>></a>　";
		echo "</b>";
		echo "</center>";
	}
	echo "</section>\r\n";
	
	echo "<nav id = 'rightColumn'>";
	/* プロフィールの表示 */
	echo "<div id = \"profile\">\r\n";
	$query = "select name, introduction from profile";
	$result = $link->query($query);
	if(!$result){
		echo 'プロフィールがありません．';
		echo "</div>";
	}
	else {
		$rows = $result->fetch(PDO::FETCH_ASSOC);
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
	
	$link = null;
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

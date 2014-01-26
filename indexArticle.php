<?php
	/* １記事ごとの処理 */
	/* タグ別記事用のカウント */
	$query = "select count(*) from tag where name='{$rows['name']}'";
	$count = $link->query($query);
	$tagnum = $count->fetchColumn();
	echo "<article>";
	echo "<ul id = \"oneArticle\">";
	$id = $rows['id'];
	$content = $rows['content'];
	$content = require('./imgConvert.php');

	if(preg_match_all("/\n/", $content, $matchRes) > 15){
		preg_match("/(.*\n){10}/", $content, $shortC);
			
		$name = $rows['name'];

		/* コメント数の表示のためにカウント */
		$query = "select count(*) from comments where id=$id";
		$comResult = $link->query($query);
		$comCount = $comResult->fetchColumn();
		
		/* 記事の表示処理 */
		echo "<li id = \"title\">\r\n";
		echo "<a href='./each.php?id=$id'>{$rows['title']}</a>\r\n";
		echo "</li>\r\n";
		echo "<li id = \"contents\">\r\n";
		echo "$shortC[0]...<br /><a href='./each.php?id=$id'>続きを読む</a>";
		echo "</li>\r\n";
		echo "<li id = \"time\">\r\n";
		echo "<a href='./each.php?id=$id#comment'>コメント($comCount)</a>　　
			タグ：<a href='./tagcategolize.php?tag=$name&num=0&tnum=$tagnum'>$name</a>　　
	{$rows['timeStamp']}　　\r\n
	<a href=''>▲</a>";
		echo "</ul>";
		echo "</article>";
		
	} else {
		$name = $rows['name'];

		/* コメント数の表示のためにカウント */
		$query = "select count(*) from comments where id=$id";
		$comResult = $link->query($query);
		$comCount = $comResult->fetchColumn();
		
		/* 記事の表示処理 */
		echo "<li id = \"title\">\r\n";
		echo "<a href='./each.php?id=$id'>{$rows['title']}</a>\r\n";
		echo "</li>\r\n";
		echo "<li id = \"contents\">\r\n";
		echo "$content";
		echo "</li>\r\n";
		echo "<li id = \"time\">\r\n";
		echo "<a href='./each.php?id=$id#comment'>コメント($comCount)</a>　　
			タグ：<a href='./tagcategolize.php?tag=$name&num=0&tnum=$tagnum'>$name</a>　　
	{$rows['timeStamp']}　　\r\n
	<a href=''>▲</a>";
		echo "</ul>";
		echo "</article>";

	}

?>

<?php
	$query = "select title from article where id={$rows['id']}";
	$comResult = $link->query($query);
	$title = $comResult->fetch(PDO::FETCH_ASSOC);
	$shortT = mb_strimwidth($title['title'], 0, 13, 'Åc', 'sjis');
	echo "<li id = \"onecom\">\r\n";
	echo "<a href = \"./each.php?id={$rows['id']}#comment\" title = \"{$title['title']}({$rows['name']})\">
		$shortT({$rows['name']})\r\n";
	echo "</a>\r\n";
	echo "</li>\r\n";
?>
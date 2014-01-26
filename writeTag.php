<?php
	$query = "select count(*) from tag where name='{$rows['name']}'";
	$count = $link->query($query);
	$num = $count->fetchColumn();
	echo "<li id = \"onetag\">\r\n";
	echo "<a href = \"./tagcategolize.php?tag={$rows['name']}&num=0&tnum=$num\">
		{$rows['name']}($num)\r\n\r\n";
	echo "</a>\r\n";
	echo "</li>\r\n";
?>
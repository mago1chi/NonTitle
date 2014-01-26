<?php
	$query = "select count(*) from article where year={$rows['year']} 
		and month={$rows['month']}";
	$count = $link->query($query);
	$num = $count->fetchColumn();
	echo "<li id = \"oneym\">\r\n";
	echo "<a href = \"./yearmonth.php?year={$rows['year']}&mon={$rows['month']}&num=0&tnum={$num}\">
		{$rows['year']}”N{$rows['month']}ŒŽ($num)\r\n";
	echo "</a>\r\n";
	echo "</li>\r\n";
?>
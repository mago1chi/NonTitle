<?php
	echo "<p id = \"ym\">プロフィール</p>\r\n";
	echo "<ul id = \"profileList\">\r\n";
	echo "<li id = \"name\">\r\n";
	echo "{$rows['name']}\r\n";
	echo "</li>\r\n";
	echo "<li id = \"introduction\">\r\n";
	echo "{$rows['introduction']}\r\n";
	echo "</li>\r\n";
	echo "</ul>\r\n";
	echo "</div>\r\n";
?>
<?php
	/* �P�L�����Ƃ̏��� */
	/* �^�O�ʋL���p�̃J�E���g */
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

		/* �R�����g���̕\���̂��߂ɃJ�E���g */
		$query = "select count(*) from comments where id=$id";
		$comResult = $link->query($query);
		$comCount = $comResult->fetchColumn();
		
		/* �L���̕\������ */
		echo "<li id = \"title\">\r\n";
		echo "<a href='./each.php?id=$id'>{$rows['title']}</a>\r\n";
		echo "</li>\r\n";
		echo "<li id = \"contents\">\r\n";
		echo "$shortC[0]...<br /><a href='./each.php?id=$id'>������ǂ�</a>";
		echo "</li>\r\n";
		echo "<li id = \"time\">\r\n";
		echo "<a href='./each.php?id=$id#comment'>�R�����g($comCount)</a>�@�@
			�^�O�F<a href='./tagcategolize.php?tag=$name&num=0&tnum=$tagnum'>$name</a>�@�@
	{$rows['timeStamp']}�@�@\r\n
	<a href=''>��</a>";
		echo "</ul>";
		echo "</article>";
		
	} else {
		$name = $rows['name'];

		/* �R�����g���̕\���̂��߂ɃJ�E���g */
		$query = "select count(*) from comments where id=$id";
		$comResult = $link->query($query);
		$comCount = $comResult->fetchColumn();
		
		/* �L���̕\������ */
		echo "<li id = \"title\">\r\n";
		echo "<a href='./each.php?id=$id'>{$rows['title']}</a>\r\n";
		echo "</li>\r\n";
		echo "<li id = \"contents\">\r\n";
		echo "$content";
		echo "</li>\r\n";
		echo "<li id = \"time\">\r\n";
		echo "<a href='./each.php?id=$id#comment'>�R�����g($comCount)</a>�@�@
			�^�O�F<a href='./tagcategolize.php?tag=$name&num=0&tnum=$tagnum'>$name</a>�@�@
	{$rows['timeStamp']}�@�@\r\n
	<a href=''>��</a>";
		echo "</ul>";
		echo "</article>";

	}

?>

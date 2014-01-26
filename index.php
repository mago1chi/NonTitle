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
	echo "<p id = \"category\">�ŋ߂̋L��</p>\r\n";
	/* DB�ɐڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link){
		die('DB�ւ̐ڑ��Ɏ��s�D');
	}
	/* �L���̗L�����m�F */
	$query = "select * from sqlite_master where type = 'table' and name = 'article'";
	$result = $link->query($query);
	if(!$result->fetch(PDO::FETCH_ASSOC)){
		echo "<li id = \"article\">\r\n";
		echo "�L��������܂���.\r\n";
		echo "</li>\r\n";
		echo "</section>\r\n";
	} else {
		/* �ŐV�L��3���̕\�� */
		$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
		where a.id=b.id order by a.id desc limit 4";
		/* �N�G������f�[�^�擾 */
		$result = $link->query($query);
		if(!$result)
			die('�N�G�������s���܂����D');
		/* �L���̕\�� */
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			/* �L���\���̃e���v�����Ăяo�� */
			require('./indexArticle.php');
		}
		echo "</section>\r\n";
		
		echo "<nav id = 'rightColumn'>\r\n";
		/* �v���t�B�[���̕\�� */
		echo "<div id = \"profile\">\r\n";
		$query = "select name, introduction from profile";
		$result = $link->query($query);
		if(!$result){
			echo '�v���t�B�[��������܂���D';
			echo '</div>';
		}
		else {
			$rows = $result->fetch(PDO::FETCH_ASSOC);
			/* �^�O���Ƃ̋L�������擾 */
			require('./writeProf.php');
		}
		
		/* �ŐV�R�����g�ꗗ�̕\�� */
		echo "<div id = 'comlist'>\r\n";
		$query = "select id, name from comments order by time desc limit 10";
		$result = $link->query($query);
		echo "<p id = 'com'>�ŋ߂̃R�����g�ꗗ</p>\r\n";
		echo "<ul id = 'coms'>\r\n";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			/* �R�����g�ꗗ�\���e���v���Ăяo�� */
			require('./writeCom.php');
		}
		echo "</ul>\r\n";
		echo "</div>\r\n";
		
		/* �N���ʃ����N�̕\�� */
		echo "<div id = \"ymcategory\">\r\n";
		$query = "select distinct year, month from article order by id desc";
		$result = $link->query($query);
		if(!$result)
			die('�N���ʃ����N�쐬�Ɏ��s�D');
		echo "<p id = \"ym\">�N���ʉߋ��̋L���ꗗ</p>\r\n";
		echo "<ul id = \"yms\">\r\n";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			require('./writeYM.php');
		}
		echo "</ul>\r\n";
		echo "</div>\r\n";
		
		/* �^�O�ʃ����N�̕\�� */
		echo "<div id = \"tagcategory\">\r\n";
		$query = "select distinct name from tag order by name";
		$result = $link->query($query);
		if(!$result)
			die('�^�O�ʃ����N�쐬�Ɏ��s�D');
		echo "<p id = \"tag\">�^�O�ʉߋ��̋L���ꗗ</p>\r\n";
		echo "<ul id = \"tags\">\r\n";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			require('./writeTag.php');
		}
		echo "</ul>\r\n";
		echo "</div>\r\n";
	}
?>

<p>�����N</p>
<ul>
<li><a href='http://www.hiroshima-u.ac.jp/index-j.html/'>�L����w</a></li>
<li><a href='http://www.se.hiroshima-u.ac.jp/'>���U�V�X�e���w</a></li>
</ul>
</nav>

<footer>
�A����<a href='mailto:shinjiro@se.hiroshima-u.ac.jp'>������</a>�܂ŁD<br>
Copyright (C) 2013 Shinjiro All Rights Reserved.
</footer>

</body>
</html>

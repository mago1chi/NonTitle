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
	
	/* �w��N���L���̈ꗗ���쐬���邽�߂̃e���v���[�g */
	/* �g�p����Ƃ��͏���̕�����u�� */
	
	/* URL����l�擾 */
	if(!isset($_GET['tag']))
		die("�^�O�ʃy�[�W�̕\���ɕK�v�ȃp�����[�^������܂���D");
	$tag = $_GET['tag'];
	if(!isset($_GET['num']))
		die("�^�O�ʃy�[�W�̕\���ɕK�v�ȃp�����[�^������܂���D");
	$num = $_GET['num'];
	if(!isset($_GET['tnum']))
		die("�^�O�ʃy�[�W�̕\���ɕK�v�ȃp�����[�^������܂���D");
	$tnum = $_GET['tnum'];
	/* DB�֐ڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link)
		die('DB�ւ̐ڑ��Ɏ��s');
	/* �e�[�u���ւ̃N�G���쐬 */
	if($tnum < 5){
		$query = "select a.id,a.year,a.month,a.title,a.content,a.timeStamp,b.name 
			from article as a, tag as b where a.id = b.id and 
			b.name = '$tag' order by a.id DESC";
		/* �N�G������f�[�^�擾 */
		$result = $link->query($query);
		if(!$result)
			die('�N�G�������s���܂����D');
		/* �L���̕\�� */
		echo "<p id = \"category\">�^�O�u".$tag."�v�̋L���ꗗ</p>";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			require('./indexArticle.php');
		}
	} else {
		/* �e�[�u���ւ̃N�G���쐬 */
		$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
		where a.id=b.id and b.name='$tag' order by a.id desc limit $num*5, 5";
		/* �N�G������f�[�^�擾 */
		$result = $link->query($query);
		if(!$result)
			die('�N�G�������s���܂����D');
		/* �L���̕\�� */
		echo "<p id = \"category\">�^�O�u".$tag."�v�̋L���ꗗ</p>";
		while($rows = $result->fetch(PDO::FETCH_ASSOC)){
			/* �L���\���̃e���v�����Ăяo�� */
			require('./indexArticle.php');
		}
		/* �y�[�W�������� */
		$pageNum = ceil($tnum / 5);
		echo "<center>";
		echo "<b>";
		if($num > 0)
			echo "<a href = './tagcategolize.php?tag=$tag&num=",$num-1,"&tnum=$tnum'>&lt&ltprev</a>�@";
		for($i = 0; $i < $pageNum; $i++){
			if($i == $pageNum-1){
				if($i == $num)
					echo $i+1;
				else
					echo "<a href = './tagcategolize.php?tag=$tag&num=$i&tnum=$tnum'>",$i+1,"</a>";
			}
			else{
				if($i == $num)
					echo $i+1,"�@";
				else
					echo "<a href = './tagcategolize.php?tag=$tag&num=$i&tnum=$tnum'>",$i+1,"</a>�@";
			}
		}
		if($num < $pageNum-1)
			echo "<a href = './tagcategolize.php?tag=$tag&num=",$num+1,"&tnum=$tnum'>�@next>></a>�@";
		echo "</b>";
		echo "</center>";
	}
	echo "</section>\r\n";
	
	echo "<nav id = 'rightColumn'>";
	/* �v���t�B�[���̕\�� */
	echo "<div id = \"profile\">\r\n";
	$query = "select name, introduction from profile";
	$result = $link->query($query);
	if(!$result){
		echo '�v���t�B�[��������܂���D';
		echo "</div>";
	}
	else {
		$rows = $result->fetch(PDO::FETCH_ASSOC);
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
	
	$link = null;
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

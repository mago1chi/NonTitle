<?php
	/* �摜�F�؂̂��߂ɃZ�b�V�����𗘗p */
	session_start();
	
	/* �摜�F�؂𗘗p���邽�߂̃\�[�X�R�[�h(securimage.php)��ǂݍ��� */
	include_once './securimage/securimage.php';
	$securimage = new Securimage();
	
	/* �V�K�R�����g���e�[�u���֒ǉ� */
	/* �R�����g���e�̗L�����m�F */
	if(isset($_POST['comment'])){
	
		/* �摜�F�؂����s */
		if ($securimage->check($_POST['captcha_code']) == false) {
		  echo "�F�؃R�[�h������������܂���D<br /><br />";
		  echo "<a href='javascript:history.go(-1)'>�߂�</a>";
		  exit();
		} else {
			/* �摜�F�؂��ʂ�΃R�����g��DB�֓o�^ */
			$id = $_GET['id'];
			$name = htmlspecialchars($_POST['name']);
			$comment = $_POST['comment'];
			/* �������ݎ������擾 */
			date_default_timezone_set('Asia/Tokyo');
			$time = getdate();
			$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
				$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
			$timeStamp = sprintf("%d-%02d-%02d %02d:%02d",
				$time['year'], $time['mon'], $time['mday'],
				$time['hours'], $time['minutes']);
			/* DB�ɐڑ� */
			$link = new pdo('sqlite:./DB/chilabo.db');
			/* �V�K�R�����g��}�� */
			$query = "insert into comments (id, time, name, comment, timeStamp)
				values ($id, $keyNum, '$name', '$comment', '$timeStamp')";
			$link->exec($query);
		}
	}
?>

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
	$id = $_GET['id'];
	echo "<section id = \"articles\">\r\n";
	
	/* DB�ɐڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	if(!$link){
		die('DB�ւ̐ڑ��Ɏ��s�D');
	}
	
	/* �w�肳�ꂽ�L���̕\�� */
	$query = "select a.id,a.title,a.content,a.timeStamp,b.name from article as a, tag as b 
	where a.id=b.id and a.id=$id";
	/* �N�G������f�[�^�擾 */
	$result = $link->query($query);
	$flag = true;
	if(!$rows = $result->fetch(PDO::FETCH_ASSOC)){
		echo '<br><center>���݂��Ȃ��L���ł��D<br><br>';
		/* HOME�ւ̃����N�\�� */
		echo "<a href='./index.php'>HOME</a></center>";
		$flag = false;
	}
	else {
		/* �L���̕\�� */
		/* �L���\���̃e���v�����Ăяo�� */
		require('./writeArticle.php');
		/* �O�̋L���ւ̃����N�\�� */
		echo "<center>";
		$query = "select id,title from article where id<$id order by id desc";
		$result = $link->query($query);
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		$title = mb_strimwidth($rows['title'], 0, 13, '�c', 'sjis');
		if($rows){
			echo "<a href='./each.php?id={$rows['id']}' title='{$rows['title']}'>&lt&lt$title</a>�@|";
		}
		/* HOME�ւ̃����N�\�� */
		echo "�@<a href='./index.php'>HOME</a>�@";
		/* ���̋L���ւ̃����N�\�� */
		$query= "select id,title from article where id>$id order by id";
		$result = $link->query($query);
		$rows = $result->fetch(PDO::FETCH_ASSOC);
		$title = mb_strimwidth($rows['title'], 0, 13, '�c', 'sjis');
		if($rows){
			echo "|�@<a href='./each.php?id={$rows['id']}' title='{$rows['title']}'>$title>></a>";
		}
	}
	echo "</center>";
	
	if($flag){
		/* �R�����g�@�\ */
		echo "<div id='comments'>";
		echo "<a name='comment'></a>";
		echo "<p id='comments'>�R�����g</p>\r\n";
		/* comments�e�[�u���̗L�����m�F */
		$query = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($query);
		if($result->fetch(PDO::FETCH_ASSOC)){
			/* ���Ƀe�[�u��������Γ��e��\�� */
			$query = "select name, comment, timeStamp from comments where id=$id order by time";
			$result = $link->query($query);
			while($rows = $result->fetch(PDO::FETCH_ASSOC)){
				$comment = nl2br(htmlspecialchars($rows['comment']));
				echo "<ul>\r\n";
				echo "<li id='name'>{$rows['name']}</li>\r\n";
				echo "<li id='comment'>$comment</li>\r\n";
				echo "<li id='timeStamp'>{$rows['timeStamp']}</li>\r\n";
				echo "</ul>\r\n";
			}
		}
		echo "<form method='post' action='./each.php?id=$id'>\r\n";
		echo "���O�F<br>";
		echo "<input type='text' name='name' size='30' value='����������'><br>\r\n";
		echo "�R�����g�F<br>";
		echo "<textarea name='comment' rows='20' cols='70'></textarea><br>\r\n";
		echo "<br>";
		echo "�摜�F�؁F<br>";
		echo '<img id="captcha" src="./securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" />';
		echo '<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById(\'captcha\').src = \'./securimage/securimage_show.php?sid=\' + Math.random(); this.blur(); return false">
		<img src="./securimage/images/refresh.png" alt="�摜�̍ēǂݍ���" onclick="this.blur()" align="bottom" border="0" width=20 hetigh=20></a><br>';
		echo '<input type="text" name="captcha_code" size="10" maxlength="6" /><br>';
		echo "<br>";
		echo "<input type='submit' value='���e'><br>\r\n";
		echo "</form>\r\n";
		echo "</div>\r\n";
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

<?php
	require('./sesFunc.php');
	seStart();
	
	if(!isset($_SESSION['logged_in'])){
		header('Location: ./login.php');
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>Title</title>
</head>

<body>

<br>

<?php
	date_default_timezone_set('Asia/Tokyo');
	$time = getdate();
	$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
		$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
	$timeStamp = sprintf("%d-%02d-%02d %02d:%02d",
		$time['year'], $time['mon'], $time['mday'],
		$time['hours'], $time['minutes']);
	/* �L���̏��� */
	@$title = $_POST["title"];
	@$article = $_POST["article"];
	/* ���e���̋K������ */
	if(!strcmp($title, "")){
		print("�^�C�g�����L�����Ă�������.");
		exit();
	}
	if(!strcmp($article, "")){
		print("���e�������Ă�������.");
		exit();
	}
	/* �摜�t�@�C���̃T�C�Y�A�g���q�𔻒� */
	$upFile = $_FILES['imgUpload'];
	for($i = 0; $i < 10; $i++){
		if($upFile['name'][$i] != "" && ($upFile['size'][$i] > 1000000 || !require('extDetect.php'))){
			die('�t�@�C���T�C�Y��1MB�ȏォ�A�������̓t�@�C�����A�g���q�Ɉᔽ������܂��D');
		}
	}
	
	try{
		/* DB�ڑ� */
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!$link)
			die('DB�ւ̐ڑ��Ɏ��s�D');
		
		/* �o�b�N�A�b�v�t�@�C���̍쐬 */
		copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
		
		/* �L���Ǘ��e�[�u���̊m�F */
		$sql = "select * from sqlite_master where type='table' and name='article'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = "create table article (id int, year int, month int, day int, title text, content text, timeStamp text)";
			$result = $link->exec($sql);
		}
		/* �^�C���X�^���v�Ǘ��e�[�u���̊m�F */
		$sql = "select * from sqlite_master where type='table' and name='time'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = "create table time (id int, hour int, minute int, second int)";
			$result = $link->exec($sql);
		}
		
		/* �e�[�u��article�֐V�K�L���̑}�� */
		$sql = "insert into article (id, year, month, day, title, content, timeStamp) 
			values ($keyNum, $time[year], $time[mon], $time[mday], '$title', '$article', '$timeStamp')";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('�V�K�L���̑}���Ɏ��s�D');
		}
		/* �e�[�u��time�փ^�C���X�^���v��}�� */
		$sql = "insert into time (id, hour, minute, second) 
			values ($keyNum, $time[hours], $time[minutes], $time[seconds])";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('�V�K�L���̃^�C���X�^���v�G���[�D');
		}
		
		/* �^�O�̏��� */
		$tag = htmlspecialchars($_POST["tag"]);
		/* �^�O�p�e�[�u���̊m�F */
		$sql = "select * from sqlite_master where type='table' and name='tag'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = 'create table tag (name text, id int)';
			$result = $link->exec($sql);
			if(!$result)
				die('tag�e�[�u���̍쐬�Ɏ��s�D');
		}
		/* �^�O�f�[�^�̑}�� */
		$sql = "insert into tag (name, id) values ('$tag', $keyNum)";
		$flag = $link->exec($sql);
		if(!$flag){
			copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
			die('�^�O�f�[�^�X�V�Ɏ��s�D');
		}
		
		/* commnet�e�[�u����������΍쐬 */
		$query = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($query);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$query = "create table comments (id int, time int, name text, comment text, timeStamp text)";
			$link->exec($query);
		}
		
		/* �摜�t�@�C���p�e�[�u���̊m�F */
		$sql = "select * from sqlite_master where type='table' and name='img'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$sql = 'create table img (id int, path text)';
			$result = $link->exec($sql);
		}
		/* �摜���A�b�v���[�h����Ă��邩���m�F */
		$imgFlag = false;
		for($i = 0; $i < 10; $i++){
			if($upFile['name'][$i] != ""){
				$imgFlag = true;
				break;
			}
		}
		if($imgFlag){
			for($i = 0; $i < 10; $i++){
				if($upFile['name'][$i] != ""){
					$upPath = "./img/{$upFile['name'][$i]}";
					$fileName[] = $upFile['name'][$i];
					move_uploaded_file($upFile['tmp_name'][$i], $upPath);
				}
			}
			/* �摜�t�@�C���̃p�X���e�[�u���֓o�^ */
			for($i = 0; $i < count($fileName); $i++){
				$sql = "insert into img (id, path) values ($keyNum, '$fileName[$i]')";
				if(!$link->exec($sql)){
					copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
					die('�摜�̃A�b�v���[�h�Ɏ��s�D');
				}
			}
		}
		
		/* RSS�t�B�[�h�̐��� */
		require('./writeRSS.php');
		
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	print("�L���̐V�K�ǉ����������܂���.<br>");
?>

�߂�Ƃ���<a href = "./articleManage.php">������</a>����
</body>
</html>
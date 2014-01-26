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
	/* �ύX���ꂽ�l���擾 */
	@$title = $_POST["title"];
	@$article = $_POST["article"];
	$tag = htmlspecialchars($_POST["tag"]);
	/* �ύX���ꂽ�L����ID���擾 */
	$id = $_GET['id'];
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
	/* DB�̃o�b�N�A�b�v�쐬 */
	copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
	/* DB�֐ڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	$sql = "update article set title='$title', content='$article' where id=$id";
	if(!$link->exec($sql)){
		copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
		die('�L���̍X�V�Ɏ��s');
	}
	$sql = "update tag set name='$tag' where id=$id";
	if(!$link->exec($sql)){
		copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
		die('�L���̍X�V�Ɏ��s');
	}
	/* �摜�t�@�C���p�e�[�u���̊m�F */
	$sql = "select * from sqlite_master where type='table' and name='img'";
	$result = $link->query($sql);
	if(!$result->fetch(PDO::FETCH_ASSOC)){
		$sql = 'create table img (id int, path text)';
		$result = $link->exec($sql);
		if(!$result)
			die('img�e�[�u���̍쐬�Ɏ��s');
	}
	/* �摜���A�b�v���[�h����Ă��邩���m�F */
	$imgFlag = false;
	for($i = 0; $i < 10; $i++){
		if($upFile['name'][$i] != ""){
			$imgFlag = true;
			break;
		}
	}
	/* �摜���A�b�v���[�h����Ă���ꍇ */
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
			$sql = "insert into img (id, path) values ($id, '$fileName[$i]')";
			if(!$link->exec($sql)){
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die('�摜�̃A�b�v���[�h�Ɏ��s�D');
			}
		}
	}
	$link = null;
?>

�L���̍X�V�������D<br>
�߂�Ƃ���<a href = './articleManage.php'>������</a>����D

</body>
</html>

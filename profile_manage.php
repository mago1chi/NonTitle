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
	/* �t�H�[������̓��͂��擾 */
	@$name = $_POST["name"];
	@$profile = nl2br($_POST["profile"]);
	try {
		/* DB�֐ڑ� */
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!$link)
			print('DB�ւ̐ڑ��Ɏ��s�D');
		/* �e�[�u��profile�̊m�F */
		$query = "select * from sqlite_master where type='table' and name='profile'";
		$result = $link->query($query);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			$query = "create table profile (id int, name text, introduction text)";
			$result = $link->exec($query);
			/* �e�[�u��profile�Ƀv���t�B�[����}�� */
			$query = "insert into profile (id, name, introduction) values (1, '$name', '$profile')";
			$flag = $link->exec($query);
			if(!$flag)
				die('�v���t�B�[���X�V�Ɏ��s�D');
			echo "�v���t�B�[���X�V�ɐ����I<br>";
		} else {			
			/* �e�[�u��profile�Ƀv���t�B�[����}�� */
			$query = "update profile set name='$name', introduction='$profile' where id=1";
			$flag = $link->exec($query);
			if(!$flag)
				die('�v���t�B�[���X�V�Ɏ��s�D');
			echo "�v���t�B�[���X�V�ɐ����I<br>";
		}
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
?>
�߂�Ƃ���<a href = "./articleManage.php">������</a>����
</body>
</html>
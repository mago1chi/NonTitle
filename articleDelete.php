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
	/* DB�̃o�b�N�A�b�v���쐬 */
	copy('./DB/chilabo.db', './DB/backup/chilabo.db.bak');
	if($_POST['check'] == ""){
		echo "�폜����L���Ƀ`�F�b�N���A�I�����Ă��������D";
		exit();
	}
	$check = $_POST['check'];
	/* DB�֐ڑ� */
	try{
		$link = new pdo('sqlite:./DB/chilabo.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* �`�F�b�N���ꂽ�L���̍폜���J��Ԃ� */
		for($i = 0; $i < count($check); $i++){
			$id = $check[$i];
			/* �L���̍폜 */
			$sql = "delete from article where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "�L���ԍ��F$id<br>\r\n";
				echo "�̍폜�Ɏ��s\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "delete from time where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "�L���ԍ��F$id<br>\r\n";
				echo "�̍폜�Ɏ��s\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "delete from tag where id={$check[$i]}";
			if(!$link->exec($sql)){
				echo "<font color='red'>\r\n";
				echo "�L���ԍ��F$id<br>\r\n";
				echo "�̍폜�Ɏ��s\r\n";
				echo "</font>";
				copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
				die();
			}
			$sql = "select count(*) from img where id={$check[$i]}";
			$count = $link->query($sql);
			$num = $count->fetchColumn();
			if($num > 0){
				$sql = "delete from img where id={$check[$i]}";
				if(!$link->exec($sql)){
					echo "<font color='red'>\r\n";
					echo "�L���ԍ��F$id<br>\r\n";
					echo "�̍폜�Ɏ��s\r\n";
					echo "</font>";
					copy('./DB/backup/chilabo.db.bak', './DB/chilabo.db');
					die();
				}
			}
			echo "�L���ԍ��F$id<br>\r\n";
			echo "���폜�D<br>\r\n";
			echo "<br>\r\n";
		}
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	echo "�w�肳�ꂽ�L���̍폜�������D<br>\r\n";
?>

�߂�Ƃ���<a href = './articleManage.php'>������</a>����D

</body>
</html>

<html>
<head><title></title></head>
<body>
<?php
	if(!isset($_POST['name']) || !isset($_POST['comment']))
		die('�s���ȃp�����[�^');
		
	$name = htmlspecialchars($_POST["name"]);
	$comment = nl2br(htmlspecialchars($_POST['comment']));
	date_default_timezone_set('Asia/Tokyo');
	$weekday = array("��", "��", "��", "��", "��", "��", "�y");
	$time = getdate();
	$number = date("w");
	$keyNum = sprintf("%d%02d%02d%02d%02d%02d", $time['year'], $time['mon'],
		$time['mday'], $time['hours'], $time['minutes'], $time['seconds']);
	$timeStamp = sprintf("%d-%02d-%02d�i{$weekday[$number]}�j %02d:%02d:%02d",
		$time['year'], $time['mon'], $time['mday'],
		$time['hours'], $time['minutes'], $time['seconds']);
	try{
		/* DB�֐ڑ� */
		$link = new pdo('sqlite:./DB/board.db');
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* �e�[�u��comments�̊m�F */
		$sql = "select * from sqlite_master where type='table' and name='comments'";
		$result = $link->query($sql);
		if(!$result->fetch(PDO::FETCH_ASSOC)){
			/* �e�[�u���������ꍇ�쐬���� */
			$sql = "create table comments (id int, name text, comment text, time text)";
			$link->exec($sql);
		}
		/* �V�����f�[�^���e�[�u���֑}�� */
		$sql = "insert into comments (id, name, comment, time)
			values ($keyNum, '$name', '$comment', '$timeStamp')";
		$link->exec($sql);
	} catch(PDOException $e) {
		echo $e->getMessage();
		exit();
	}
	$link = null;
	print("�������݂ɐ������܂���.<br>");
?>
�߂�Ƃ���<a href = "./keiji.php">������</a>����
</body>
</html>
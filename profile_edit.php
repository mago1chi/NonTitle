<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>Title</title>
</head>

<body>

<br>

<?php
	/* �t�H�[������̓��͂��擾 */
	$name = htmlspecialchars($_POST["name"]);
	$profile = nl2br(htmlspecialchars($_POST["profile"]));
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* �e�[�u��profile�Ƀv���t�B�[����}�� */
	$query = "update profile set name='$name', introduction='$profile' where id=1";
	$flag = $link->exec($query);
	if(!$flag)
		die('�v���t�B�[���X�V�Ɏ��s�D');
	echo "�v���t�B�[���X�V�ɐ����I<br>";
?>
�߂�Ƃ���<a href = "./articleManage.php">������</a>����
</body>
</html>
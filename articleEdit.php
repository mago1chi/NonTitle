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
	/* URL����l���擾 */
	$id = $_GET['id'];
	/* DB�֐ڑ� */
	$link = new pdo('sqlite:./DB/chilabo.db');
	/* �e�[�u������^�C�g���A���e�A�^�O���擾 */
	$sql = "select a.title,a.content,b.name from article as a, tag as b 
		where a.id=b.id and a.id = $id";
	if(!$result = $link->query($sql))
		die('�f�[�^�̎擾�Ɏ��s�D');
	$rows = $result->fetch(PDO::FETCH_ASSOC);
	$content = $rows['content'];
	echo "<form method = 'POST' action = './tableEdit.php?id=$id'
		enctype='multipart/form-data'>\r\n";
	echo "�^�C�g���F<br>\r\n";
	echo "<input type='text' name='title' size='50' value='{$rows['title']}'><br>\r\n";
	echo "<br>\r\n";
	echo "<br>\r\n";
	echo "�L�����e�F<br>\r\n";
	echo "<textarea name='article' rows='30' cols='100'>$content</textarea><br>\r\n";
	echo "���{���̉��s��&ltbr /&gt���g���K�v����D�iRSS�t�B�[�h�̃G���[��������邽�߁j";
	echo "<br>\r\n";
	echo "<br>\r\n";
	echo "�^�O�F<br>\r\n";
	echo "<input type='text' name='tag' size='20' value='{$rows['name']}'><br>\r\n";
	echo "<br>\r\n";
	echo "�摜�̃A�b�v���[�h�F<br>\r\n";
	echo "���t�@�C���T�C�Y��500kb�ȉ��܂ŉD�g���q���ujpg�Apng�v�̉摜�̂�OK�D<br>
		�܂��t�@�C�����͉p�����̂݁D<br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<input type='file' name='imgUpload[]'><br>\r\n";
	echo "<br>\r\n";
	echo "<input type='submit' value='�ҏW����'>\r\n";
	echo "</form>";
	$link = null;
?>

</body>
</html>

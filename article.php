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

<form method="post" action="diary_manage.php"  enctype='multipart/form-data'>
�^�C�g���F<br>
<input type="text" name="title" size="50"><br>
<br>
<br>
�L�����e�F<br>
<textarea name="article" rows="30" cols="100"></textarea><br>
���{���̉��s��&ltbr /&gt���g���K�v����D�iRSS�t�B�[�h�̃G���[��������邽�߁j<br>
<br>
<br>
�^�O�F<br>
<input type="text" name="tag" size="20"><br>
<br>
�摜�̃A�b�v���[�h�F<br>
���t�@�C���T�C�Y��1MB�ȉ��܂ŉD�g���q���ujpg�Apng�v�̉摜�̂�OK�D<br>
�܂��t�@�C�����͉p�����̂݁D<br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<input type='file' name='imgUpload[]'><br>
<br>
<input type="submit" value="���e">
</form>
</body>
</html>
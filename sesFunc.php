<?php
	/* �Z�b�V�����̊J�n */
	function seStart()
	{
		session_name('MANAGE');
		session_set_cookie_params(1800);
		session_start();
		/* �Z�b�V�����Œ�U���΍� */
		if(!isset($_SESSION['initialize'])){
			session_regenerate_id(true);
			$_SESSION['initialize'] = true;
		}
	}
	/* �Z�b�V�����j�� */
	function endSes()
	{
		/* �Z�b�V�����ϐ��̏����� */
		$_SESSION = array();
		/* Cookie�폜 */
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time()-42000, '/');
		}
		/* �Z�b�V�����t�@�C�����폜 */
		session_destroy();
	}
?>
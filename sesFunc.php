<?php
	/* セッションの開始 */
	function seStart()
	{
		session_name('MANAGE');
		session_set_cookie_params(1800);
		session_start();
		/* セッション固定攻撃対策 */
		if(!isset($_SESSION['initialize'])){
			session_regenerate_id(true);
			$_SESSION['initialize'] = true;
		}
	}
	/* セッション破棄 */
	function endSes()
	{
		/* セッション変数の初期化 */
		$_SESSION = array();
		/* Cookie削除 */
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time()-42000, '/');
		}
		/* セッションファイルを削除 */
		session_destroy();
	}
?>
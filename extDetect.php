<?php
$name = $upFile['name'][$i];
$name = strtoupper($name);
$ext = array('JPG', 'JPEG', 'PNG');
foreach($ext as $value){
	$topName = basename($name, $value);
	if(strlen($topName . $value) == strlen($name)){
		return true;
	}
}
return false;
?>
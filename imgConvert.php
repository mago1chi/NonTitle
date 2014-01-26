<?php
	$imgSize = 400;
	$sql = "select path from img where id=$id";
	$imgRes = $link->query($sql);
	if($imgRes){
		while($imgPath = $imgRes->fetch(PDO::FETCH_ASSOC)){
			$longPath = "./img/{$imgPath['path']}";
			list($width, $height) = getimagesize($longPath);
			$height = round($height * $imgSize / $width);
			$content = str_replace("img:{$imgPath['path']}",
				"<a href='$longPath' target='_blank'><img width='$imgSize' height='$height' src='$longPath'></a>", $content);
		}
	}
	return $content;
?>

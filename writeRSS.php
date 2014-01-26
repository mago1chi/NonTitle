<?php
/* RSS�t�B�[�h�����e���v�� */

/* �t�@�C���̓��e���擾 */
$pastContent = file_get_contents('./chilabo.xml');
/* <channle>�^�O���̓��e�̂ݒ��o */
preg_match("!</language>(.*?)</channel>!s", $pastContent, $extract);

$content = $article;
$id = $keyNum;

/* �摜�\���p�̏��� */
$imgSize = 400;
$sql = "select path from img where id=$id";
$imgRes = $link->query($sql);
if($imgRes){
	while($imgPath = $imgRes->fetch(PDO::FETCH_ASSOC)){
		$longPath = "./img/{$imgPath['path']}";
		list($width, $height) = getimagesize($longPath);
		$height = round($height * $imgSize / $width);
		$content = str_replace("img:{$imgPath['path']}",
			"<a href='$longPath' target='_blank'><img width='$imgSize' height='$height' src='$longPath'></img></a>", $content);
	}
}

/* �X�V���Ԃ�RSS2.0�̌`���Ŏ擾 */
$pubDate = gmdate('D, j M Y H:i:s \+0900');

if(preg_match_all("/\n/", $content, $matchRes) > 15){
	preg_match("/(.*\n){10}/", $content, $shortC);

	/* RSS�t�B�[�h�Ƃ��ĐV���ɏ������ޓ��e��$str�֊i�[ */
	$str = "<?xml version='1.0' encoding='Shift_JIS'?>\r\n
<rss version='2.0'>\r\n
<channel>\r\n"
."<title>Non Title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/</link>\r\n
<description>�C�ɂȂ����L���₿�痠�Ȃ��Ƃ̏�������</description>\r\n
<lastBuildDate>$pubDate</lastBuildDate>\r\n
<language>ja</language>\r\n\r\n
<item>\r\n
<title>$title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/each.php?id=$id</link>\r\n
<description>$shortC[0]...<br /><a href='./each.php?id=$id'>������ǂ�</a></description>\r\n
<pubDate>$pubDate</pubDate>\r\n
</item>\r\n"
.$extract[1]
."</channel>\r\n
</rss>";
	file_put_contents('./chilabo.xml', $str);
} else {
	/* RSS�t�B�[�h�Ƃ��ĐV���ɏ������ޓ��e��$str�֊i�[ */
	$str = "<?xml version='1.0' encoding='Shift_JIS'?>\r\n
<rss version='2.0'>\r\n
<channel>\r\n"
."<title>Non Title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/</link>\r\n
<description>�C�ɂȂ����L���₿�痠�Ȃ��Ƃ̏�������</description>\r\n
<lastBuildDate>$pubDate</lastBuildDate>\r\n
<language>ja</language>\r\n\r\n
<item>\r\n
<title>$title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/each.php?id=$id</link>\r\n
<description>$content</description>\r\n
<pubDate>$pubDate</pubDate>\r\n
</item>\r\n"
.$extract[1]
."</channel>\r\n
</rss>";
	file_put_contents('./chilabo.xml', $str);
}
?>

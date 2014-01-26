<?php
/* RSSフィード生成テンプレ */

/* ファイルの内容を取得 */
$pastContent = file_get_contents('./chilabo.xml');
/* <channle>タグ内の内容のみ抽出 */
preg_match("!</language>(.*?)</channel>!s", $pastContent, $extract);

$content = $article;
$id = $keyNum;

/* 画像表示用の処理 */
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

/* 更新時間をRSS2.0の形式で取得 */
$pubDate = gmdate('D, j M Y H:i:s \+0900');

if(preg_match_all("/\n/", $content, $matchRes) > 15){
	preg_match("/(.*\n){10}/", $content, $shortC);

	/* RSSフィードとして新たに書き込む内容を$strへ格納 */
	$str = "<?xml version='1.0' encoding='Shift_JIS'?>\r\n
<rss version='2.0'>\r\n
<channel>\r\n"
."<title>Non Title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/</link>\r\n
<description>気になった記事やちら裏なことの書き溜め</description>\r\n
<lastBuildDate>$pubDate</lastBuildDate>\r\n
<language>ja</language>\r\n\r\n
<item>\r\n
<title>$title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/each.php?id=$id</link>\r\n
<description>$shortC[0]...<br /><a href='./each.php?id=$id'>続きを読む</a></description>\r\n
<pubDate>$pubDate</pubDate>\r\n
</item>\r\n"
.$extract[1]
."</channel>\r\n
</rss>";
	file_put_contents('./chilabo.xml', $str);
} else {
	/* RSSフィードとして新たに書き込む内容を$strへ格納 */
	$str = "<?xml version='1.0' encoding='Shift_JIS'?>\r\n
<rss version='2.0'>\r\n
<channel>\r\n"
."<title>Non Title</title>\r\n
<link>http://www.se.hiroshima-u.ac.jp/~shinjiro/</link>\r\n
<description>気になった記事やちら裏なことの書き溜め</description>\r\n
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

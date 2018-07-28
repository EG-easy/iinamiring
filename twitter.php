<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel= "stylesheet" type="text/css" href="twitter.css">
	<link rel="shortcut icon" href="./img/favicon.png" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>誰でもリング</title>
</head>

<body>

<div class="wrap">

	<div class="container">#誰でもリングが完成しました！</div>

	<div class ="showimage">
		<p><?php
		require "func.php";
		if($_SERVER["REQUEST_METHOD"] === 'POST' && !empty($_POST['params_a']) && !empty($_POST['radio'])){

			define('DATABASE_NAME','iinami_db');
			define('DATABASE_USER','dbuser01');
			define('DATABASE_PASSWORD','iinami');
			define('DATABASE_HOST','localhost');

			define('PDO_DSN','mysql:dbname=' . DATABASE_NAME .';host=' . DATABASE_HOST . '; charset=utf8');

			$now = new DateTime();
				// 設定
			$dir = 'user_pictures/' . $now->format('Y/m/d');
			$width2 = 400;
			$height2 = 400;
				$api_key = 'hw9RYRWXJrgpxDpjWJEx685gs' ;		// APIキー
				$api_secret = 'Xnm8TYtcytYUgkCPaAwezEK58aG2s4vwB8lYx6rX5l1s15o5Fo' ;		// APIシークレット
				$access_token = '891590595432423429-yKLleh014H4Q4Tjvz8L8P5otk4ZgSrm' ;		// アクセストークン
				$access_token_secret = 'ueqQYmgl7I4crCIyuSA02rcJLDPfTrzMDciAdugsRFUSh' ;		// アクセストークンシークレット
				$path = changeTwitterImage($dir, $width2, $height2, $api_key, $api_secret, $access_token, $access_token_secret);
			}else{
				// リダイレクト先のURLへ転送する
				$url = 'https://iinamiring.com/badrequests/emptyid.html';
				header('Location: ' . $url, true, 301);

				// すべての出力を終了
				exit;

			}?>
		</p>
    <br>
		<br>
	<a class="button" href="<?php echo $path;?>" download >写真を保存</a>
</div>

	 <p class="note">
		 <span>スマホの場合</span>
		 <br>
		 ①保存をタップ
		 <br>
		 ②長押しで保存
	 </p>

<br>


<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="#誰でもリング を作りました！みんなで誰でもリングを作って夏を越えよう！" data-url="https://iinamiring.com" data-lang="ja" data-show-count="false">
	Tweet
</a>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<br>
<br>

<div class="member">
 <p>作った人をフォローする</p>
 <a href="https://twitter.com/inoueyosui" class="member_name">@inoueyosui</a>
 <a href="https://twitter.com/____easy" class="member_name">@____easy</a>
 <a href="https://twitter.com/Azzzstar" class="member_name">@Azzzstar</a>
</div>

</div>


</body>


</html>

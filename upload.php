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



	<div class="container">#誰でもリングが完成しました！</div>

    <div class = "showimage">
        <p><?php
            require "func.php";
            if($_SERVER["REQUEST_METHOD"] === 'POST' && !empty($_FILES['image']['tmp_name']) && !empty($_POST['radio'])){
                $now = new DateTime();

                // リサイズする画像のサイズを指定
                $width2 = 400;
                $height2 = 400;

                // 一時ファイルの場所
                $tmpName = $_FILES['image']['tmp_name'];

                // 保存先のディレクトリ
                $dir = 'user_pictures/' . $now->format('Y/m/d');
                $path = uploadImage($tmpName, $dir, $width2, $height2);
            }else{
                // リダイレクト先のURLへ転送する
                $url = 'https://iinamiring.com/404.html';
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
				 ①ボタンをタップ
				 <br>
				 ②長押しで保存
			 </p>

<br>

		<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="#誰でもリング を作りました！みんなでリングを作って夏を越えよう！" data-url="https://iinamiring.com" data-hashtags="#誰でもリング" data-lang="ja" data-show-count="false">
			Tweet
		</a>

				<br>
				<br>

				<div class="member">
				 <p>作った人をフォローする</p>
				 <a href="https://twitter.com/inoueyosui" class="member_name">@inoueyosui</a>
				 <a href="https://twitter.com/____easy" class="member_name">@____easy</a>
				 <a href="https://twitter.com/Azzzstar" class="member_name">@Azzzstar</a>
				</div>

					<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


</body>
</html>

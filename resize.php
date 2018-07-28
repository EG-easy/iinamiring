<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>iinami</title>
</head>
<body>
    <div class = showimage>
        <p><?php
            require "func.php";
            if($_SERVER["REQUEST_METHOD"] === 'POST' && !empty($_FILES['image']['tmp_name'])){
                $now = new DateTime();

                // リサイズする画像のサイズを指定
                $width2 = 400;
                $height2 = 400;
 
                // 一時ファイルの場所
                $tmpName = $_FILES['image']['tmp_name'];
 
                // 保存先のディレクトリ
                $dir = 'files/' . $now->format('Y/m/d');
                $path = uploadImage($tmpName, $dir, $width2, $height2);

            }?>
        </p>
    </div>  
    <a href="<?php echo $path;?>" download >ダウンロード</a>
</body>
</html>

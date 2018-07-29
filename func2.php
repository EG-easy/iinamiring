<?php 
function uploadImage($tmpName, $dir, $width2, $height2){

    //MIMEタイプという画像の種類を関数を使って取得
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpName);

    //加工前のファイルをフォーマット別に読み出す
    if($mime == 'image/jpeg' || $mime == 'image/pjpeg'){
        $ext = '.jpg';
        $image1 = imagecreatefromjpeg($tmpName);
    } elseif($mime == 'image/png' || $mime == 'image/x-png'){
        $ext = '.png';
        $image1 = imagecreatefrompng($tmpName);
    } elseif($mime == 'image/gif'){
        $ext = '.gif';
        $image1 = imagecreatefromgif($tmpName);
    } else {
        // リダイレクト先のURLへ転送する
        $url = 'https://iinamiring.com/badrequests/wrongformat.html';
        header('Location: ' . $url, true, 301);

        // すべての出力を終了
        exit;
    }

    // 加工前の画像の情報を取得
    list($width1, $height1) = getimagesize($tmpName);

    // 新しくリサイズする画像を作成
    $image2 =imagecreatetruecolor($width2, $height2);
 
    //グレースケールを利用する場合は以下のコードを実行する。
    // imagecopyresampled($image2, $image1, 0, 0, 0, 0, $width2, $height2, $width1, $height1);

    // if($image2 && imagefilter($image2, IMG_FILTER_GRAYSCALE)){
    //     echo '変換成功';
    //     imagepng($image2, 'sample_IMG_FILTER_GRAYSCALE.png');
    // }else{
    //     echo '変換失敗';
    // }

    // 青緑にする用の画像を作成
    $image3 = imagecreatetruecolor($width2, $height2);
 
    imagecopyresampled($image3, $image1, 0, 0, 0, 0, $width2, $height2, $width1, $height1);

    if($image3 && imagefilter($image3, IMG_FILTER_COLORIZE, 0, 255, 255)){
    //PNG イメージを青緑にしてブラウザまたはファイルに出力する
    echo 'IMG_FILTER_COLORIZE変換成功';
    //第２引数は、ファイルの保存先のパス
    imagepng($image3, 'sample_COLORIZE.png');
    //画像を破棄する（保持するメモリを解放）
    } else {
    echo 'IMG_FILTER_COLORIZE変換失敗';
    }

    // 赤にするようの画像を作成
    $image4 = imagecreatetruecolor($width2, $height2);
    imagecopyresampled($image4, $image1, 0, 0, 0, 0, $width2, $height2, $width1, $height1);

    if($image4 && imagefilter($image4, IMG_FILTER_COLORIZE, 255, 0, 0)){
    //PNG イメージを赤くしてブラウザまたはファイルに出力する
    echo 'IMG_FILTER_COLORIZE変換成功';
    //第２引数は、ファイルの保存先のパス
    imagepng($image4, 'sample_COLORIZE.png');
    //画像を破棄する（保持するメモリを解放）
    } else {
    echo 'IMG_FILTER_COLORIZE変換失敗';
    }

    //青緑にした画像を、ちょっとずらして透明にして重ねる
    imagecopymerge($image2, $image3, -8, -8, 0, 0, 400, 400, 50);
    imagedestroy($image3);

    //赤にした画像を、ちょっとずらして透明にして重ねる
    imagecopymerge($image2, $image4, 8, 8, 0, 0, 400, 400, 50);
    imagedestroy($image4);


    //コピーする画像2枚目を取得(黄色)
    $designImage = "./img/frame".$_POST['radio'].".png";
    $frame_im = imagecreatefrompng($designImage);
    //出力先の画像に貼り付け
    //この時、dst_x(貼り付け先のx座標)に200を指定することで、貼り付け位置を下にずらす
    imagecopy($image2, $frame_im, 0, 0, 0, 0, 400, 400);
    imagedestroy($frame_im);

    // imagepng( $image2, "final.png"); //PNG画像で保存する場合

    if(!file_exists($dir)){
        mkdir($dir, 0777, true);
    }

    $filename = sha1(microtime() . $_SERVER['REMOTE_ADDR'] . $tmpName) . $ext;
    $saveTo = rtrim($dir, '/\\') . '/' . $filename;
 
    if($ext == '.jpg'){
        $quality = 80;
        imagejpeg($image2, $saveTo, $quality);
    } else if($ext == '.png'){
        imagepng($image2, $saveTo);
    } else if($ext == '.gif'){
        imagegif($image2, $saveTo);
    }

    // print("<img src=\"{$saveTo}\">");
    echo "<img src =\"$saveTo\">";
    
    // 読み出したファイルは消去
    imagedestroy($image1);
    imagedestroy($image2);

    return $saveTo;
}

function changeTwitterImage($dir, $width2, $height2, $api_key, $api_secret, $access_token, $access_token_secret){
    
    $request_url = 'https://api.twitter.com/1.1/users/show.json' ;      // エンドポイント
    $request_method = 'GET' ;

    // パラメータA (オプション)
    $params_a = array(
        // "user_id" => "1528352858",
        "screen_name" => $_POST["params_a"],
//      "include_entities" => "true",
    ) ;

    // キーを作成する (URLエンコードする)
    $signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret ) ;

    // パラメータB (署名の材料用)
    $params_b = array(
        'oauth_token' => $access_token ,
        'oauth_consumer_key' => $api_key ,
        'oauth_signature_method' => 'HMAC-SHA1' ,
        'oauth_timestamp' => time() ,
        'oauth_nonce' => microtime() ,
        'oauth_version' => '1.0' ,
    ) ;

    // パラメータAとパラメータBを合成してパラメータCを作る
    $params_c = array_merge( $params_a , $params_b ) ;

    // 連想配列をアルファベット順に並び替える
    ksort( $params_c ) ;

    // パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
    $request_params = http_build_query( $params_c , '' , '&' ) ;

    // 一部の文字列をフォロー
    $request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params ) ;

    // 変換した文字列をURLエンコードする
    $request_params = rawurlencode( $request_params ) ;

    // リクエストメソッドをURLエンコードする
    // ここでは、URL末尾の[?]以下は付けないこと
    $encoded_request_method = rawurlencode( $request_method ) ;
 
    // リクエストURLをURLエンコードする
    $encoded_request_url = rawurlencode( $request_url ) ;
 
    // リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
    $signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params ;

    // キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
    $hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE ) ;

    // base64エンコードして、署名[$signature]が完成する
    $signature = base64_encode( $hash ) ;

    // パラメータの連想配列、[$params]に、作成した署名を加える
    $params_c['oauth_signature'] = $signature ;

    // パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
    $header_params = http_build_query( $params_c , '' , ',' ) ;

    // リクエスト用のコンテキスト
    $context = array(
        'http' => array(
            'method' => $request_method , // リクエストメソッド
            'header' => array(            // ヘッダー
                'Authorization: OAuth ' . $header_params ,
            ) ,
        ) ,
    ) ;

    // パラメータがある場合、URLの末尾に追加
    if( $params_a ) {
        $request_url .= '?' . http_build_query( $params_a ) ;
    }

    // cURLを使ってリクエスト
    $curl = curl_init() ;
    curl_setopt( $curl, CURLOPT_URL , $request_url ) ;
    curl_setopt( $curl, CURLOPT_HEADER, 1 ) ; 
    curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $context['http']['method'] ) ;  // メソッド
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;  // 証明書の検証を行わない
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;   // curl_execの結果を文字列で返す
    curl_setopt( $curl, CURLOPT_HTTPHEADER , $context['http']['header'] ) ; // ヘッダー

    curl_setopt( $curl , CURLOPT_TIMEOUT , 5 ) ;    // タイムアウトの秒数
    $res1 = curl_exec( $curl ) ;
    $res2 = curl_getinfo( $curl ) ;
    curl_close( $curl ) ;

    // 取得したデータ
    $json = substr( $res1, $res2['header_size'] ) ;     // 取得したデータ(JSONなど)
    $header = substr( $res1, 0, $res2['header_size'] ) ;    // レスポンスヘッダー (検証に利用したい場合にどうぞ)

    // JSONをオブジェクトに変換
    $obj = json_decode( $json, true);
    // 画像のURLを代入
    $normal_url = $obj["profile_image_url"]; 
    //画像サイズの調整
    $square_url = str_replace( "_normal.", "_400x400.", $normal_url ) ;

    //画像ファイルデータを取得
    $img_data = file_get_contents($square_url);
    //MIMEタイプの取得
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_buffer($finfo, $img_data);
    finfo_close($finfo);

        //加工前のファイルをフォーマット別に読み出す
    if($mime == 'image/jpeg' || $mime == 'image/pjpeg'){
        $ext = '.jpg';
        $image1 = imagecreatefromjpeg($square_url);
    } elseif($mime == 'image/png' || $mime == 'image/x-png'){
        $ext = '.png';
        $image1 = imagecreatefrompng($square_url);
    } elseif($mime == 'image/gif'){
        $ext = '.gif';
        $image1 = imagecreatefromgif($square_url);
    } else {
        // リダイレクト先のURLへ転送する
                $url = 'https://iinamiring.com/badrequests/wrongid.html';
                header('Location: ' . $url, true, 301);

                // すべての出力を終了
                exit;
    }

    // 加工前の画像の情報を取得
    list($width1, $height1) = getimagesize($square_url);
    // 新しくリサイズする画像を作成

    $image2 = imagecreatetruecolor($width2, $height2);
 
    imagecopyresampled($image2, $image1, 0, 0, 0, 0, $width2, $height2, $width1, $height1);

    //コピーする画像2枚目を取得(黄色)
    $designImage = "./img/frame".$_POST['radio'].".png";
    $frame_im = imagecreatefrompng($designImage);
    //出力先の画像に貼り付け
    //この時、dst_x(貼り付け先のx座標)に200を指定することで、貼り付け位置を下にずらす
    imagecopy($image2, $frame_im, 0, 0, 0, 0, 400, 400);
    imagedestroy($frame_im);

    if(!file_exists($dir)){
        mkdir($dir, 0777, true);
    }

    $filename = sha1(microtime() . $_SERVER['REMOTE_ADDR']) . $ext;
    $saveTo = rtrim($dir, '/\\') . '/' . $filename;
 
    if($ext == '.jpg'){
        $quality = 80;
        imagejpeg($image2, $saveTo, $quality);
    } else if($ext == '.png'){
        imagepng($image2, $saveTo);
    } else if($ext == '.gif'){
        imagegif($image2, $saveTo);
    }

    #データベースに登録
    $action = new getFormAction();
    $action->saveDbPostData($_POST);

    // print("<img src=\"{$saveTo}\">");
    echo "<img src =\"$saveTo\">";
    
    // 読み出したファイルは消去
    imagedestroy($image1);
    imagedestroy($image2);

    return $saveTo;
}

class getFormAction {
    public $pdo;

    /**
     * コネクション確保
     */
    function __construct() {
        try {
            $this->pdo = new PDO( PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
        } catch (PDOException $e) {
            echo 'error' . $e->getMessage();
            die();
        }
    }
    /**
     * 記事データをDBに保存
     */
    function saveDbPostData($data){

        // データの保存
        $smt = $this->pdo->prepare('insert into users (TwitterID) values(:TwitterID)');
        $smt->bindParam(':TwitterID',$data['params_a'], PDO::PARAM_STR);
        $smt->execute();
    }
}


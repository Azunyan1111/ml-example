<html>
<head>
    <title>PHP Request</title>
</head>
<body>
<?php
    // マルチログインのURL
    $url = "http://localhost:8040";
    // APIリクエストパラメーター 実際にリクエストを送るURLは「http://localhost:8040/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555」
    $param = "/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555";
    // サービス登録後にマイページに表示される36文字のトークンとシークレット
    $token = "your key";
	$secret = "your secret";
    // リクエスト送信時のUnix時間
    $timeStr = $_SERVER['REQUEST_TIME'];

    // ヘッダーの'authorization'に付ける文字列。別の情報になる時はカンマで区切る
    $authorization = "";
    // ML独自の認証方式と宣言する
    $authorization .= "MLAuth1.0" . ",";
    // トークンを記述する
    $authorization .= "token=" . $token . ",";
    // Unix時間を記述する
    $authorization .= "time=" . $timeStr . ",";

    // パラメーターURLとUnix時間をカンマで繋げた文字列を作成
    $join = $param . "," . $timeStr;
    // hashを生成
    $hash = hash_hmac('sha256', $join, $secret);

    // 生成したhashを記述する
    $authorization .= "signature=" . $hash . ",";

    // 実際に送る
    $header = array(
        "Authorization: " . $authorization,
    );
    echo $authorization;
    $conn = curl_init(); // cURLセッションの初期化
    curl_setopt($conn, CURLOPT_URL, $url.$param); //　取得するURLを指定
    curl_setopt($conn, CURLOPT_RETURNTRANSFER, true); // 実行結果を文字列で返す。
    curl_setopt($conn, CURLOPT_HTTPHEADER, $header);
    $res =  curl_exec($conn);
    var_dump($res);
    curl_close($conn); //セッションの終了

echo $res;
?>
</body>
</html>
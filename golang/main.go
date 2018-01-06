package main

import (
	"crypto/hmac"
	"crypto/sha256"
	"encoding/hex"
	"fmt"
	"io/ioutil"
	"net/http"
	"strconv"
	"time"
)

func main() {

	// マルチログインのURL
	url := "http://localhost:8040"
	// APIリクエストパラメーター 実際にリクエストを送るURLは「http://localhost:8040/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555」
	param := "/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555"
	// サービス登録後にマイページに表示される36文字のトークンとシークレット
	token := "your token"
	secret := "your secret"
	// リクエスト送信時のUnix時間
	timeStr := strconv.FormatInt(time.Now().Unix(), 10)

	// ヘッダーの'authorization'に付ける文字列。別の情報になる時はカンマで区切る
	var authorization string
	// ML独自の認証方式と宣言する
	authorization += "MLAuth1.0" + ","
	// トークンを記述する
	authorization += "token=" + token + ","
	// Unix時間を記述する
	authorization += "time=" + timeStr + ","

	// パラメーターURLとUnix時間をカンマで繋げた文字列を作成
	join := param + "," + timeStr
	// hashを生成
	mac := hmac.New(sha256.New, []byte(secret))
	mac.Write([]byte(join))
	hash := hex.EncodeToString(mac.Sum(nil))

	// 生成したhashを記述する
	authorization += "signature=" + hash + ","

	// 実際に送る
	req, _ := http.NewRequest("GET", url+param, nil)
	req.Header.Set("Authorization", authorization)

	client := new(http.Client)
	resp, err := client.Do(req)
	if err != nil {
		panic(err)
	}
	byteArray, _ := ioutil.ReadAll(resp.Body)
	fmt.Println(string(byteArray)) // htmlをstringで取得
}

# -*- coding: utf-8 -*-

import hashlib
import hmac
import urllib2
from datetime import datetime

if __name__ == '__main__':
    # ヘッダーの'authorization'に付ける文字列。別の情報になる時はカンマで区切る
    authorization = ""
    # マルチログインのURL
    url = "http://localhost:8040"
    # APIリクエストパラメーター実際にリクエストを送るURLは「http:#localhost:8040/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555」
    param = "/api/user/name?uuid=26d2983e-3d5a-421c-bf6f-d4608025e555"
    # サービス登録後にマイページに表示される36文字のトークンとシークレット
    token = "your token"
    secret = "your secret"
    # 	# リクエスト送信時のUnix時間
    timeStr = datetime.now().strftime('%s')

    # ML独自の認証方式と宣言する
    authorization += "MLAuth1.0" + ","
    # トークンを記述する
    authorization += "token=" + token + ","
    # Unix時間を記述する
    authorization += "time=" + timeStr + ","

    # パラメーターURLとUnix時間をカンマで繋げた文字列を作成
    join = param + "," + timeStr
    # hashを生成
    hash = hmac.new(secret, join, hashlib.sha256)
    # 生成したhashを記述する
    authorization += "signature=" + hash.hexdigest() + ","

    req = urllib2.Request(url + param)
    # ヘッダ設定
    req.add_header('authorization', authorization)

    res = urllib2.urlopen(req)
    r = res.read()
    print r

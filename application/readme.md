# concrete5 Redis Override Sample

(October 11, 2018)

日本語はこの後に / Japanese instruction followed by English

## Introduction

This is the sample override of Redis cache for concrete5.

Due to the nature of concrete5 structure, you must override application. Packaging is impossible as of concrete5 8.4.3.

## Set-up Redis

Please set-up your Redis instances on AWS or other server.
Have the redis endpoint ready.

We're skipping the detail configuration of Redis server. However, Redis on AWS ElastiCache is running without any setting modification.

## Installation Steps

1. Download all the files on this Git.
1. Open `application/config/concrete.php` 
1. Change whereever it says `<redis_server>` to
 your redis server endpoint.
1. Upload those files onto your concrete5
1. Clear cache and now your concrete5 should be using Redis to store block and full-page cache and session.


## File list

There are 7 files that you need modify and upload.

### Files needs to modify

Make sure not to override your own setting and add the following settings. Make sure to replace `<redis_server>` to your actual redis server.

- application/config/concrete.php

### FIle may need to modify and replace

For app.php, make sure you have the line added on the top of PHP code.
For autoload.php, make sure that you have `$classLoader` part.

- application/bootstrap/app.php
- application/bootstrap/autoload.php

### Files which are just needed to be uploaded

You will just need to upload these files onto the proper location.

- application/src/Concrete/Cache/Page/RedisPageCache.php
- application/src/Redis/Driver/Redis.php
- application/src/Redis/Session/RedisSessionHandler.php
- application/src/Redis/Session/SessionFactory.php

## はじめに

これは concrete5 で Redis を使うためのサンプルオーバーライドです。

concrete5 の特性からパッケージ化は難しく、application のオーバーライドのみ可能となります。


## Redis のセットアップ

まず、 Redis サーバーをセットアップしてください。AWS や他のサービスで開始できます。
エンドポイントを記録してください。

細かい設定は飛ばします。ただし ElastiCache の Redis では、デフォルト設定のままで動作しました。 (2018年10月現在)

## インストールの手順

1. この Git から全てのファイルをダウンロード
1. `application/config/concrete.php` を開く
1. `<redis_server>` と書かれているところを実際の Redis エンドポイント変更
1. ファイルを concrete5 アップロード
1. concrete5 のキャッシュをクリアすると Redis を利用し始めます。


## ファイル一覧

このパッケージには7つのファイルがあります。

### 修正が必ず必要なファイル

もしも独自の設定がされていたら、上書きされないように気をつけて設定を追加してください。 `<redis_server>` を必ず自分の Redis サーバーに書き換えてください。

- application/config/concrete.php

### アップロードするだけだが場合によっては修正が必要なファイル

app.php について：他にもコードが有る場合、必ずこの行が最初に来るようにしてください。

- application/bootstrap/app.php
- application/bootstrap/autoload.php

### アップロードするだけで良いファイル

これらのファイルは下記の場所にアップロードするだけでOKです。

- application/src/Concrete/Cache/Page/RedisPageCache.php
- application/src/Redis/Driver/Redis.php
- application/src/Redis/Session/RedisSessionHandler.php
- application/src/Redis/Session/SessionFactory.php


(c) 2018 concrete5 Japan, Inc.
(c) 2018 コンクリートファイブジャパン株式会社
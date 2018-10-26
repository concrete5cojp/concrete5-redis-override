# concrete5 Redis Override Sample

(October 12, 2018)

日本語はこの後に / Japanese instruction followed by English

## Introduction

This is the sample override of Redis cache for concrete5. This version only works and tested with PHP version 7.x.

Due to the nature of concrete5 structure, you must override application. Packaging is impossible as of concrete5 8.4.3.

These Redis implementation may be included into concrete5 in the future. We're currently discussing with concrete5 Core team.

## Agreement

By downloading the code, you agree to use this code "AS-IS". concrete5 Japan, Inc. hold no responsibility whatsoever for any damages and payment caused by using this Redis overrides.

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

#### Optional Concrete.php Settings

- 'prefix'
    - You can set a key prefix here for each cache/session level (not_required)
- 'database' - (INTEGER)
    - Select which redis database you want to use, useful for keeping the cache's/session seperatly if you do not use a prefix.

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

これは concrete5 で Redis を使うためのサンプルオーバーライドです。PHP 7.x での動作確認をしています。

concrete5 の特性からパッケージ化は難しく、application のオーバーライドのみ可能となります。

このオーバーライドは、将来 concrete5 本体に取り込まれる可能性があります。

## 免責

このオーバーライドを使って発生したいかなる損害について、コンクリートファイブジャパン株式会社は補償いたしません。

このコードをダウンロードされた方はこのコードが無保証であると同意していただいたとみなします。

## Redis のセットアップ

まず、 Redis サーバーをセットアップしてください。AWS や他のサービスで開始できます。
エンドポイントを記録してください。

細かい設定は飛ばします。ただし ElastiCache の Redis では、デフォルト設定のままで動作しました。 (2018年10月現在)

## インストールの手順

1. この Git から全てのファイルをダウンロード
1. `application/config/concrete.php` を開く
1. `<redis_server>` と書かれているところを実際の Redis エンドポイントに変更
1. ファイルを concrete5 アップロード
1. Redis が使われ始めます。場合によっては concrete5 のキャッシュをクリアが必要です。


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
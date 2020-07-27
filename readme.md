# concrete5 Redis Config Sample

(July 20, 2020)

日本語はこの後に / Japanese instruction followed by English

## Introduction

This is the sample override of Redis cache config for concrete5. This version only works and tested with PHP version 7.2 and 7.3.

If you are looking for application overrides of Redis for concrete5 8.4.3, please check [8.4.3-legacy Branch](https://github.com/concrete5cojp/concrete5-redis-override/tree/8.4.3-legacy).

## Agreement

By downloading the code, you agree to use this code "AS-IS". concrete5 Japan, Inc. hold no responsibility whatsoever for any damages and payment caused by using this Redis overrides.

## Set-up Redis

Please set-up your Redis instances on AWS or other server.
Have the redis endpoint ready.

We're skipping the detail configuration of Redis server. However, Redis on AWS ElastiCache is running without any setting modification.

### Setting up

## Installation Steps

1. Download all the files on this Git.
1. Open `application/config/concrete.php` 
1. Change whereever it says `<redis_server>` to
 your redis server endpoint.
1. Add additional settings and config files if necessary.
1. Upload those files onto your concrete5
1. Clear cache and now your concrete5 should be using Redis to store block and full-page cache and session.

#### File to modify (required)

Make sure not to add the following config &

- application/config/concrete.php

#### Optional: concrete.php Redis Settings

- 'prefix'
    - You can set a key prefix here for each cache/session level (not_required)
- 'database' - (INTEGER)
    - Select which redis database you want to use, useful for keeping the cache's/session separately if you do not use a prefix.

### Optional: ENV setting for config variation & Site setting

You can set different environment and load different config files by adding prefix to config files, so that you can keep using one git repo and etc.

- application/bootstrap/start.php

site.php saves site name, tracking code and CKEditor's settings. You may want to lock down in code for multiple web server environments.

- application/config/site.php

#### [CAUTION] Generated Overrides

concrete5 saves some of config setting under /application/config/generated_overrides/*. However, if you are using one (1) concrete5 installs using multiple web servers, you faced the problem that concrete5 only saves a generated override config file onto one of the many web servers.

Currently, you have the following options

- 1. Don't use generated_overrides & Don't change config over dashboard: Don't change any setting via concrete5 Dashboard. Make sure that you set config under /application/config/
     - I've uploaded the sample parameters to `concrete.php` and `site.php` under `application/config`.
- 2. Lsync config: Make sure that site admin ONLY log in to one server, then set-up lsync to sync generated overrides to another server.
     - This readme doesn't include how-to. We've done this way in the past.


#### WIP: Redis support of generated overrides

The following PR explains how to store generated overrides parameters onto Redis, so that we won't have to worry about generated overrides. I will add the detail descruption once I finished testing.

https://github.com/concrete5/concrete5/pull/8397

## はじめに

これは concrete5 で Redis を使うためのサンプルオーバーライドです。PHP 7.2 & 7.3 での動作確認をしています。

concrete5 8.4.3 の application オーバーライドを使った Redis の設定方法をお探しの方は [8.4.3-legacy ブランチ](https://github.com/concrete5cojp/concrete5-redis-override/tree/8.4.3-legacy) を確認ください。

## 免責

このオーバーライドを使って発生したいかなる損害について、コンクリートファイブジャパン株式会社は補償いたしません。

このコードをダウンロードされた方はこのコードが無保証であると同意していただいたとみなします。

## Redis のセットアップ

まず、 Redis サーバーをセットアップしてください。AWS や他のサービスで開始できます。
エンドポイントを記録してください。

細かい設定は飛ばします。ただし ElastiCache の Redis では、デフォルト設定のままで動作しました。 (2020年7月現在)

## 設定方法

1. この Git から全てのファイルをダウンロード
1. `application/config/concrete.php` を開く
1. `<redis_server>` と書かれているところを実際の Redis エンドポイントに変更
1. その他必要な設定を変更。
1. ファイルを concrete5 アップロード
1. Redis が使われ始めます。場合によっては concrete5 のキャッシュをクリアが必要です。



### 修正が必ず必要なファイル

もしも独自の設定がされていたら、上書きされないように気をつけて設定を追加してください。 `<redis_server>` を必ず自分の Redis サーバーに書き換えてください。

- application/config/concrete.php

#### オプション: concrete.php での Redis 設定

- 'prefix'
    - key prefix を設定して別々のキーとして保存可能です
- 'database' - (整数)
    - Redis に搭載しているどのデータベースを利用するかを設定できます。prefix と使い分けることが可能です。


### オプション: 別々の環境変数を設定したい場合 & サイト設定

環境によって、別々の config ファイルを読み込める ENV 変数を設定したり、

- application/bootstrap/start.php

site.php にサイト名や、トラッキングコード、CK Editor などの設定が保存されます。

- application/config/site.php

#### [注意] Generated Overrides の設定ファイル

concrete5 では一部のサイト設定をテキストファイルとして /application/config/generated_overrides/* に保存します。しかし、concrete5 を、複数サーバーに冗長化させて運用していると問題が発生します。管理画面からの設定変更は、1台の Web サーバー内にしか設定が保存されません。

今、現在、下記のオプションがあります。

- 1. generated_overrides の設定を使わない ＆ 管理画面から設定を変更しない。その代わり config 設定ファイルを /application/config/ の直下に保存する。
     -  `application/config` 配下にある `concrete.php` と `site.php` にサンプルの値を入れておきました。
- 2. Config を Lsync で同期：サイト管理者は1台の Web サーバーだけにログインして、Lsync を設定し残りのサーバーに generated override の設定ファイルを動悸するようにする。
     - この readme では記載していませんが、過去に実施した経験があります。


#### WIP: Redis が generated overrides をサポート。

concrete5 では、generated_overrides の設定情報を Redis 内に保存できるようになりました。

まだ試していないので、試してからこのドキュメントを書き直します。

https://github.com/concrete5/concrete5/pull/8397


(c) 2018 concrete5 Japan, Inc.
(c) 2018 コンクリートファイブジャパン株式会社

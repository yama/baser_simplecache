# baser_simplecache

複雑な設定を持たないシンプルなページキャッシュです。ログイン時・投稿時はキャッシュしません。

アンインストールする場合は、アンインストール後にindex.phpの１行目の
```
include '/path/to/baser/app/Plugin/SimpleCache/cache-driver.php';
```
を削除してください。

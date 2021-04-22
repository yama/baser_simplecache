# SimpleCacheプラグイン

## 特長

- POSTのたびにキャッシュをクリア
- [条件付きget機能](https://www.google.com/search?q=php+%E6%9D%A1%E4%BB%B6%E4%BB%98%E3%81%8Dget)を設定可能。デフォルトで有効

## Installation

1. app/Plugin/ フォルダの中にSimpleCacheフォルダを転送する
2. 管理画面でインストールする

## Uninstall

管理画面でアンインストールする

## Settings

デフォルトで条件付きgetが有効になっています。Config/setting.php で設定を無効にできます。

## Description

下記の条件に該当するページをキャッシュします。

- 非ログイン時
  - $_POSTが空
    - ページ内にdata[_Token][key]という文字列を含まない
      -  PagesControllerに紐づくページ(固定ページ)
      -  BlogControllerに紐づくページ(ブログ)
      -  ContentFoldersControllerに紐づくページ(フォルダ)

キャッシュファイルは {APP_DIR}/tmp/cache/simplecache/ フォルダの中に生成します。

※詳細については SimpleCacheControllerEventListener.php を参照してください。

管理画面にログイン中に何らかの更新操作($_POSTの有無で判定)を行なうとキャッシュをクリアします。ログインするだけでもクリアされます。

## TODO

- RSSフィードの巡回結果を定期的にパースするページなどを想定してexpire設定の実装を検討
- 設定画面を作りたい

## Thanks

- [https://basercms.net](http://basercms.net/)
- [https://wiki.basercms.net/](http://wiki.basercms.net/)
- [https://cakephp.org](https://cakephp.org)
- [Cake Development Corporation](https://cakedc.com)
- [DerEuroMark](https://www.dereuromark.de/)

## License
The source code is licensed MIT.


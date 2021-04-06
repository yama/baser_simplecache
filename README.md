# SimpleCacheプラグイン

## 特長

- POSTのたびにキャッシュをクリア
- [条件付きget機能](https://www.google.com/search?q=php+%E6%9D%A1%E4%BB%B6%E4%BB%98%E3%81%8Dget)を設定可能。デフォルトで有効

## インストール

1. app/Plugin/ フォルダの中にSimpleCacheフォルダを転送する
2. 管理画面でインストールする

## アンインストール

管理画面でアンインストールする

## 設定

デフォルトで条件付きgetが有効になっています。Config/setting.php で設定を無効にできます。

## 解説

下記の条件に該当するページをキャッシュします。

- 非ログイン時
  - $_POSTが空
    - ページ内にdata[_Token][key]という文字列を含まない
      -  PagesControllerに紐づくページ(固定ページ)
      -  BlogControllerに紐づくページ(ブログ)
      -  ContentFoldersControllerに紐づくページ(フォルダ)

※詳細については SimpleCacheControllerEventListener.php を参照してください。

管理画面にログイン中に何らかの更新操作($_POSTの有無で判定)を行なうとキャッシュをクリアします。ログインするだけでもクリアされます。

## todo

- 100KB以上に及ぶページは自動的にgzip圧縮転送


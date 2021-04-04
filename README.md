# SimpleCacheプラグイン

## 特長

- 副作用が起きる可能性が低い、シンプルなページキャッシュ
- POSTのたびにキャッシュをクリア
- [条件付きget機能](https://www.google.com/search?q=php+%E6%9D%A1%E4%BB%B6%E4%BB%98%E3%81%8Dget)を設定可能。デフォルトで有効

## インストール

1. app/Plugin/ フォルダの中にSimpleCacheフォルダを転送する
2. 管理画面でインストールする

## アンインストール

管理画面でアンインストールする

## 設定

デフォルトで条件付きgetが有効になっています。Config/setting.php で設定を無効にできます。<br>
条件付きgetはサーバ・クライアント間の二度目以降の転送量をゼロにできるため非常に高速ですが、ページに紐づくリソース(cssやjsなど)も更新なしとされるため、リソースレベルの変更をブラウザに伝えることができません。これが不便な場合は設定を無効にしてください。

## 解説

下記の条件に該当するページをキャッシュします。

- 非ログイン時
  - $_POSTが空
    - ページ内にdata[_Token][key]という文字列を含まない
      -  PagesControllerに紐づくページ(固定ページ)
      -  BlogControllerに紐づくページ(ブログ)
      -  ContentFoldersControllerに紐づくページ(フォルダ)

※詳細については SimpleCacheControllerEventListener.php を参照してください。

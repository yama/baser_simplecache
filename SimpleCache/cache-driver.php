<?php

include_once __DIR__ . '/preload.functions.inc.php';

define('SimpleCacheInstalled', true); // index.phpのフックを確認するための目印

if(is_logged_in() || !is_file(cache_dir() . cache_filename())) {
	return;
}

readfile(cache_dir() . cache_filename());

exit; // キャッシュを出力したらbaserCMSを立ち上げずに終了

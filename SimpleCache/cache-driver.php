<?php

include_once __DIR__ . '/preload.functions.inc.php';

define('SimpleCacheInstalled', true); // index.phpのフックを確認するための目印

if(sc_is_logged_in() || !is_file(sc_cache_filepath())) {
	return;
}

include_once __DIR__ . '/Config/setting.php';
if (!empty($config['SimpleCache']['cget'])) {
	sc_conditional_get(filemtime(sc_cache_filepath()));
}

readfile(sc_cache_filepath());

exit; // キャッシュを出力したらbaserCMSを立ち上げずに終了

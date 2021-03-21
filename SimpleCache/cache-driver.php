<?php
define('RapidCache', true);
$cache_path = dirname(dirname(__DIR__)).'/tmp/cache/rapidcache/';
if(!is_dir($cache_path)) {
	mkdir($cache_path);
}

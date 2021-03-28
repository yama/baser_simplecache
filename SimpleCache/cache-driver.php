<?php
$base_memory = memory_get_usage();
include_once __DIR__ . '/functions.inc.php';

define('SimpleCacheInstalled', true);

if(!is_logged_in() && is_file(cache_dir() . cache_filename())) {
	readfile(cache_dir() . cache_filename());
	exit;
}

register_shutdown_function('save_cache');
ob_start();


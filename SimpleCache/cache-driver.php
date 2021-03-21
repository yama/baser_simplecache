<?php

define('SimpleCacheInstalled', true);
include 'basics.php';

if(!is_dir(cache_path())) {
	mkdir(cache_path());
}

<?php
function cache_dir() {
	return dirname(dirname(__DIR__)).'/tmp/cache/simplecache/';
}

function cache_filename() {
	return md5($_SERVER['REQUEST_URI']).'.txt';
}

function is_logged_in() {
	if(!isset($_COOKIE['BASER_LOGGED_IN'])) {
		return false;
	}
	return true;
}

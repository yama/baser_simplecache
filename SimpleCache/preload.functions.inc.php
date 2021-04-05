<?php
function sc_cache_filepath() {
	return sc_cache_dir() . sc_cache_filename();
}

function sc_cache_dir() {
	return dirname(dirname(__DIR__)).'/tmp/cache/simplecache/';
}

function sc_cache_filename() {
	return md5($_SERVER['REQUEST_URI']).'.txt';
}

function sc_is_logged_in() {
	if(!isset($_COOKIE['BASER_LOGGED_IN'])) {
		return false;
	}
	return true;
}

function sc_conditional_get($timestamp) {
	if (!$timestamp) {
		return;
	}
	$etag = sprintf('"%s"', md5($timestamp));
	header('ETag: '.$etag);
	if (filter_input( INPUT_SERVER, 'HTTP_IF_NONE_MATCH') !== $etag) {
		return;
	}
	header('HTTP', true, 304);
	header('Content-Length: 0');
	exit;
}

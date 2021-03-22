<?php
function cache_dir() {
	return dirname(dirname(__DIR__)).'/tmp/cache/simplecache/';
}

function cache_filename() {
	return md5($_SERVER['REQUEST_URI']).'.html';
}

function save_cache() {
	if(!empty($_SESSION['Auth'])) {
		if(!empty($_POST)) {
			array_map('unlink', glob(cache_dir() . '*.html'));
		}
		return;
	}
	if(strpos($_SERVER['REQUEST_URI'], '.js') !== false) {
		file_put_contents(cache_dir().'log.txt', $_SERVER['REQUEST_URI'], FILE_APPEND);
		return;
	}

	$response = ob_get_contents();
	if(strpos($response, 'data[_Token][key]') !== false) {
		return;
	}
	if(!is_dir(cache_dir())) {
		mkdir(cache_dir());
	}
	file_put_contents(cache_dir() . cache_filename(), $response);
}

function mod_indexphp() {
	define('SimpleCacheInstalled', true);
	return file_put_contents(
		WWW_ROOT . 'index.php',
		preg_replace(
			'@^<\?php@',
			sprintf(
				"<?php\ninclude '%s/cache-driver.php';",
				str_replace(
					'\\',
					'/',
					__DIR__
				)
			),
			file_get_contents(WWW_ROOT . 'index.php')
		)
	);
}

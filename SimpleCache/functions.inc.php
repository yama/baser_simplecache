<?php
function cache_dir() {
	return dirname(dirname(__DIR__)).'/tmp/cache/simplecache/';
}

function cache_filename() {
	return md5($_SERVER['REQUEST_URI']).'.html';
}

function is_logged_in() {
	if(!isset($_COOKIE['BASER_LOGGED_IN'])) {
		return false;
	}
	return true;
}

function save_cache() {
	if(!empty($_SESSION['Auth'])) {
		if(!empty($_POST)) {
			array_map('unlink', glob(cache_dir() . '*.*'));
			file_put_contents(
				cache_dir() . 'touch.php',
				sprintf(
					"<?php\necho '%s';\n",
					date('Y-m-d H:i:s')
				)
			);
		}
		return;
	}

	if (Configure::read('debug') > 0) {
		return;
	}
	if(strpos($_SERVER['REQUEST_URI'], '.js') !== false) {
		file_put_contents(cache_dir().'log.txt', $_SERVER['REQUEST_URI'], FILE_APPEND);
		return;
	}

	$response = ob_get_contents();
	if(strpos($response, '<') === false) {
		file_put_contents(cache_dir().'log.txt', $_SERVER['REQUEST_URI']."\n", FILE_APPEND);
		return;
	}
	if(strpos($response, 'data[_Token][key]') !== false) {
		return;
	}
	if(!is_dir(cache_dir())) {
		mkdir(cache_dir());
	}
	file_put_contents(cache_dir() . cache_filename(), $response);
}

function mod_indexphp() {
	return file_put_contents(
		WWW_ROOT . 'index.php',
		preg_replace(
			'@^<\?php@',
			sprintf(
				"<?php\ninclude_once '%s/cache-driver.php';",
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

if (!function_exists('str_ends_with')) {
	function str_ends_with($haystack, $needle)
	{
		return $needle === '' || ($haystack !== '' && substr_compare($haystack, $needle, -\strlen($needle))===0);
	}
}

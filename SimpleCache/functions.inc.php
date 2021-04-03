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

function save_cache() {
	if(!empty($_SESSION['Auth'])) {
		if(!empty($_POST)) {
			array_map('unlink', glob(cache_dir() . '*.*'));
			touch_cache();
		}
		return;
	}

	if (!is_cacheable_action()) {
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
	touch_cache();
}

function touch_cache() {
	if(!is_file(cache_dir() . 'touch.php')) {
		file_put_contents(
			cache_dir() . 'touch.php',
			sprintf(
				"<?php\necho '%s';\n",
				date('Y-m-d H:i:s')
			)
		);
}
}

function is_cacheable_action() {
	$route = Router::parse(env('REQUEST_URI'));
	if ($route['plugin'] === 'blog') {
		if ($route['controller'] === 'blog') {
			return true;
		}
		if ($route['action'] === 'blog_comments_scripts.js') {
			return true;
		}
		return false;
	}
	if ($route['controller'] === 'pages') {
		return true;
	}
	if ($route['controller'] === 'content_folders') {
		return true;
	}
	return false;
}

if (!function_exists('str_ends_with')) {
	function str_ends_with($haystack, $needle)
	{
		return $needle === '' || ($haystack !== '' && substr_compare($haystack, $needle, -\strlen($needle))===0);
	}
}

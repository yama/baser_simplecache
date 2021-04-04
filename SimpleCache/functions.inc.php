<?php
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

function setLoginCookie() {
	return setcookie(
		'BASER_LOGGED_IN',
		'loggedin',
		time()+60*60*24*180,
		baseUrl()
	);
}

function unSetLoginCookie() {
	return setcookie(
		'BASER_LOGGED_IN',
		'',
		time()-3600, baseUrl()
	);
}

function modify_indexphp() {
	if(!is_file(WWW_ROOT . 'index.php')) {
		return false;
	}
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

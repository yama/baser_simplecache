<?php

if(!defined('SimpleCacheInstalled') && is_file(WWW_ROOT . 'index.php')) {
	file_put_contents(
		WWW_ROOT . 'index.php',
		preg_replace(
			'@^<\?php@',
			sprintf(
				"<?php\ninclude '%s/cache-driver.php';",
				str_replace(
					'\\',
					'/',
					dirname(__DIR__)
				)
			),
			file_get_contents(WWW_ROOT . 'index.php')
		)
	);
}

$cache_path = dirname(dirname(dirname(__DIR__))).'/tmp/cache/simple/';
if(!is_dir($cache_path)) {
	mkdir($cache_path);
}

<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

if(!defined('RapidCache') && is_file(WWW_ROOT . 'index.php')) {
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

$cache_path = dirname(dirname(dirname(__DIR__))).'/tmp/cache/rapidcache/';
if(!is_dir($cache_path)) {
	mkdir($cache_path);
}

<?php
class SimpleCacheControllerEventListener extends BcControllerEventListener {

	public $events = [
		'initialize'
		, 'shutdown'
	];

	public function initialize(CakeEvent $event) {
		if(!function_exists('cache_dir')) {
			include_once dirname(__DIR__) . '/preload.functions.inc.php';
		}

		if (BcUtil::loginUserName()) {
			$controller = $event->subject();
			if ($this->isUninstallAction($controller->request)) {
				$this->unSetLoginCookie();
				$this->purgeCache();
				$this->uninstall();
				return;
			}
			if(!empty($_POST)) {
				$this->purgeCache();
				$this->touch_cache();
				return;
			}
			if (empty($_COOKIE['BASER_LOGGED_IN'])) {
				$this->setLoginCookie();
			}
		} else {
			if (!empty($_COOKIE['BASER_LOGGED_IN'])) {
				$this->unSetLoginCookie();
			}
		}
		if (!defined('SimpleCacheInstalled')) {
			$this->modify_indexphp();
		}
	}

	public function shutdown(CakeEvent $event) {
		$controller = $event->subject();
		if (!$this->is_cacheable_action($controller->request)) {
			return;
		}
		$body = $controller->response->body();
		if(strpos($body, 'data[_Token][key]') !== false) {
			return;
		}
		if(!is_dir(cache_dir())) {
			mkdir(cache_dir());
		}
		file_put_contents(cache_dir() . cache_filename(), $body);
		$this->touch_cache();

	}

	private function purgeCache() {
		array_map('unlink', glob(cache_dir() . '*.*'));
	}

	private function isUninstallAction($request) {
		if ($request->controller !== 'plugins') {
			return false;
		}
		if ($request->action !=='admin_ajax_delete') {
			return false;
		}
		if ($request->param('pass.0') !== 'SimpleCache') {
			return false;
		}
		return true;
	}

	private function uninstall() {
		file_put_contents(
			WWW_ROOT . 'index.php',
			preg_replace(
				'@^.*include_once .+app/Plugin/SimpleCache/cache-driver\.php.+$\n@m',
				'',
				file_get_contents(WWW_ROOT . 'index.php')
			)
		);
	}

	private function is_cacheable_action($request) {
		if ($request->plugin === 'blog') {
			if ($request->controller === 'blog') {
				return true;
			}
			if ($request->action === 'blog_comments_scripts.js') {
				return true;
			}
			return false;
		}
		if ($request->controller === 'pages') {
			return true;
		}
		if ($request->controller === 'content_folders') {
			return true;
		}
		return false;
	}

	private function touch_cache() {
		if(!is_file(cache_dir() . 'touch.php')) {
			file_put_contents(
				cache_dir() . 'touch.php',
				sprintf(
					"<?php\necho '%s';\n",
					date('Y-m-d H:i:s', filemtime(__FILE__))
				)
			);
		}
	}

	private function setLoginCookie() {
		return setcookie(
			'BASER_LOGGED_IN',
			'loggedin',
			time()+60*60*24*180,
			baseUrl()
		);
	}

	private function unSetLoginCookie() {
		return setcookie(
			'BASER_LOGGED_IN',
			'',
			time()-3600, baseUrl()
		);
	}

	private function modify_indexphp() {
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
						dirname(__DIR__)
					)
				),
				file_get_contents(WWW_ROOT . 'index.php')
			)
		);
	}
}

<?php
class SimpleCacheControllerEventListener extends BcControllerEventListener {

	public $events = [
		'initialize'
		, 'shutdown'
	];

	public function initialize(CakeEvent $event) {
		if (BcUtil::loginUserName()) {
			$controller = $event->subject();
			if ($this->isUninstallAction($controller->request)) {
				$this->unSetLoginCookie();
				$this->purgeCache();
				$this->uninstall();
				return;
			}
			if(!empty($_POST) || $this->isClearCacheAction($controller->request)) {
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
		if (BcUtil::loginUserName()) {
			return;
		}
		$controller = $event->subject();
		if (!$this->isCacheableAction($controller->request)) {
			return;
		}
		$body = $controller->response->body();
		if(strpos($body, 'data[_Token][key]') !== false) {
			return;
		}
		if(!is_dir(sc_cache_dir())) {
			mkdir(sc_cache_dir());
		}
		file_put_contents(sc_cache_filepath(), $body);
		$this->setEtag(sc_cache_filepath());
		$this->touch_cache();

	}

	private function purgeCache() {
		array_map('unlink', glob(sc_cache_dir() . '*.*'));
	}

	private function setEtag($file_path) {
		if (!Configure::read('SimpleCache.cget')) {
			return;
		}
		header(sprintf('ETag: "%s"', md5(filemtime($file_path))));
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

	private function isCacheableAction($request) {
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

	private function isClearCacheAction($request) {
		if ($request->controller !== 'site_configs') {
			return false;
		}
		if ($request->action !== 'admin_del_cache') {
			return false;
		}
		return true;
	}

	private function touch_cache() {
		if(!is_file(sc_cache_dir() . 'touch.php')) {
			file_put_contents(
				sc_cache_dir() . 'touch.php',
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

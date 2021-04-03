<?php
class SimpleCacheControllerEventListener extends BcControllerEventListener {
    public $events = [
        'initialize'
    ];

    public function initialize(CakeEvent $event) {
		if ($this->isUninstallAction($event)) {
			$this->unSetLoginCookie();
			$this->uninstall();
		}

		if (BcUtil::loginUserName()) {
			if(!defined('SimpleCacheInstalled')) {
				$this->modify_indexphp();
			}
			if(empty($_COOKIE['BASER_LOGGED_IN'])) {
				$this->setLoginCookie();
			}
			return;
		}

		if(!empty($_COOKIE['BASER_LOGGED_IN'])) {
			$this->unSetLoginCookie();
			return;
		}
	}

	private function setLoginCookie() {
		setcookie(
			'BASER_LOGGED_IN',
			'loggedin',
			time()+60*60*24*180,
			baseUrl()
		);
	}

	private function unSetLoginCookie() {
		setcookie(
			'BASER_LOGGED_IN',
			'',
			time()-3600, baseUrl()
		);
}

	private function isUninstallAction($event) {
		$controller = $event->subject();
		if ($controller->name !== 'Plugins') {
			return false;
		}
		if ($controller->request->param('action') !=='admin_ajax_delete') {
			return false;
		}
		if ($controller->request->param('pass.0') !== 'SimpleCache') {
			return false;
		}
		return true;
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
}

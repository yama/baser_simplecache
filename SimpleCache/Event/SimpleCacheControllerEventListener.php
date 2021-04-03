<?php
class SimpleCacheControllerEventListener extends BcControllerEventListener {
    public $events = [
        'initialize'
    ];
    public function initialize(CakeEvent $event) {
		if (BcUtil::loginUserName()) {
			if(empty($_COOKIE['BASER_LOGGED_IN'])) {
				setcookie(
					'BASER_LOGGED_IN',
					'loggedin',
					time()+60*60*24*180,
					baseUrl()
				);
			}
		} elseif(!empty($_COOKIE['BASER_LOGGED_IN'])) {
			setcookie(
				'BASER_LOGGED_IN',
				'',
				time()-3600, baseUrl()
			);
		}
		if($_POST && str_ends_with(env('REQUEST_URI'), '/admin/plugins/ajax_delete/SimpleCache')) {
			$this->uninstall();
		}
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

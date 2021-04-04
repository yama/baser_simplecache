<?php
class SimpleCacheControllerEventListener extends BcControllerEventListener {
	public $events = [
		'initialize'
	];

	public function initialize(CakeEvent $event) {
		if ($this->isUninstallAction($event)) {
			unSetLoginCookie();
			$this->uninstall();
		}
	}

	private function isUninstallAction($event) {
		// /admin/plugins/ajax_delete/SimpleCache
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

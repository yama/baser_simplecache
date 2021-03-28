<?php
// App::uses('BcAuthComponent', 'Controller/Component');
// App::uses('BcUtil', 'Lib');
class SimpleCacheViewEventListener extends BcViewEventListener {
	public $components = ['Cookie', 'BcAuth'];

	public $events = array('beforeRenderFile');

	public function beforeRenderFile(CakeEvent $event) {
		if (BcUtil::loginUserName()) {
			if(empty($_COOKIE['BASER_LOGGED_IN'])) {
				// exit('test');
				// todo できればキー末尾にハッシュ文字列をつけたい
				setcookie('BASER_LOGGED_IN', 'loggedin', time()+60*60*24*180, baseUrl());
				// setcookie('BASER_LOGGED_IN', 'loggedin', 60*60*24*180, baseUrl());
			}
		} elseif(!empty($_COOKIE['BASER_LOGGED_IN'])) {
			setcookie('BASER_LOGGED_IN', '', time()-3600, baseUrl());
		}
	}
}

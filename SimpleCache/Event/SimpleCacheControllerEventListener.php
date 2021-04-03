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
    }
}

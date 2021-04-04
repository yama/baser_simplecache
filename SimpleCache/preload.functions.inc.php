<?php
function cache_dir() {
	return dirname(dirname(__DIR__)).'/tmp/cache/simplecache/';
}

function cache_filename() {
	return md5($_SERVER['REQUEST_URI']).'.txt';
}

function is_logged_in() {
	if(!isset($_COOKIE['BASER_LOGGED_IN'])) {
		return false;
	}
	return true;
}

class CGET {

    private $etag;

    public function __construct($timestamp=0) {
        if (!$timestamp) {
            return;
        }
        $this->etag = sprintf('"%s"', md5($timestamp));
        $this->run();
    }

    private function run() {
        header('ETag: '.$this->etag);
        if (filter_input( INPUT_SERVER, 'HTTP_IF_NONE_MATCH') !== $this->etag) {
            return;
        }
        header('HTTP', true, 304);
        header('Content-Length: 0');
        exit;
    }
}
